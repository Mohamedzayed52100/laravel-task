<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function getCustomer()
    {
        $customer= auth("api")->user();
        return response()->json([
            "user" =>$customer
        ]);

    }
}
