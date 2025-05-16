<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        // if (!isset($credentials['email']) || !isset($credentials['password'])) {
        //     return response()->json([
        //         'error' => 'Email and password is required'
        //     ], 401);
        // }
        $validator = Validator::make(request()->all(), [
            'email'     => 'bail|required|exists:users,email',
            'password'  => 'required'
        ],[
            'email.required'    => 'Please Enter Email Id',
            'password.required' => 'Please Enter Password'
        ])->stopOnFirstFailure(true);
        
        $validator->validate();

        // request()->validate([
        //     'email'     => 'bail|required|exists:users,email',
        //     'password'  => 'required'
        // ],[
        //     'email.required'    => 'Please Enter Email Id',
        //     'password.required' => 'Please Enter Password'
        // ])->stopOnFirstFailure(true);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid email or password'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $id = auth('api')->user()->id;

        $user = User::with(array('hrEmployee' => function($query) {
            $query->select('user_id', 'office_shift_id', 'reports_to', 'first_name', 'last_name', 'username', 'email', 'date_of_birth', 'gender', 'user_role_id', 'department_id', 'date_of_joining', 'date_of_leaving', 'is_active');
        }))->find($id, ['id', 'email', 'first_name', 'last_name', 'type', 'status', 'role_id']);


        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
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
            'expires_in' => auth('api')->factory()->getTTL() * 90
        ]);
    }
}
