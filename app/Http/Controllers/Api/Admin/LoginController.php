<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=> 'required|email',
            'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'success'=>false,
                'message'=>'login failed',
            ],400);
        }
        
        return response()->json([
            'success'=>true,
            'message'=>'login successfully',
            'user'=>$user,
            'token'=>$user->createToken('authToken')->accessToken
        ]);
        
    }
}
