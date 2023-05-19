<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if(Auth::attempt( $credentials )){

            $user = Auth::user();

            // $tokens = Token::where('user_id',Auth::user()->id)->update(['revoked' => true]);

            $tokenResult = $user->createToken('CONDOMINIUM APP');

            $token = $tokenResult->token;

            if ($request->remember_me)

            $token->expires_at = Carbon::now()->addWeeks(1);

            $token->save();

            $data['user'] =  $user;
            $this->getPermisions($user);
            $data['token'] =  $tokenResult->accessToken;
            $data['token_type'] =  'Bearer';
            $data['expires_at'] = Carbon::parse($token->expires_at)->toDateTimeString();

            return $this->successResponse($data, 'User login successfully.');
        }
        else{
            return $this->errorResponse('unauthorized', ['error'=>'User does not exists!']);
        }

    }


    public function checkToken()
    {


        return $this->successResponse('token', 'Token Validate.');

    }



     public function getPermisions($user)
     {

        $permissions = $user->getPermissionsViaRoles()->map(function($permission) {
            return $permission->name;
        })->toArray();

         return $permissions;
     }

}
