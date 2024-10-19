<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Models\User;


class OTPControllerTest extends TestCase
{

    public function testSendOtpReturnsStatusCode200()
    {
        $data = [
            'phone' => '1234567890',
        ];

        $otpValue = '123456';
        $this->mock(\App\Http\Services\OTPService::class, function ($mock) use ($otpValue) {
            $mock->shouldReceive('sendPhoneOTP')
                ->with('1234567890')
                ->andReturn($otpValue);
        });

        $response = $this->postJson("http://127.0.0.1:8000/api/send-otp", $data);

        $response->assertStatus(200);
    }
}
