<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class SMS extends BaseConfig
{
	/**
	 * PhilSMS API base URL
	 */
	public $baseURL = 'https://app.philsms.com/api/v3';

	/**
	 * API Token for PhilSMS (set via env: sms.token)
	 */
	public $token = '3003|UQEYIqLcOL4eRMQ6cBKoqvsdRFL5KsOrxCMjQb7o';

	/**
	 * Optional alphanumeric sender id (registered in PhilSMS)
	 * Set via env: sms.senderId
	 */
	public $senderId = 'PhilSMS';

	/**
	 * Request timeout seconds
	 */
	public $timeout = 15;

	/**
	 * Max retries for transient failures
	 */
	public $maxRetries = 2;

	/**
	 * Delay between retries (seconds)
	 */
	public $retryDelay = 2;

	public function __construct()
	{
		parent::__construct();
		// Prefer .env overrides when available
		$this->token = env('sms.token', $this->token);
		$this->senderId = env('sms.senderId', $this->senderId);
		$this->timeout = (int) env('sms.timeout', $this->timeout);
		$this->maxRetries = (int) env('sms.maxRetries', $this->maxRetries);
		$this->retryDelay = (int) env('sms.retryDelay', $this->retryDelay);
	}
}