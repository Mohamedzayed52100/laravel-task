<?php

namespace App\Http\Controllers;

use App\DTO\LoginDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Services\LoginService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private LoginService $loginService;

    /**
     * @param LoginService $loginService
     */
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        $email  =$request->input("email");
        $password  =$request->input("password");
        $loginDto = new LoginDTO($email , $password);
        return  $this->loginService->login($loginDto);
    }
}
