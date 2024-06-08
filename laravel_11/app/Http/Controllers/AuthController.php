<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    public function register(Request $request)
    {
        // $data_validation = $request->validate([
        //     "email"=> "required|email|string|unique:users|max:128",
        //     "name"=> "required|string|min:3",
        //     "password"=> "required|string|min:6",
        // ]);

        // $user = User::create($data_validation);
        // $token = auth('api')->login($user);

        // return $this->respondWithToken($token);
        try{
            $data_validation = $request->validate([
                "email" => "required|email|string|unique:users|max:128",
                "name" => "required|string|min:3",
                "password" => "required|string|min:6",
            ]);
    
            $user = User::create($data_validation);
            $token = auth('api')->login($user);
    
            $response = $this->respondWithToken($token, 'user');
            $response->withCookie(cookie('api_token', $token, 60));
    
            return $response;
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 406);
        }catch (Exception $e) {
            return response()->json(['error' => "Failed to create user"], 400);
        }
        
    }

    public function login()
    {
        // $credentials = request(['email', 'password']);

        // if (! $token = auth('api')->attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // $role = auth('api')->user()->role;

        // return $this->respondWithToken($token, $role);

        try{
            $credentials = request(['email', 'password']);

            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'email or password incorrect'], 401);
            }
    
            $response = $this->respondWithToken($token);
            $response->withCookie(cookie('api_token', $token, 60));
    
            return $response;
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to login"], 400);
        }
    }


    public function me()
    {
        return response()->json(auth('api')->user());
    }


    public function logout()
    {
        // auth('api')->logout();

        // return response()->json(['message' => 'Successfully logged out']);
        auth('api')->logout();

        // Clear the token cookie
        $response = response()->json(['message' => 'Successfully logged out']);
       $response->withCookie(cookie()->forget('api_token'));

        return $response;
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }


    protected function respondWithToken($token, $role = "user")
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'role' => $role
        ]);
    }
}
