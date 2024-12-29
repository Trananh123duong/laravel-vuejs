<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Kiểm tra email và mật khẩu
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Tạo token thủ công (ví dụ: JWT hoặc chuỗi token đơn giản)
        $token = base64_encode(random_bytes(40)); // Tạo token ngẫu nhiên
        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->get('token');

        $user = User::where('remember_token', $token)->first();

        if ($user) {
            $user->remember_token = null; // Xóa token
            $user->save();
        }

        return response()->json(['message' => 'Logout successful']);
    }
}
