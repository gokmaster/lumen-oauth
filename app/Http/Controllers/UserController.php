<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $response = [];
        try {
            $name =  $request->input('name');
            $email =  $request->input('email');
            $password = $request->input('password');

            $emailCount = User::where('email', $email)->count();

            if ( $emailCount > 0 ) {
                throw new \Exception( "The following email address already exist: $email" ); 
            }

            if (strlen($password) < 4) {
                throw new \Exception( "Password should be atleast 4 characters in length." ); 
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
            ];

            User::create($data);
                
            $response = [
                'status' => 1,
                'message' => 'User registration successful',
            ];    
        }
        catch(\Exception $e) {
            // When query fails. 
            $response = [
                'status' => 0,
                'message' => $e->getMessage(),
            ];
        }

        return response()->json($response);
    }
}