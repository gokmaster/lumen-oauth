<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Oauthclient;
use App\User;

class UserController extends Controller
{
    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:4'
            ]);

            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => password_hash($request->input('password'), PASSWORD_BCRYPT),
            ];

            User::create($data);
                
            $response = [
                'message' => 'User registration successful',
            ]; 
            return (new Response($response, 200));

        } catch (\Illuminate\Validation\ValidationException $e ) {
            // When there is any invalid input
            $response = [
                'message' => $e->errors(),
            ];
            return (new Response($response, 400));

        } catch(\Exception $e) {
            // When query fails. 
            $response = [
                'message' => $e->getMessage(),
            ];
            return (new Response($response, 500));
        }
    }

    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

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

            // get auth token if authentication is successful
            $authResponse = $this->getAuthToken($email, $password);

            $userDetails = User::select('name', 'email')->where('email', $email)->first();

            $response = [
                'message' => 'Login successful',
                'user-details' => $userDetails,
                'auth' => json_decode($authResponse, true),
            ];    
            return (new Response($response, 200));

        } catch (\Illuminate\Validation\ValidationException $e ) {
            // When there is any invalid input
            $response = [
                'message' => $e->errors(),
            ];
            return (new Response($response, 400));

        } catch(\Exception $e) {
            // When query fails. 
            $response = [
                'message' => $e->getMessage(),
            ];
            return (new Response($response, 500));
        }
    }

    public function getUserDetils($user_id) {
        return User::select('name', 'email', 'created_at')->where('id', $user_id)->first();
    }

    // Returns auth token if authentication is successful
    private function getAuthToken($email, $password) {
        $client = Oauthclient::select('id', 'secret')
                    ->where(DB::raw("TRIM(name)"), "Password Grant Client")
                    ->first();

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