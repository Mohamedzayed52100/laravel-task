<?php

namespace App\DTO;

class ForgetPasswordDTO
{
    private string $password;
    private  string $optToken ;
    private string $phone;

    /**
     * @param string $password
     * @param string $optToken
     * @param string $phone
     */
    public function __construct(string $password, string $optToken, string $phone)
    {
        $this->password = $password;
        $this->optToken = $optToken;
        $this->phone = $phone;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getOptToken(): string
    {
        return $this->optToken;
    }

    public function setOptToken(string $optToken): void
    {
        $this->optToken = $optToken;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }



}
