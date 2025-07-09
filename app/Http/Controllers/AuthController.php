<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * @return View
     */
    public function registrationForm(): View
    {
        return view('auth.registration');
    }

    public function registration(RegistrationRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());
        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function loginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $role = User::find(Auth::user()->id)->role;

            if ($role->title === 'master') {
                return redirect()->intended('profile/' . Auth::user()->id)->with('success', 'Привет, ' . Auth::user()->name . '!');
            }

            return redirect()->intended('/', )->with('success', 'Привет, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Регистрационные данные не верны.',
        ])->onlyInput('email');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('index');
    }

    public function passwordRequest(): View
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Проверьте электронную почту'])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function passwordUpdate(PasswordUpdateRequest $request): RedirectResponse
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Пароль обновлен успешно.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
