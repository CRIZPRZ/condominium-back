<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {

        try {

        $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|string|email|unique:users',
            'password'  => 'required'
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            // 'phone'         => $request->phone,
            // 'property_id'   => 1
        ]);

        return $this->successResponse($user, 'Successfully created user!');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, ['error'=> $th->getMessage()]);
        }
    }

}
