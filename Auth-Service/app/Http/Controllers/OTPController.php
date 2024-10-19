<?php

namespace App\Http\Controllers;

use App\Http\Requests\OTPSenderRequest;
use App\Http\Requests\OTPVerificationRequest;
use App\Http\Services\OTPService;

class OTPController extends Controller
{
    private OTPService $OTPService;

    /**
     * @param OTPService $OTPService
     */
    public function __construct(OTPService $OTPService)
    {
        $this->OTPService = $OTPService;
    }

    public function sendOTP(OTPSenderRequest $request)
    {
        $request->validated();
        $phone = $request->input("phone");
        $otpValue = $this->OTPService->sendPhoneOTP($phone);
        return response()->json([
            "otp_value" => $otpValue
        ]);

    }

    public function verifyOTP(OTPVerificationRequest $request)
    {
        $request->validated();
        $phone = $request->input("phone");
        $otpValue = $request->input("otp_value");
        return $this->OTPService->verifyOTP($phone, $otpValue);


    }
}
