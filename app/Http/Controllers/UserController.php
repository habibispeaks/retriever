<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Notifications\ClaimNotification;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private $status_code = 200;

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         "uname" => "required",
    //         "email" => "required|email",
    //         "password" => "required",
    //         // "password" => "required|min:6",
    //         // "confirmpassword" => "required",
    //         "phone" => "required"
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
    //     }

    //     // $name                   =       $request->name;
    //     // $name                   =       explode(" ", $name);
    //     // $first_name             =       $name[0];
    //     // $last_name              =       "";

    //     // if(isset($name[1])) {
    //     //     $last_name          =       $name[1];
    //     // }

    //     $userDataArray = array(
    //         "fname" => $request->fname,
    //         "uname" => $request->uname,
    //         "phone" => $request->phone,
    //         "email" => $request->email,
    //         "password" =>$request->password
    //         // "password" => md5($request->password),
    //         // "confirmpassword" => md5($request->confirmpassword)
    //     );

    //     $user_status = User::where("email", $request->email)->first();

    //     if (!is_null($user_status)) {
    //         return response()->json(["status" => "failed", "success" => false, 'error'=>'Whoops! email already registered']);
    //     }

    //     $user = User::create($userDataArray);

    //     if (!is_null($user)) {
    //         return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully", "data" => $user]);
    //     } else {
    //         return response()->json(["status" => "failed", "success" => false,'error'=>'failed to register']);
    //     }

    // }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "fname" => "required",
            "uname" => "required",
            'email' => 'email|required|unique:users',
            "password" => "required",
            // "password" => "required|min:6",
            // "confirmpassword" => "required",
            "phone" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 401, "message" => "validation_error", "errors" => $validator->errors()]);
        }

        $validatedData = $validator->validated();
        $validatedData['password'] = $request->password;

        // $token = $user->createToken('auth_token')->plainTextToken;
        // $token = createToken('auth_token')->plainTextToken;
        // $validatedData['api_token'] = $token;

        $user = User::create($validatedData);


        return response()->json([
            "status" => 200,
            // 'access_token' => $token,
            // 'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
    // function register(Request $request)
    // {
    //     $user = new User;
    //     $user->fname = $request->input('fname');
    //     $user->uname = $request->input('uname');
    //     $user->phone = $request->input('phone');
    //     $user->email = $request->input('email');
    //     $user->password = $request->input('password');
    //     // $user->confirmpassword = $request->input('confirmpassword');
    //     // $user->password = Hash::make($request->input('password'));
    //     // $user->confirmpassword = Hash::make($request->input('confirmpassword'));
    //     $user->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User created successfully!',
    //         'data' => $user
    //     ], 201);
    // }
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        // echo ;;$validatedData['email'];

        if (Auth::attempt($validatedData)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (auth()->check()) {
            echo "Checked";

            $user = User::find(1);

            // echo auth()->user();


            $token = Auth::user()->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => Auth::user()
            ]);
        } else {
            return response()->json([
                "Not verified"
            ]);

        }
    }
    // function login(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || $request->password !== $user->password) {
    //         return response()->json(['success'=>false,'error'=>'Incorrect Email or Password'],401);
    //     // if (!$user || !Hash::check($request->password, $user->password)) {
    //     //     return response()->json(['success'=>false,'error'=>'Incorrect Email or Password'],401);
    //         // return response("Incorrect Email or Password");
    //     } else {
    //         // return response()->json(['success'=>true,'error'=>'Correct Email or Password'],200);
    //          return $user;
    //     }

    // }




    // public function login(Request $request) {

    //     $validator          =       Validator::make($request->all(),
    //         [
    //             "email"             =>          "required|email",
    //             "password"          =>          "required"
    //         ]
    //     );

    //     if($validator->fails()) {
    //         return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
    //     }


    //     // check if entered email exists in db
    //     $email_status       =       User::where("email", $request->email)->first();


    //     // if email exists then we will check password for the same email

    //     if(!is_null($email_status)) {
    //         $password_status    =   User::where("email", $request->email)->where("password", md5($request->password))->first();

    //         // if password is correct
    //         if(!is_null($password_status)) {
    //             $user           =       $this->userDetail($request->email);

    //             return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $user]);
    //         }

    //         else {
    //             return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Incorrect password."]);
    //         }
    //     }

    //     else {
    //         return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Email doesn't exist."]);
    //     }
    // }


    public function updateuser(Request $request, $id)
    {
        // return $id;
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'User not found'], 404);
        }
        $user->fname = $request->input('fname');
        $user->uname = $request->input('uname');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        // $user->password = Hash::make($request->input('password'));
        $user->save();
        $data = array_filter($request->all()); // Remove null or empty values
        $user->update($data);
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'data' => $user
        ], 200);
    }

    public function deleteuser(Request $request, $id)
    {

        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'User not found'], 404);
        }
        if ($request->password !== $user->password) {
            return response()->json(['success' => false, 'error' => 'Incorrect password'], 401);
        }
        // if (!Hash::check($request->password, $user->password)) {
        //     return response()->json(['success'=>false,'error'=>'Incorrect password'], 401);
        // }
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ], 200);
    }

    public function notify()
    {
        $user = User::first();
        auth()->user()->notify(new ClaimNotification($user));
        dd("done");
    }
}
