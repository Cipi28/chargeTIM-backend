<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    const RESOURCE_MODEL = User::class;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);
        try {

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => ["Account doesn't exist!"]], 404);
        }

        /** @var User $user */
        $user = auth()->user();

        /** @var LoginService $loginService */
        $loginService = app(LoginService::class);
        $responseData = $loginService->generateUserStorageData($user, auth());
        } catch (TokenExpiredException $e) {
            return response(null, [], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response(null, [], $e->getStatusCode());
        } catch (JWTException $e) {
            return response(null, [], $e->getStatusCode());
        }

        return response()->json($responseData);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:25',
            'email' => 'required|email|max:25',
            'password' => 'required|string|min:6|max:25',
            'address' => 'string|max:30|regex:/^[a-zA-Z0-9\s,.]+$/',
            'role' => 'required'
        ], [
            'address.regex' => 'The address format is invalid. It should contain only letters, numbers, spaces, and the following characters: , .'
        ]);
        try {

            $credentials = request(['name', 'email', 'password', 'address', 'role']);

            // Create a new user
            $user = new User();
            $user->name = $credentials['name'];
            $user->email = $credentials['email'];
            $user->password = app('hash')->make($credentials['password']);
            $user->address = $credentials['address'];
            $user->role = $credentials['role'];
            $user->save();

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 404);
            }

            /** @var User $user */
            $user = auth()->user();

            /** @var LoginService $loginService */
            $loginService = app(LoginService::class);
            $responseData = $loginService->generateUserStorageData($user, auth());
        } catch (TokenExpiredException $e) {
            return response(null, [], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response(null, [], $e->getStatusCode());
        } catch (JWTException $e) {
            return response(null, [], $e->getStatusCode());
        }

        return response()->json($responseData);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
