<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResources;
use App\Mail\Forgotpassword;
use App\Mail\Welcomeemail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
    //

    public function login(Request $request):LoginResources
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
          return  new LoginResources([$validator->errors()]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            $authUser['token'] = $authUser->createToken('MyAuthApp')->plainTextToken;

            //return response()->json(['status' => 'success', 'message' => 'user logged in', 'data' => $authUser], 200);
            $resource = new LoginResources($authUser);
            return $resource;
        } else {
            $resource = new LoginResources(['status' => 'error', 'message' => 'wrong details', 'data' => $request->email]);
            return $resource;
        }
    }
    public function register(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|unique:users',
            'password' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        $user = new User();
        $user->email = $request->input('email');
        //$user->isactive = 1;
        $password = $request->input('password');
        $user->name = ucwords($request->input('firstname')) . " " . ucwords($request->input('lastname'));
        $password = $request->input('password');
        $encriptedPassword = bcrypt($password);
        $user->password = $encriptedPassword;
        // $user->passwordresetcode = substr(str_shuffle("01234567893ABCDEFGHIJKLMN01234567893ABCDEFGHIJKLMN"), -10);
        // $user->emailresetcode = substr(str_shuffle("01234567893ABCDEFGHIJKLMN01234567893ABCDEFGHIJKLMN"), -10);
        // $user->reverse = strrev($request->input('password'));

        if ($user->save()) {

            $token = $user->createToken('MyAuthApp')->plainTextToken;

            $user->reverse = time();
            // note change the sending ofemailto become a queue
            try {
                Mail::to($user)->send(new Welcomeemail($user));
            } catch (\Exception $e) {
                throw new $e($e->getMessage());
                //$error = $e->getMessage();
            }

            return response()->json(['status' => 'success', 'message' => "user created successfully", 'data' => $user], 201);
        } else {
            return response()->json(['status' => 'error', 'message' => 'cannot create user', 'data' => $user], 400);
        }

    }

    public function confirmemail($code)
    {
        $link = \Config::get('constants.frontend');
        $user = new User();
        $code = substr($code, -10);
        $user = $user::where('email', $code)->first();

        if ($user != null) {
            $time = new \DateTime("Africa/Lagos");
            $user->email_verified_at = $time->format("Y-m-d h:m:s");
            $user->save();
            $link = \Config::get('constants.frontend');
            return response()->json(['status' => 'success', 'message' => "Email successfully verified", 'data' => $user], 200);
        } else {
            return response()->json(['status' => 'success', 'message' => "We could notverify your email at this time, please retry the entire process", 'data' => $user], 400);
        }
    }

    public function sendpasswordresetlink(Request $request)
    {
        $time = new \DateTime("Africa/Lagos");
        $request->validate([
            "email" => "required",
        ]);
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (empty($user)) {
            return response()->json(["status" => "error", "message" => "The email address you entered does not exist.", "data" => ''], 400);
        } else {
            $codex = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), -3);
            $user->passwordresetcode = $codex . str_shuffle('1234567');

            $user->save();
            $data = array(
                'firstname' => $user->name,

                'link' => \Config::get('constants.frontend') . '/recoverpassword/' . time() . $user->passwordresetcode,
            );

            try {

                Mail::to($email)->send(new Forgotpassword($data));
            } catch (\Exception $e) {
                throw $e("Email not sent");
            }
            return response()->json(['status' => 'success', 'message' => 'Please check your email for further instruction', 'data' => $data], 200);

        }
    }

    public function resetpassword(Request $request)
    {
        $validator = $request->validate([
            'password' => 'required',
            'resetcode' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
        }
        $password = $request->input('password');
        $code = $request->input('resetcode');
        $code = substr($code, -10);
        $user = User::where('passwordresetcode', $code)->first();

        if ($user == null) {
            return response()->json(["status" => "error", "message" => "code does not exist or expired", "data" => ''], 400);
        } else {
            $user->passwordresetcode = time();
            $user->password = bcrypt($password);
            $time = new \DateTime("Africa/Lagos");
            $user->email_verified_at = $time->format("Y-m-d h:m:s");
            $user->reverse = strrev($request->input('password'));
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'password changed successfully', 'data' => $user], 200);
        }
    }
}
