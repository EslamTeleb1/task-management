<?php
namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $req)
    {

        $credentials = $req->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'invalid credentials'], 401);
        }
        $user = Auth::user();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }
    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'logged out']);
    }
}
