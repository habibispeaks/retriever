<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                "email" => "required|email",
                "password" => "required"
            ]
        );

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }
        $email_status = User::where("email", $request->email)->first();
        if (is_null($email_status)) {
            return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Email doesn't exist."]);
        } else {
            $credentials = $request->only('email', 'password');

            $token = Auth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::user();
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }

    }


    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "uname" => "required",
            "email" => "required|email",
            "password" => "required",
            // "password" => "required|min:6",
            // "confirmpassword" => "required",
            "phone" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $user_status = User::where("email", $request->email)->first();

        if (!is_null($user_status)) {
            return response()->json(["status" => "failed", "success" => false, 'error' => 'Whoops! email already registered']);
        } else {
            $user = User::create([
                'fname' => $request->fname,
                'uname' => $request->uname,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),

                // 'password' => $request->password,
            ]);

            if (!is_null($user)) {
                $token = Auth::login($user);
                return response()->json([
                    'status' => 'success',
                    'message' => 'User created successfully',
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);

            } else {
                return response()->json(["status" => "failed", "success" => false, 'error' => 'failed to register']);
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}
