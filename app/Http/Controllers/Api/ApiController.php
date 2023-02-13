<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if (!$token = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Login credentials are invalid.',
            ], 400);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::invalidate($request->token);

        return response()->json([
            'status' => true,
            'message' => 'User has been logged out'
        ]);
    }

    public function get_user()
    {
        return response()->json(['user' => auth()->user()]);
    }

    public function refresh()
    {
        return $this->respondWithToken(\PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $JWTAuth = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::class;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
