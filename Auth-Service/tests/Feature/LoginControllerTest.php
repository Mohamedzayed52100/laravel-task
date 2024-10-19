<?php

namespace Tests\Feature;

use Tests\TestCase;


class LoginControllerTest extends TestCase
{


    public function testLoginReturnsStatusCode200()
    {
        $data = [
            "password" => "123451789",
            "email" => "ahmed.zayedd@gmail.com"
        ];

//        Redis::shouldReceive('hmset')
//            ->once()
//            ->with("user." . $this->user->id, [
//                'user' => $this->user,
//            ]);

        $response = $this->postJson("http://127.0.0.1:8000/api/login", $data);

        $response->assertStatus(200);
    }
}
