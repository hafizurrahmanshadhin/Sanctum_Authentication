<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller {
    function UserRegistration(Request $request) {
        try {
            $request->validate([
                'firstName' => 'required|string|max:50',
                'lastName'  => 'required|string|max:50',
                'email'     => 'required|string|email|max:100|unique:users,email',
                'mobile'    => 'required|string|max:25',
                'password'  => 'required|string|max:1000|min:4',
            ]);
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName'  => $request->input('lastName'),
                'email'     => $request->input('email'),
                'mobile'    => $request->input('mobile'),
                'password'  => Hash::make($request->input('password')),
            ]);
            return response()->json([
                'status'  => 'success',
                'message' => 'User Registered successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }

    function UserLogin(Request $request) {
        try {
            $request->validate([
                'email'    => 'required|string|email|max:100',
                'password' => 'required|string|max:1000|min:4',
            ]);

            $user = User::where('email', $request->input('email'))->first();

            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Invalid email or password',
                ]);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status'  => 'success',
                'message' => 'Login in successfully',
                'token'   => $token,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }

    function UserProfile(Request $request) {
        return Auth::user();
    }

    function UserLogout(Request $request) {
        $request->user()->tokens()->delete();
        return redirect('/userLogin');
    }

    function UpdateProfile(Request $request) {
        try {
            $request->validate([
                'firstName' => 'required|string|max:50',
                'lastName'  => 'required|string|max:50',
                'mobile'    => 'required|string|max:25',
            ]);

            User::where('id', '=', Auth::id())->update([
                'firstName' => $request->input('firstName'),
                'lastName'  => $request->input('lastName'),
                'mobile'    => $request->input('mobile'),
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Profile Updated successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }

    function SendOTP(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email|max:100',
            ]);

            $email = $request->input('email');
            $otp   = rand(1000, 9999);
            $count = User::where('email', '=', $email)->count();

            if ($count == 1) {
                Mail::to($email)->send(new OTPMail($otp));
                User::where('email', '=', $email)->update(['otp' => $otp]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'OTP Sent Successfully',
                ]);
            } else {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Invalid Email Address',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }

    function VerifyOTP(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email|max:100',
                'otp'   => 'required|string|max:4|min:4',
            ]);

            $email = $request->input('email');
            $otp   = $request->input('otp');
            $user  = User::where('email', '=', $email)->where('otp', '=', $otp)->first();

            if (!$user) {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Invalid OTP',
                ]);
            }

            User::where('email', '=', $email)->update(['otp' => '0']);
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status'  => 'success',
                'message' => 'OTP Verified Successfully',
                'token'   => $token,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }

    function ResetPassword(Request $request) {
        try {
            $request->validate([
                'password' => 'required|string|max:1000|min:4',
            ]);

            $id       = Auth::id();
            $password = $request->input('password');
            User::where('id', '=', $id)->update(['password' => Hash::make($password)]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Password Reset Successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
