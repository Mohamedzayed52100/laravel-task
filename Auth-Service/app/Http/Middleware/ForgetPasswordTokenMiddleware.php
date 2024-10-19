<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use App\Repository\CustomerRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForgetPasswordTokenMiddleware
{
    protected CustomerRepository $customerRepository;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $phone = $request->input("phone");
        $otpToken = $request->input("otp_token");
        if ($this->customerRepository->isOTPTokenValid($phone, $otpToken)) {

            return $next($request);
        } else {
            throw  new CustomException("customer otp token not valid", 401);
        }
    }
}
