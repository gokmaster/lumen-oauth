<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Oauthclient;
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
                'status' => "success",
                'message' => 'User registration successful',
            ];    
        }
        catch(\Exception $e) {
            // When query fails. 
            $response = [
                'status' => "failed",
                'message' => $e->getMessage(),
            ];
        }

        return response()->json($response);
    }

    public function login(Request $request)
    {
        $response = [];
        try {
            $email =  $request->input('email');
            $password = $request->input('password');

            $emailCount = User::where('email', $email)->count();
            if ( $emailCount == 0 ) {
                throw new \Exception( "The email address, $email does not exist." ); 
            }

            $d = User::select('password')->where('email', $email)->first();
            // if password is incorrect
            if(!password_verify($password, $d->password)) {
                throw new \Exception( "Invalid password." ); 
            } 

            $userDetails = User::select('name', 'email')->where('email', $email)->first();

            // get auth token if authentication is successful
            $authResponse = $this->getAuthToken($email, $password);

            $response = [
                'status' => "success",
                'message' => 'Login successful',
                'user-details' => $userDetails,
                'auth' => json_decode($authResponse, true),
            ];    
        }
        catch(\Exception $e) {
            // When query fails. 
            $response = [
                'status' => "failed",
                'message' => $e->getMessage(),
            ];
        }

        return response()->json($response);
    }

    // Returns auth token if authentication is successful
    private function getAuthToken($email, $password) {
        $client = Oauthclient::select('id', 'secret')->where(DB::raw("TRIM(name)"), "Password Grant Client")->first();

        $params = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '*',
        ];

        // call the '/oauth/token' route internally within this app to get auth token
        $req = Request::create('/oauth/token', 'POST', $params);
        $res = app()->handle($req);
        return $res->getContent();
    }
}