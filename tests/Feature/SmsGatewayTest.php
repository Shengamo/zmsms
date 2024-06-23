<?php

namespace Shengamo\Zmsms\Tests\Feature;

use Shengamo\Zmsms\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Shengamo\Zmsms\Zmsms;

class SmsGatewayTest extends TestCase
{
    public function test_get_balance()
    {
        Http::fake([
            'https://zmsms.online/api/v1/balance' => Http::response([
                'response_code' => '0',
                'response_description' => 'Your balance is 947 SMS(s)',
                'balance' => 947
            ], 200)
        ]);

        $zmsms = new Zmsms();
        $balance = $zmsms->getBalance();

        $this->assertEquals(947, $balance);
    }

    public function test_can_send_SMS_with_sufficient_balance()
    {
        Http::fake([
            config('zmsms.base_url') . 'balance' => Http::response([
                'response_code' => '0',
                'response_description' => 'Your balance is 947 SMS(s)',
                'balance' => 947
            ], 200),
            config('zmsms.base_url') . 'bulksms' => Http::response([
                'response_code' => '0',
                'response_description' => 'Message Sent'
            ], 200)
        ]);

        $zmsms = new Zmsms();
        $response = $zmsms->sendSMS('MagicBrains', 'Hello, this is a test message.', ['0971977252', '0776639088']);

        $this->assertEquals('0', $response['response_code']);
        $this->assertEquals('Message Sent', $response['response_description']);
    }

    public function test_send_SMS_with_insufficient_balance()
    {
        Http::fake([
            config('zmsms.base_url') . 'balance' => Http::response([
                'response_code' => '0',
                'response_description' => 'Your balance is 0 SMS(s)',
                'balance' => 0
            ], 200)
        ]);

        $zmsms = new Zmsms();
        $response = $zmsms->sendSMS('MagicBrains', 'Hello, this is a test message.', ['0971977252', '0776639088']);

        $this->assertEquals('0', $response['response_code']);
        $this->assertEquals('Insufficient balance', $response['response_description']);
    }

    public function test_send_SMS_with_insufficient_balance_for_multiple_recepients()
    {
        Http::fake([
            config('zmsms.base_url') . 'balance' => Http::response([
                'response_code' => '0',
                'response_description' => 'Your balance is 2 SMS(s)',
                'balance' => 2
            ], 200),
            config('zmsms.base_url') . 'bulksms' => Http::response([
                'response_code' => '0',
                'response_description' => 'Message Sent'
            ], 200)
        ]);

        $zmsms = new Zmsms();
        $response = $zmsms->sendSMS('MagicBrains', 'Hello, this is a test message.', ['0971977252', '0967123456', '0776639088']);

        $this->assertEquals('0', $response['response_code']);
        $this->assertEquals('Insufficient balance', $response['response_description']);
    }
}
