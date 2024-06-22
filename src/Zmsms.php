<?php

namespace Shengamo\Zmsms;

use Illuminate\Support\Facades\Http;

class Zmsms
{
    private const MAX_RETRY_ATTEMPTS = 3;
    protected string $baseUrl;
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl = config('zmsms.base_url');
        $this->username = config('zmsms.username');
        $this->password = config('zmsms.password');
    }

    public function getBalance(): int
    {
        $response = Http::post("{$this->baseUrl}balance", [
            'username' => $this->username,
            'password' => $this->password
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        if ($responseBody['response_code'] == '0') {
            // Extract the numeric balance from the response description
            if (preg_match('/Your balance is (\d+) SMS/', $responseBody['response_description'], $matches)) {
                return (int)$matches[1]; // Convert the extracted balance to an integer
            }
        }

        return 0;
    }

    public function sendSMS($senderId, $message, $phoneNumbers)
    {
        $balance = $this->getBalance();
        $numPhoneNumbers = count($phoneNumbers);

        if ($balance < $numPhoneNumbers) {
            return [
                'response_code' => '1',
                'response_description' => 'Insufficient balance'
            ];
        }

        $response = Http::post("{$this->baseUrl}bulksms", [
                'auth' => [
                    'username' => $this->username,
                    'password' => $this->password
                ],
                'sender_id' => $senderId,
                'message' => $message,
                'phone_numbers' => $phoneNumbers
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
