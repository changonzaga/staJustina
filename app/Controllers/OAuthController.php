<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OAuthLog;

class OAuthController extends BaseController
{
    protected $oauthLog;

    public function __construct()
    {
        $this->oauthLog = new OAuthLog();
    }

    /**
     * Display OAuth logs
     */
    public function logs()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('adminData')['auth_type'] !== 'email') {
            return redirect()->to('/admin/login')
                ->with('fail', 'You must be logged in as an administrator to view OAuth logs.');
        }

        $data = [
            'pageTitle' => 'OAuth Logs',
            'logs' => $this->oauthLog->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('backend/admin/oauth/logs', $data);
    }

    /**
     * Clear all OAuth logs
     */
    public function clearLogs()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('adminData')['auth_type'] !== 'email') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        try {
            // Truncate the oauth_logs table
            $this->oauthLog->truncate();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'OAuth logs cleared successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Failed to clear OAuth logs: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to clear logs'
            ]);
        }
    }

    /**
     * Get failed login attempts
     */
    public function getFailedAttempts()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('adminData')['auth_type'] !== 'email') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $limit = $this->request->getGet('limit') ?? 10;
        $failedAttempts = $this->oauthLog->getFailedAttempts($limit);

        return $this->response->setJSON([
            'success' => true,
            'data' => $failedAttempts
        ]);
    }

    /**
     * Get login attempts by email
     */
    public function getAttemptsByEmail()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('adminData')['auth_type'] !== 'email') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $email = $this->request->getGet('email');
        if (!$email) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email parameter is required'
            ]);
        }

        $limit = $this->request->getGet('limit') ?? 10;
        $attempts = $this->oauthLog->getAttemptsByEmail($email, $limit);

        return $this->response->setJSON([
            'success' => true,
            'data' => $attempts
        ]);
    }
}