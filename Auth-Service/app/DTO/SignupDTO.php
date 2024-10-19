<?php

namespace App\DTO;

class SignupDTO
{
    private string $name;
    private string $email;
    private string $password;
    private string $phone;

    /**
     * @param string $email
     * @param string $name
     * @param string $password
     * @param string $phone
     */
    public function __construct(string $email, string $name, string $password, string $phone)
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->phone = $phone;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
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
