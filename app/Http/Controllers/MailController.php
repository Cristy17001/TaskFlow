<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailModel;
use App\Models\User;

class MailController extends Controller
{
    function send(Request $request) { 
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found!']);
        }
        $token = Password::createToken($user);
        $mailData = [
            'email' => $request->email,
            'token' => $token,
        ];

        Mail::to($request->email)->send(new MailModel($mailData));
        return redirect()->route('login');
    }
}
