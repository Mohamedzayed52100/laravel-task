<?php

namespace App\Services;

use App\Repository\CustomerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UserService
{
    private CustomerRepository $customerRepository;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getUserById($userId)
    {
        $userData = $this->getUserFromRedis($userId);
        if (!empty($userData)) {
            return $userData;
        }
        return $this->customerRepository->getUserById($userId);


    }

    public function getUserFromRedis($userid)
    {
        $userIdentifier = "user." . $userid;

        return Redis::hget($userIdentifier, "user");

    }

    public function updateUser($userId, $updatedData)
    {
        if (!empty($updatedData["password"])) {
            $updatedData["password"] = Hash::make($updatedData["password"]);
        }
       $isUpdated=  DB::table("users")->where("id", $userId)->update($updatedData);
        $userIdentifier = "user." . $userId;
        if (Redis::exists($userIdentifier)) {
            Redis::del($userIdentifier);
        }
        $user = $this->getUserById($userId);
        Redis::hmset($userIdentifier, [
            'user' => $user,
        ]);
        return $isUpdated;
    }

}
