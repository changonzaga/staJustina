<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = '';
    public string $fromName   = '';
    public string $recipients = '';

    public string $userAgent  = 'CodeIgniter';
    public string $protocol   = 'smtp'; // use smtp, not mail
    public string $mailPath   = '/usr/sbin/sendmail';

    public string $SMTPHost   = '';
    public string $SMTPUser   = '';
    public string $SMTPPass   = '';
    public int    $SMTPPort   = 587;
    public int    $SMTPTimeout = 5;
    public bool   $SMTPKeepAlive = false;
    public string $SMTPCrypto = 'tls';

    public bool   $wordWrap   = true;
    public int    $wrapChars  = 76;
    public string $mailType   = 'html'; // better use html for formatted emails
    public string $charset    = 'UTF-8';
    public bool   $validate   = false;
    public int    $priority   = 3;
    public string $CRLF       = "\r\n";
    public string $newline    = "\r\n";
    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;
    public bool   $DSN        = false;

    public function __construct()
    {
        $this->fromEmail  = env('EMAIL_FROM_ADDRESS');
        $this->fromName   = env('EMAIL_FROM_NAME');
        $this->SMTPHost   = env('EMAIL_HOST');
        $this->SMTPUser   = env('EMAIL_USERNAME');
        $this->SMTPPass   = env('EMAIL_PASSWORD');
        $this->SMTPPort   = (int) env('EMAIL_PORT');
        $this->SMTPCrypto = env('EMAIL_ENCRYPTION');
        $this->protocol   = env('EMAIL_PROTOCOL');
    }
}
