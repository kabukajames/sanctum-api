<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
   use App\Models\User;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $field = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed'
        ]);
            $user = User::create([

                'name'=>$field['name'],
                'email'=>$field['email'],
                'password'=>bcrypt($field['password'])


            ]);

            $token = $user->createToken('myAppToken')->plainTextToken;  
            $response = [
                'user'=>$user,
                'token'=>$token
            ];
                    return response($response,201); 
    }
    public function login(Request $request){
        $field = $request->validate([
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed'
        ]);
            
            //check the email 
            $user = User::where('email',$field['email'])->first();



            //check password
            if( !$user || !Hash::check($field['password'],$user->password)){



                    return response([

                        'message'=>'Wrong credentials'
                    ],401);
            }






            $token = $user->createToken('myAppToken')->plainTextToken;  
            $response = [
                'user'=>$user,
                'token'=>$token
            ];
                    return response($response,201); 
    }

    public function logout(Request $request){
            auth()->user()->tokens()->delete();
            return [

                'message'=>'logged out'


            ];




    }
}
