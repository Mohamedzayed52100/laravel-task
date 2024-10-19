<?php

namespace App\Http\Middleware;

use App\Repository\CustomerRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class LimitSendingOTP
{
    private CustomerRepository $customerRepository;

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
        $customerId = $this->customerRepository->getCustomerIdByPhone($phone);
        $userIdentifier = "send.otp";
        $userIdentifier .= $customerId ?? $phone;
        $now = Carbon::now();

        if (!empty((Redis::hget($userIdentifier, "count")) && Redis::hget($userIdentifier, "count") % 5 == 0)) {
            return response()->json([
                "message" => "We've noticed many attempts .Please wait for 5 Hours before trying again.",

            ], Response::HTTP_TOO_MANY_REQUESTS);

        } else {
            $futureDateTime = $now->addSeconds(18000);
            Redis::hmset($userIdentifier, [
                'can_request_at' => $futureDateTime->format('Y-m-d H:i:s'),
                "count" => (int)(Redis::hget($userIdentifier, "count")) + 1

            ]);
            Redis::expire($userIdentifier, 18000);
        }
        return $next($request);
    }
}
