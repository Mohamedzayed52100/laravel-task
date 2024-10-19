<?php

namespace App\Http\Services;

use App\DTO\ForgetPasswordDTO;
use App\Repository\CustomerRepository;

class PasswordService
{
    private CustomerRepository $customerRepository;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function forgerPassword(ForgetPasswordDTO $forgetPasswordDTO)
    {
        $phone= $forgetPasswordDTO->getPhone();
        $password =$forgetPasswordDTO->getPassword();
        return $this->customerRepository->updatePassword($phone, $password);
    }


}
