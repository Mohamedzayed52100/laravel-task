<?php

namespace Tests\Feature;

use App\Repository\CustomerRepository;
use App\Services\UserService;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    protected $userService;
    protected $customerRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->createMock(UserService::class);
        $this->customerRepository = $this->createMock(CustomerRepository::class);

        $this->app->instance(UserService::class, $this->userService);
        $this->app->instance(CustomerRepository::class, $this->customerRepository);
    }

    public function testGetUserByIdReturnsUserFromRedis()
    {
        $userId = 1;
        $expectedUser = [
            'id' => $userId,
            'name' => 'ahmed khaled'
        ];

        $this->userService->method('getUserById')->willReturn($expectedUser);

        $response = $this->getJson("http://127.0.0.1:8000/api/user/{$userId}");

        $response->assertStatus(200);
    }

    public function testGetUserByIdReturnsUserFromRepository()
    {
        $userId = 2;
        $expectedUser = ['id' => $userId, 'name' => 'Jane Doe'];

        $this->userService->method('getUserById')->willReturn(null);
        $this->customerRepository->method('getUserById')->willReturn($expectedUser);

        $response = $this->getJson("http://127.0.0.1:8000/api/user/{$userId}");

        $response->assertStatus(200);

    }


    public function testUpdateUserReturnsStatusCode200()
    {
        $data = [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'password' => 'newpassword'
        ];


        $response = $this->putJson("http://127.0.0.1:8000/api/update/1", $data);

        $response->assertStatus(200);
    }
}

