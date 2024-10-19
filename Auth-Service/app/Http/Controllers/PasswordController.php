<?php

namespace App\Http\Controllers;

use App\DTO\ForgetPasswordDTO;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Services\PasswordService;

class PasswordController extends Controller
{
    private PasswordService $passwordService;

    /**
     * @param PasswordService $passwordService
     */
    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $request->validated();
        $newPassword = $request->input("password");
        $phone = $request->input("phone");
        $otpToken = $request->input("otp_token");
        $forgetPasswordDTO  = new ForgetPasswordDTO($newPassword ,$otpToken , $phone);
        $isUpdated = $this->passwordService->forgerPassword($forgetPasswordDTO);
        return response()->json([
            "update_status" => $isUpdated
        ]);

    }
}
