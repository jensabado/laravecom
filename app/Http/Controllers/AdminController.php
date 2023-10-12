<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $validator = Validator::make($request->all(), [
            'login_id' => 'required',
            'password' => 'required|min:5|max:45',
        ], [
            'login_id.required' => 'Email or Username is required',
            'password.required' => 'Password is required',
            'password.min' => 'The password must be at least 5 characters',
            'password.max' => 'The password cannot exceed 45 characters',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        } else {
            $admin = Admin::where($fieldType, $request->input('login_id'))
                ->where('is_deleted', 0)
                ->first();

            if($admin && Hash::check($request->input('password'), $admin->password)) {
                if ($request->has('remember')) {
                    Cookie::queue('laravecom_admin_email', $request->input('email'), time() + (86400 * 30), '/');
                    Cookie::queue('laravecom_admin_password', $request->input('password'), time() + (86400 * 30), '/');
                } else {
                    Cookie::queue('laravecom_admin_email', '');
                    Cookie::queue('laravecom_admin_password', '');
                }

                // Auth::guard('admin')->attempt(['email' => $request->input('login_id'), 'password' => $request->input('password')]);
                auth('admin')->login($admin);
                $message = 'Welcome ' . $admin->name .'!';
                return response()->json(['status' => 'success', 'message' => $message]);
            } else {
                return response()->json(['status' => 'alert', 'message' => 'Username or Email is not exist in system.']);
            }
        }
    }
}
