<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = substr($token, 7); // Loại bỏ tiền tố "Bearer "
        $token = stripslashes($token); // Xử lý escape ký tự nếu cần
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Nếu cần logic thêm (ví dụ: chỉ admin mới được phép logout)
        // if ($user->role !== 'admin') {
        //     return response()->json(['message' => 'Access denied'], 403);
        // }

        // Gắn thông tin user vào request
        $request->merge([
            'user' => $user,
            'token' => $token,
        ]);

        return $next($request);
    }
}
