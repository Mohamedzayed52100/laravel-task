<?php

namespace App\Http\Services;

use App\Repository\CustomerRepository;

class SignupService
{
    private CustomerRepository $customerRepository;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function createCustomer(array $data)
    {
        return $this->customerRepository->createCustomer($data);
    }

    public function authenticateCustomer($customerEmail)
    {
        $user = $this->customerRepository->getCustomerByEmail($customerEmail);
        return response()->json([
            "accessToken" => $user->createToken('auth_token')->accessToken,
            "user" => $user
        ], 200);

    }

}
