<?php

namespace App\Http\Services;

use App\Repository\CustomerRepository;
use Illuminate\Support\Facades\Redis;

class OTPService
{
    private CustomerRepository $customerRepository;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function sendPhoneOTP($phone)
    {
        $optValue = $this->generateOTP();
        $this->customerRepository->insertPhoneOTP($phone, $optValue);
        return $optValue;
    }

    public function generateOTP()
    {
        return rand(1000, 9999);
    }

    public function verifyOTP($phone, $otpValue)
    {
        if ($this->customerRepository->verifyOTP($phone, $otpValue)) {
            $this->customerRepository->markCustomerAsVerified($phone);
            $otpToken = $this->generateCustomerOTPToken();
            $this->customerRepository->insertOTPToken($phone, $otpToken);
            $userId = $this->customerRepository->getCustomerIdByPhone($phone);
            $userIdentifier = "user." .$userId;
            $user = $this->customerRepository->getUserById($userId);

            Redis::hmset($userIdentifier, [
                'user' => $user,
            ]);
            return response()->json([
                "is_verified" => true,
                "otp_token" => $otpToken
            ]);
        } else {
            return response()->json([
                "is_verified" => false,
            ]);
        }

    }

    function generateCustomerOTPToken($length = 24)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}
