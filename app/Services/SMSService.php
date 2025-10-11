<?php

namespace App\Services;

use Config\SMS as SMSConfig;
use CodeIgniter\HTTP\CURLRequest;

class SMSService
{
	protected $config;
	protected $http;

	public function __construct(?SMSConfig $config = null)
	{
		$this->config = $config ?? config('SMS');
		$this->http = service('curlrequest', [
			'timeout' => $this->config->timeout,
			'http_errors' => false, // allow 4xx/5xx without throwing so we can read status/body
			'headers' => [
				'Authorization' => 'Bearer ' . $this->config->token,
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
			],
		]);
	}

	/**
	 * Send an SMS via PhilSMS
	 *
	 * @param string $recipient E.164 without '+', e.g. 63917xxxxxxx
	 * @param string $message   The SMS text
	 * @param string|null $senderId Optional sender id (overrides config)
	 * @return array { success: bool, status: int|null, body: mixed, error?: string }
	 */
	public function send(string $recipient, string $message, ?string $senderId = null): array
	{
		if (empty($this->config->token)) {
			return [
				'success' => false,
				'status' => null,
				'body' => null,
				'error' => 'Missing sms.token configuration',
			];
		}

		$payload = [
			'recipient' => $recipient,
			'message' => $message,
		];
		$effectiveSender = $senderId ?? $this->config->senderId;
		if (!empty($effectiveSender)) {
			$payload['sender_id'] = $effectiveSender;
		}

		$url = rtrim($this->config->baseURL, '/') . '/sms/send';

		$attempt = 0;
		$lastError = null;
		while ($attempt <= $this->config->maxRetries) {
			try {
				$response = $this->http->post($url, [
					'json' => $payload,
				]);
				$status = $response->getStatusCode();
				$rawBody = (string) $response->getBody();
				$body = json_decode($rawBody, true);

				if ($status >= 200 && $status < 300) {
					return [
						'success' => true,
						'status' => $status,
						'body' => $body,
					];
				}

				// Non-2xx: capture body for diagnostics
				$lastError = 'HTTP ' . $status . ' response';
				// Log a compact error without sensitive headers
				log_message('error', 'PhilSMS send failed: status=' . $status . ' body=' . ($rawBody ?: '[empty]'));
				return [
					'success' => false,
					'status' => $status,
					'body' => $body ?? $rawBody,
					'error' => $lastError,
				];
			} catch (\Throwable $e) {
				$lastError = $e->getMessage();
			}

			$attempt++;
			if ($attempt <= $this->config->maxRetries) {
				sleep($this->config->retryDelay);
			}
		}

		return [
			'success' => false,
			'status' => null,
			'body' => null,
			'error' => $lastError,
		];
	}
}

