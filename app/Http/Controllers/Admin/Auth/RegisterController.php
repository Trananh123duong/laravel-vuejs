<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        // Tạo token tự động cho người dùng sau khi đăng ký
        $token = base64_encode(random_bytes(40));

        // Tạo người dùng
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
        ]);

        // Tạo token tự động cho người dùng sau khi đăng ký
        $token = base64_encode(random_bytes(40)); // Tạo token ngẫu nhiên
        $user->remember_token = $token; // Lưu token vào database
        $user->save();

        // Trả về phản hồi bao gồm token
        return response()->json([
            'message' => 'User registered and logged in successfully',
            'user' => $user,
            'token' => $token, // Trả token về cho client
        ], 201);
    }
}
