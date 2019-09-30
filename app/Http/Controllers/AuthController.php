<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use App\MaUser;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'username' => 'required|unique:ma_users',
            'password'  => 'required|min:3|confirmed',
            'role' => 'in:root,admin,user',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }

        $user = new MaUser;
        $user->id =  Uuid::uuid1()->toString();
        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->status = 'AKTIF';
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->username, 
            'password'=> $request->password, 
            'status' => 'AKTIF',
            //'role' => 'admin'
        ];

        if ($token = $this->guard()->attempt($credentials)) {
            $user = MaUser::find(Auth::user()->id);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => ['token' => $token, 'user' => $user]
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'login_failed',
            'data' => null
        ], 401);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => null
        ], 200);
    }

    public function user (Request $request)
    {
        $user = MaUser::find(Auth::user()->id);

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()->json([
                'status' => true,
                'message' => 'success', 
                'data' => $token
            ], 200);
        }
        
        return response()->json([
            'status' => false, 
            'message' => 'refresh_token_error',
            'data' => null
        ], 401);
    }

    public function changePassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'password_old' => 'required',
            'password'  => 'required|min:3|confirmed',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }

        $user = MaUser::find(Auth::user()->id);
        $user->password = bcrypt($request->password);
        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $user
        ], 200);
    }

    private function guard()
    {
        return Auth::guard();
    }
}
