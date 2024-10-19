<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUser($id)
    {
        return response()->json([
            "user" => $this->userService->getUserById($id)
        ]);
    }

    public function update($id, Request $request)
    {

        if (empty($id)) {
            return response()->json([
                'message' => "id not founded"
            ], 422);
        }

        return response()->json([
            "updated_status" =>  $this->userService->updateUser($id, $request->toArray())
        ]);

    }
}
