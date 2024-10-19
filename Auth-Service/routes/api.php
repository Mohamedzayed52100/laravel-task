<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post("login", [LoginController::class, "login"]);
Route::post("register", [SignupController::class, "signup"]);
Route::middleware(['ForgetPasswordTokenMiddleware'])->group(function () {
    Route::post("forgot-password", [PasswordController::class, "forgetPassword"]);
});

Route::middleware(['AuthCustomerMiddleware'])->group(function () {

    Route::get("user", [CustomerController::class, "getCustomer"]);
});
Route::middleware(['LimitSendingOTP'])->group(function () {
    Route::post("send-otp", [OTPController::class, "sendOTP"]);
});
Route::post("verify-otp", [OTPController::class, "verifyOTP"]);




