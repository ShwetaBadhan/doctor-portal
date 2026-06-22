<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class InterkartsWhatsAppService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $instanceId;

    public function __construct()
    {
        $this->baseUrl    = config('services.interkarts.base_url');
        $this->apiKey     = config('services.interkarts.api_key');
        $this->instanceId = config('services.interkarts.instance_id');
    }

    /**
     * Send a simple text message
     */
    public function sendTextMessage(string $phoneNumber, string $message): Response
    {
        $payload = [
            'instance_id' => $this->instanceId,
            'api_key'     => $this->apiKey,
            'number'      => $phoneNumber,
            'type'        => 'text',
            'text'        => $message,
        ];

        return $this->makeRequest('/send-message', $payload);
    }

    /**
     * Send a pre-approved WhatsApp Template
     */
    public function sendTemplateMessage(string $phoneNumber, string $templateName, array $variables = []): Response
    {
        $payload = [
            'instance_id' => $this->instanceId,
            'api_key'     => $this->apiKey,
            'number'      => $phoneNumber,
            'type'        => 'template',
            'template'    => [
                'name'      => $templateName,
                'language'  => 'en', 
                'variables' => $variables, 
            ],
        ];

        return $this->makeRequest('/send-message', $payload);
    }

    /**
     * Core HTTP Request Handler
     */
    protected function makeRequest(string $endpoint, array $payload): Response
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        return Http::timeout(30)->post($url, $payload);
    }
}