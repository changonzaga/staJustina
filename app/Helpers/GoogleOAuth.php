<?php

namespace App\Helpers;

use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;
use CodeIgniter\HTTP\ResponseInterface;
use Config\App;

class GoogleOAuth
{
    private $client;
    private $oauth2Service;

    public function __construct()
    {
        try {
            $this->client = new Google_Client();
            
            $clientId = getenv('GOOGLE_CLIENT_ID');
            $clientSecret = getenv('GOOGLE_CLIENT_SECRET');
            
            if (empty($clientId) || empty($clientSecret)) {
                log_message('error', 'Missing Google OAuth credentials in environment configuration');
                throw new \RuntimeException('Google OAuth credentials not configured');
            }
            
            // Build absolute redirect URI using current base URL
            $baseURL = rtrim(config(App::class)->baseURL, '/');
            $redirectUri = $baseURL . '/admin/google/callback';
            
            log_message('debug', '--------------------');
            log_message('debug', 'Google OAuth Configuration:');
            log_message('debug', 'Client ID: ' . substr($clientId, 0, 8) . '...');
            log_message('debug', 'Base URL: ' . $baseURL);
            log_message('debug', 'Redirect URI: ' . $redirectUri);
            
            $this->client->setApplicationName('StaJustina Application');
            $this->client->setClientId($clientId);
            $this->client->setClientSecret($clientSecret);
            $this->client->setRedirectUri($redirectUri);
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');
            $this->client->addScope('email');
            $this->client->addScope('profile');

            $this->oauth2Service = new Google_Service_Oauth2($this->client);
            
            log_message('info', 'Google OAuth client successfully configured');
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to initialize Google OAuth client: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get Google OAuth login URL
     *
     * @return string
     */
    public function getAuthUrl(): string
    {
        $url = $this->client->createAuthUrl();
        log_message('info', 'Generated Google Auth URL: ' . $url);
        return $url;
    }

    /**
     * Handle OAuth callback and get user info
     *
     * @return array|null Token if successful, null if failed
     */
    public function handleCallback(): ?array
    {
        try {
            log_message('debug', '--------------------');
            log_message('debug', 'Processing Google OAuth Callback');
            
            $code = service('request')->getVar('code');
            if (empty($code)) {
                log_message('error', 'No authorization code received from Google');
                return null;
            }
            log_message('debug', 'Received authorization code from Google');

            // Exchange authorization code for access token
            log_message('debug', 'Attempting to exchange authorization code for access token');
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                log_message('error', 'Google OAuth token error: ' . $token['error']);
                if (isset($token['error_description'])) {
                    log_message('error', 'Error description: ' . $token['error_description']);
                }
                return null;
            }
            
            if (!isset($token['access_token'])) {
                log_message('error', 'No access token received in response');
                return null;
            }
            
            log_message('info', 'Successfully obtained access token');
            $this->client->setAccessToken($token);
            return $token;
            
        } catch (\Exception $e) {
            log_message('error', 'Google OAuth callback error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Get user information using access token
     *
     * @param array $token Access token
     * @return array|null User information if successful, null if failed
     */
    public function getUserInfo(array $token): ?array
    {
        try {
            log_message('debug', '--------------------');
            log_message('debug', 'Fetching Google user information');
            
            if (empty($token) || !isset($token['access_token'])) {
                log_message('error', 'Invalid token provided to getUserInfo');
                return null;
            }
            
            $this->client->setAccessToken($token);
            
            if ($this->client->isAccessTokenExpired()) {
                log_message('warning', 'Access token has expired, attempting to refresh');
                if (isset($token['refresh_token'])) {
                    try {
                        $token = $this->client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
                        log_message('info', 'Successfully refreshed access token');
                    } catch (\Exception $e) {
                        log_message('error', 'Failed to refresh token: ' . $e->getMessage());
                        return null;
                    }
                } else {
                    log_message('error', 'No refresh token available');
                    return null;
                }
            }
            
            log_message('debug', 'Requesting user information from Google');
            $userInfo = $this->oauth2Service->userinfo->get();
            
            $userData = [
                'email' => $userInfo->email,
                'name' => $userInfo->name,
                'picture' => $userInfo->picture
            ];
            
            log_message('info', 'Successfully retrieved user information for: ' . $userData['email']);
            return $userData;
            
        } catch (\Exception $e) {
            log_message('error', 'Google OAuth user info error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Verify if the user's email domain is allowed
     *
     * @param string $email
     * @return bool
     */
    public function isAllowedDomain(string $email): bool
    {
        try {
            if (empty($email)) {
                log_message('error', 'Empty email provided for domain validation');
                return false;
            }

            // Extract and normalize the domain
            $atPos = strrpos($email, '@');
            if ($atPos === false) {
                log_message('error', 'Invalid email format: ' . $email);
                return false;
            }

            $domain = strtolower(substr($email, $atPos + 1));
            $allowedDomains = [
                'cspc.edu.ph',
                'stajustina.edu.ph'
            ];

            log_message('debug', '--------------------');
            log_message('debug', 'Email Domain Validation:');
            log_message('debug', 'Full Email: ' . $email);
            log_message('debug', 'Extracted Domain: ' . $domain);
            log_message('debug', 'Allowed Domains: ' . implode(', ', $allowedDomains));

            foreach ($allowedDomains as $allowedDomain) {
                // Check if the email domain ends with the allowed domain
                if (str_ends_with($domain, $allowedDomain)) {
                    log_message('info', 'Domain authorized: ' . $domain . ' (matches ' . $allowedDomain . ')');
                    return true;
                }
            }

            log_message('warning', 'Domain not authorized: ' . $domain);
            return false;

        } catch (\Exception $e) {
            log_message('error', 'Domain validation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Revoke access token and logout
     *
     * @return void
     */
    public function logout(): void
    {
        if ($this->client->getAccessToken()) {
            $this->client->revokeToken();
        }
    }
}