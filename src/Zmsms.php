<?php

namespace Shengamo\Zmsms;

use Illuminate\Support\Facades\Http;

class Zmsms
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl = config('zmsms.base_url'); // Assuming this is correctly set in Laravel's config
        $this->username = config('zmsms.username');
        $this->password = config('zmsms.password');
    }

    public function getBalance(): int
    {
        try {
            $response = Http::post("{$this->baseUrl}balance", [
                'username' => $this->username,
                'password' => $this->password
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($responseBody['response_code'] == 0) {
                return $responseBody['balance'];
            }

            return 0; // Default to 0 balance if response format is unexpected
        } catch (\Exception $e) {
            // Handle HTTP or other exceptions
            report($e);
            return 0;
        }
    }

    public function sendSMS($senderId, $message, $phoneNumbers)
    {
        try {
            $balance = $this->getBalance();
            $numPhoneNumbers = count($phoneNumbers);

            if ($balance < $numPhoneNumbers) {
                return [
                    'response_code' => '0',
                    'response_description' => 'Insufficient balance'
                ];
            }

            $invalidNumbers = $this->validatePhoneNumbers($phoneNumbers);
            if (!empty($invalidNumbers)) {
                return [
                    'response_code' => '0',
                    'response_description' => 'Invalid phone numbers: ' . implode(', ', $invalidNumbers),
                    'invalid_numbers' => $invalidNumbers
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
        } catch (\Exception $e) {
            // Handle HTTP or other exceptions
            report($e);
            return [
                'response_code' => '99',
                'response_description' => 'Unexpected error occurred'
            ];
        }
    }

    private function validatePhoneNumbers($phoneNumbers): array
    {
        $invalidNumbers = [];
        foreach ($phoneNumbers as $number) {
            if (!$this->isValidZambianNumber($number)) {
                $invalidNumbers[] = $number;
            }
        }
        return $invalidNumbers;
    }

    private function isValidZambianNumber($number): bool
    {
        return preg_match('/^(26\d{10})|(097\d{7})|(096\d{7})|(095\d{7})|(076\d{7})|(077\d{7})|(075\d{7})$/', $number);
    }
}
