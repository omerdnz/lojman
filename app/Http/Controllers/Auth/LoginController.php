<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('panel.index');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $username = $request->validated('username');
        $password = $request->validated('password');

        $user = User::query()
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (! $user || ! $user->is_active || ! Auth::getProvider()->validateCredentials($user, ['password' => $password])) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Hatalı kullanıcı adı veya şifre.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $user->update(['last_login_at' => now()]);

        return redirect()->intended(route('panel.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
