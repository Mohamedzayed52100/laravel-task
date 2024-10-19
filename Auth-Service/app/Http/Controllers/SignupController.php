<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Http\Services\SignupService;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    private SignupService $signupService;

    /**
     * @param SignupService $signupService
     */
    public function __construct(SignupService $signupService)
    {
        $this->signupService = $signupService;
    }

    public function signup(SignupRequest $request)
    {
        $request->validated();
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        $phone = $request->input("phone");
//        $signupDTO = new SignupDTO($email  ,$name , $password , $phone);
        $isInserted = $this->signupService->createCustomer([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
            "phone" => $phone
        ]);
        return $this->signupService->authenticateCustomer($email);
         ///php artisan passport:client --personal

    }
}
