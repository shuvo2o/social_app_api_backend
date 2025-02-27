<?php

namespace App\Http\Controllers\API;
use App\Mail\TestMail;
use App\Mail\ForgotPasswordEmail;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => "Account Created Succesfully"
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
    
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Invalid Credentials',
                'error_code' => '401',
            ], 401);
        }
    
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('access_token', ['user']);
    
        return response()->json([
            'message' => 'Login Successful',
            'user' => $user,
            'access_token' => $token->plainTextToken,
        ]);
    }

    public function getProfile(Request $request){
        $user = $request->user();
        return response()->json($user);
    }

    
    public function logout(Request $request){
        $user = $request->user();
        $user ->tokens()->delete();


        return response()->json([
            'message' => "Logout Succesfull"
        ], 200);
    }

    public function testMail(Request $request){

        $data = [
            'name' => 'Alex',
            'body' => 'This is for test purpose'
        ];

        Mail::to('shuvodas340985@gmail.com')->send(new TestMail('test subject',$data));
    }

    public function forgetPasswordRequest(Request $request){
        $request->validate([
            'email' =>'required|email'
        ]);
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                'errors' => ['email'=> ['Account with this email not found']]
            ], 422);
        }
        $code = rand(11111, 99999);

        $user->remember_token = $code;
        $user->save();

        $data= [
            'name' => $user->first_name. ' '.$user->last_name,
            'code' => $code,
        ];
        Mail::to($user->email)->send(new ForgotPasswordEmail('Forget Password Request',$data) );

        return response()->json([
            'message' => 'We have sended code to your email'
        ]);
    }
    public function verifyAndChangePassword(Request $request){
         $request->validate([
            'email' => 'required|email',
            'code' => 'required|integer',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('email', $request->email)->where('remember_token', $request->code)->first();

        if (!$user){
            return response()->json([
                'errors'=> ['code' => ['Invalid OTP']]
            ], 422);
        }
        $user->remember_token = null;
        $user->password = bcrypt($request->password);

        return response()->json([
            'message' => 'Password Succesfully Changed'
        ], 200);
    }


    public function changePassword(Request $request){
        $request->validate([
          'current_password' => 'required|current_password',
          'password' => 'required|confirmed',
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->password) ;
        $user->save();

        return response()->json([
          'message' =>  'password updated successfully'
        ], 422);
      }
    public function updateProfile(Request $request){
        $request->validate([
          'first_name' => 'required',
          'last_name' => 'required',
        ]);

        $user = $request->user();

        if($user->email !== $request->email){
            $request->validate([
                
                    'email' =>'required|unique:users,email',
                
            ]);
            $user->first_name = $request->first_name ?? $user->first_name;
            $user->last_name = $request->last_name ?? $user->last_name;
        }
        $user->save();
        return response()->json([
          'message' =>  'Profile updated successfully',
          'data'=> $user
        ], 422);
      }

}
