<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"auth"},
     *     operationId="userRegister",
     *     summary="Register New User",
     *     description="Register user and retrieve token",
     *     @OA\Response(response="201", description="Register as new user."),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *             required={"name","email", "password"},
     *             @OA\Property(property="name", type="string",example="James Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="jamesdoe@fakemail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="confident.folks123!")
     *          ),
     *     )
     * )
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:6'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        $token = $user->createToken('app-token')->plainTextToken;
        $user->token = $token;

        $response = ['data' => new UserResource($user)];
        return response($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"auth"},
     *     operationId="userLogin",
     *     summary="Login User",
     *     description="Login user with credential and retrieve token",
     *     @OA\Response(response="201", description="Login user with registered credential and retrieve token."),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *          @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="jamesdoe@fakemail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="confident.folks123!")
     *          ),
     *     )
     * )
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['errors' => 'The provided credentials are incorrect.'], 404);
        }
        $token = $user->createToken('app-token')->plainTextToken;
        $user->token = $token;

        $response = ['data' => new UserResource($user)];
        return response($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"auth"},
     *     operationId="userLogout",
     *     summary="Logout user account",
     *     security={ {"bearer": {} }},
     *     description="Logout current user with given credential",
     *     @OA\Response(response="201", description="Logout current user account given credential")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $response = ['data' => 'Logout successful.'];
        return response()->json($response, 201);
    }
}
