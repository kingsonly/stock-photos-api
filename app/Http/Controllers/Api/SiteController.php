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

/**
 * @group Site management
 *
 * APIs for managing basic site requirments such as login, logout, registration etc
 */
class SiteController extends Controller
{
    /**
     * @bodyParam email string required The email of the user. Example: kingsonly13c@gmail.com
     * @bodyParam password string required The password of the user. Example: firstoctober
     * This route is responsible for enabling a user to login into the system
     * @response {
     *  "data": {
     *      "name": "Prof. Morris Boehm",
     *      "token": "3|XyZ0nQXDCq4ZN8Z81ILGSvJMTRDDtGDMAXeWGip4",
     *      "email": "damion.mante@example.com",
     *      "status": "success",
     *      "message": "user logged in"
     *     }
     * }
     */
    public function login(Request $request): LoginResources
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return  new LoginResources([$validator->errors()]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            $authUser['token'] = $authUser->createToken('MyAuthApp')->plainTextToken;

            //return response()->json(['status' => 'success', 'message' => 'user logged in', 'data' => $authUser], 200);
            $resource = new LoginResources($authUser);
            return $resource;
        } else {
            $resource = new LoginResources(['status' => 'error', 'message' => 'wrong details', 'data' => Auth::attempt(['email' => $request->email, 'password' => $request->password])]);
            return $resource;
        }
    }

    /**
     * @bodyParam email string required The email of the user. Example: kingsonly13c@gmail.com
     * @bodyParam password string required The password of the user. Example: firstoctober
     * @bodyParam firstname string required The firstname of the user. Example: kingsley
     * @bodyParam lastname string required The lastname of the user. Example: Achumie
     * This route is responsible for enabling a user to register and create an account on the system
     * @response {
     *  "status": "success",
     *  "message": "user created successfully",
     *  "data": {
     *      "email": "kingsonly13c@gmail.com",
     *      "name": "Kings Kings",
     *      "updated_at": "2023-04-14T15:28:27.000000Z",
     *      "created_at": "2023-04-14T15:28:27.000000Z",
     *      "id": 16,
     *      "reverse": 1681486107
     *    }
     * }
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'password' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'success', 'message' => "ensure that all required filed are properly filled "], 400);
        }

        $user = new User();
        $time = new \DateTime("Africa/Lagos");
        $user->email = $request->input('email');
        //$user->isactive = 1;
        $password = $request->input('password');
        $user->name = ucwords($request->input('firstname')) . " " . ucwords($request->input('lastname'));
        $password = $request->input('password');
        $encriptedPassword = bcrypt($password);
        $user->password = $encriptedPassword;

        if ($user->save()) {

            $token = $user->createToken('MyAuthApp')->plainTextToken;

            // note change the sending of email to become a queue
            try {
                //$user->link = time().str_shuffle("01234567893ABCDEFGHIJKLMN01234567893ABCDEFGHIJKLMN").$user->emailresetcode;
                Mail::to($user)->send(new Welcomeemail($user));
            } catch (\Exception $e) {
                //throw new $e($e->getMessage());
                //$error = $e->getMessage();
                return response()->json(['status' => 'success', 'message' => "user created successfully", 'data' => $user], 201);
            }

            return response()->json(['status' => 'success', 'message' => "user created successfully", 'data' => $user], 201);
        } else {
            return response()->json(['status' => 'error', 'message' => 'cannot create user', 'data' => $user], 400);
        }
    }

    /**
     * @ulrParam code string required The code used to verify  user email. Example:rdtgfytr678
     * This route is responsible for confirming emails after a user registers.
     * @response {
     *  "status": "success",
     *  "message": "Email successfully verified",
     *  "data": {
     *      "id": 11,
     *      "name": "Kings Kings",
     *      "email": "kingsonly13c@gmail.com",
     *      "email_verified_at": "2023-05-06T04:05:33.000000Z",
     *      "passwordresetcode": "ZAB4631572",
     *      "created_at": "2023-04-14T14:49:57.000000Z",
     *      "updated_at": "2023-05-06T15:34:33.000000Z"
     *  }
     * }
     */
    public function confirmemail($code)
    {
        $link = \Config::get('constants.frontend');
        $user = new User();
        $user = $user::where('email', $code)->first();

        if ($user != NULL) {
            $time = new \DateTime("Africa/Lagos");
            $user->email_verified_at = $time->format("Y-m-d h:m:s");
            $user->save();
            //$link = \Config::get('constants.frontend');
            return response()->json(['status' => 'success', 'message' => "Email successfully verified", 'data' => $user], 200);
        } else {
            return response()->json(['status' => 'success', 'message' => "We could notverify your email at this time, please retry the entire process", 'data' => $user], 400);
        }
    }
    
    /**
     * @bodyParam email string required The email used to send password reset link to a user . Example:kings@gmail.com
     * This route is responsible for sending password reset link to a user when the user wants to reset their password
     * @response {
     *  "status": "success",
     *  "message": "Please check your email for further instruction",
     *  "data": {
     *      "firstname": "Gavin Abbott",
     *      "link": "/recoverpassword/1683386125VJZ3625741"
     *  }
     * }
     */
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
                //throw $e("Email not sent");
                return response()->json(['status' => 'error', 'email was not sent', 'data' => $e], 400);
            }
            return response()->json(['status' => 'success', 'message' => 'Please check your email for further instruction', 'data' => $data], 200);
        }
    }

    /**
     * @urlParam id string required This id is used to fetch the user from the database which password needs to be changed.
     * @bodyParam password string required The password which would be saved as the new users password . Example:firstoctober
     * @response {
     *  "status": "success",
     *  "message": "password changed successfully",
     *  "data": {
     *      "id": 1,
     *      "name": "Gavin Abbott",
     *      "email": "noe.wisozk@example.org",
     *      "email_verified_at": "2023-05-06T04:05:23.000000Z",
     *      "passwordresetcode": 1683386423,
     *      "created_at": "2023-04-12T14:05:43.000000Z",
     *      "updated_at": "2023-05-06T15:20:23.000000Z"
     *    }
     * }
     */
    public function resetpassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 200);
        }

        $password = $request->input('password');
        $code = $id;
        $code = substr($code, -10);
        $user = User::where('passwordresetcode', $code)->first();

        if ($user == null) {
            return response()->json(["status" => "error", "message" => "code does not exist or expired", "data" => ''], 400);
        } else {
            $user->passwordresetcode = time();
            $user->password = bcrypt($password);
            $time = new \DateTime("Africa/Lagos");
            $user->email_verified_at = $time->format("Y-m-d h:m:s");
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'password changed successfully', 'data' => $user], 200);
        }
    }
}
