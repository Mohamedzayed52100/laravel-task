<?php

namespace App\Http\Services;

use App\DTO\LoginDTO;
use App\Repository\CustomerRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class LoginService
{
    protected CustomerRepository $customerRepository;
    private OTPService $OTPService;


    /**
     * @param CustomerRepository $customerRepository
     * @param OTPService $OTPService
     */
    public function __construct
    (
        CustomerRepository $customerRepository,
        OTPService         $OTPService
    )
    {
        $this->customerRepository = $customerRepository;
        $this->OTPService = $OTPService;
    }

    public function login(LoginDTO $loginDTO)
    {
        $user = $this->customerRepository->getCustomerByEmail($loginDTO->getEmail());

        // Check if the user exists and if the password matches
        if (empty($user) || !Hash::check($loginDTO->getPassword(), $user->password)) {
            return response()->json([
                "message" => "unauthenticated"
            ], 401);
        }

        $phone = $this->customerRepository->getCustomerPhone($user->id);
        if (!empty($phone)) {
            return response()->json([
                "message" => "please enter OTP code",
                "otp_value" => $this->OTPService->sendPhoneOTP($phone)
            ]);
        }
        $token = $user->createToken('auth_token')->accessToken;
        $userIdentifier = "user." .$user->id;

        Redis::hmset($userIdentifier, [
            'user' => $user,
        ]);

        return response()->json([
            "accessToken" => $token,
            "user" => $user
        ], 200);
    }


}
