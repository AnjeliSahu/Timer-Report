<?php

namespace App\Libraries;

class FileMakerAPI
{
    private $config;
    private $token;

    public function __construct()
    {
        $this->config = [
            'server' => env('FILEMAKER_SERVER', 'https://172.16.8.104'),
            'database' => env('FILEMAKER_DATABASE', 'TimeTracker'),
            'username' => env('FILEMAKER_USERNAME', 'admin'),
            'password' => env('FILEMAKER_PASSWORD', 'Anjeli'),
        ];
    }

    public function authenticate()
    {
        $url = rtrim($this->config['server'], '/') . "/fmi/data/v1/databases/" . urlencode($this->config['database']) . "/sessions";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => '{}',
            CURLOPT_USERPWD => "{$this->config['username']}:{$this->config['password']}",
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Accept: application/json"
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            log_message('error', 'FileMaker authentication failed: HTTP ' . $httpCode);
            return false;
        }

        $data = json_decode($response, true);
        $this->token = $data['response']['token'] ?? null;
        
        return $this->token !== null;
    }

    public function getRecords($layout = 'Timer')
    {
        if (!$this->token && !$this->authenticate()) {
            return ['error' => 'Authentication failed'];
        }

        $url = rtrim($this->config['server'], '/') . "/fmi/data/v1/databases/" . urlencode($this->config['database']) . "/layouts/" . urlencode($layout) . "/records";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                "Content-Type: application/json",
                "Accept: application/json"
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            log_message('error', 'FileMaker records fetch failed: HTTP ' . $httpCode);
            return ['error' => 'Failed to fetch records'];
        }

        $data = json_decode($response, true);
        return $data['response']['data'] ?? [];
    }

    public function logout()
    {
        if (!$this->token) {
            return true;
        }

        $url = rtrim($this->config['server'], '/') . "/fmi/data/v1/databases/" . urlencode($this->config['database']) . "/sessions/" . $this->token;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                "Content-Type: application/json"
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        curl_exec($ch);
        curl_close($ch);

        $this->token = null;
        return true;
    }
}