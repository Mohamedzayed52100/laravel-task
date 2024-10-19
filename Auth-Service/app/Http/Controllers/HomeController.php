<?php

namespace App\Http\Controllers;

use App\Models\UsersPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{

    public function storePhoneNumber(Request $request)
    {
        //run validation on data sent in
        $validatedData = $request->validate([
            'phone_number' => 'required|unique:users_phone_number|numeric'
        ]);
        $user_phone_number_model = new UsersPhoneNumber($request->all());
        $user_phone_number_model->save();
        return back()->with(['success'=>"{$request->phone_number} registered"]);
    }

    /**
     * Show the forms with users phone number details.
     *
//     * @return Response
     */
    public function show()
    {
        $users = UsersPhoneNumber::all(); //query db with model
        return view('welcome', compact("users")); //return view with data
    }
    private function sendMessage($message, $recipients)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipients,
            ['from' => $twilio_number, 'body' => $message] );
    }
}
