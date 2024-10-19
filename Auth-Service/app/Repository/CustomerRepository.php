<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Contract\CustomerRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getCustomerByEmail($customerEmail)
    {
        return User::where("email", $customerEmail)->first();
    }

    public function createCustomer(array $payload)
    {
        return User::create($payload);
    }

    public function isPhoneSentOTPBefore($phone)
    {
        return DB::table("sms_otp")->where("phone", $phone)->exists();
    }

    public function insertPhoneOTP($phone, $otpValue)
    {
        $currentDateTime = Carbon::now();
        if (!$this->isPhoneSentOTPBefore($phone)) {
            DB::table("sms_otp")->insert([
                "phone" => $phone,
                "otp_value" => $otpValue,
                "expires_at"=> $currentDateTime->addMinutes(5) ,
                "created_at" => $currentDateTime,
                "updated_at" => $currentDateTime,
            ]);

        } else {
            DB::table("sms_otp")->where("phone", $phone)->update([
                "otp_value" => $otpValue,
                "expires_at"=> $currentDateTime->addMinutes(5) ,
                "updated_at" => now(),
            ]);
        }
    }

    public function verifyOTP($phone, $otpValue)
    {
        $currentDateTime = Carbon::now();

        return DB::table("sms_otp")->where([
            ["phone", $phone],
            ["otp_value", $otpValue] ,
            ["expires_at" , ">=" , $currentDateTime ]
        ])->exists();
    }

    public function markCustomerAsVerified($phone)
    {
        DB::table("users")->where("phone" , $phone)->update([
            "is_verified" =>1
        ]);

    }

    public function updatePassword($phone, $password)
    {
        return DB::table("users")->where("phone", $phone)->update([
            "password" => Hash::make($password)
        ]);

    }
//    public function generateForgetPasswordToken()
//    {
//        $otpToken = $this->generateCustomerOTPToken();
//
//    }
    public function isCustomerHasOTPToken($userIdentifier)
    {
        return DB::table("otp_token")->where("user_identifier" , $userIdentifier)->exists();

    }
    public function insertOTPToken($userIdentifier , $otp_token)
    {
        if(!$this->isCustomerHasOTPToken($userIdentifier)){
            return DB::table("otp_token")->insert([
                "otp_token" =>$otp_token,
                "user_identifier"=>$userIdentifier  ,
                "created_at" =>now() ,
                "updated_at"=>now()
            ]);


        }else {
            return DB::table("otp_token")->where("user_identifier", $userIdentifier)->update([
               "otp_token"=>$otp_token ,
                "updated_at"=>now()

            ]);

        }

    }

    public function isOTPTokenValid($userIdentifier , $otp_token)
    {
        return DB::table("otp_token")->where([
            ["user_identifier" , $userIdentifier],
            ["otp_token" , $otp_token]
        ])->exists();
    }
    public function getCustomerIdByPhone($phone)
    {
        return DB::table("users")->where("phone" , $phone)->value("phone");
    }

    public function getCustomerPhone($customerId)
    {
        return DB::table("users")->where("id" , $customerId)->value("phone");
    }


    public function getCustomerIdByEmail($email)
    {
        return DB::table("users")->where("email" ,$email)->value("id");
    }
    public function getUserById($userId)
    {
        return DB::table("users")->where("id" , $userId)->first();
    }

}
