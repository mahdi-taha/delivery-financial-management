<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function showLogin()
    {
        $info = CompanyInfo::select('logo')->first();
        return view('auth.login', compact('info'));
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ]);
        }
        $request->session()->regenerate();
        $user = Auth::user();
        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'username' => 'Your account is inactive.',
            ]);
        }
        ActivityLogger::log(
            'login',
            $user,
            "{$user->username} logged in",
            [],
            $user->fresh()->toArray()
        );
        return redirect()->route('dashboard');
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        ActivityLogger::log(
            'logout',
            $user,
            "{$user->username} logged out",
            [],
            $user->fresh()->toArray()
        );
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
