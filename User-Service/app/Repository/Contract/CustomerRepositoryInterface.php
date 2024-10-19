<?php

namespace App\Repository\Contract;

interface CustomerRepositoryInterface
{
    public function getCustomerByEmail($customerEmail);

}
