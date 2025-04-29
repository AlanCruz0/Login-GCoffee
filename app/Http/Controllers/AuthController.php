<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Str;
use App\Models\VerifyCode;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $verificationCode = Str::random(6);
        VerifyCode::create( [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode,
        ]);
    
        Mail::to($request->email)->send(new VerificationCodeMail($verificationCode));
    
        return redirect()->route('login');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if (!User::whereExists($request->email)) //Verificar si no existe en user y en verifyCode
        {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden.',
            ])->onlyInput('email');
        }

        // Compruebo correo y contraseña
        // Generar codigo
        // Enviar correo 
        // Mando al formulario del codigo

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showVerifyForm()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric|digits:6', // Aquí suponemos que el código es de 6 dígitos
        ]);

        $user = Auth::user();
        
        // Suponiendo que el código de verificación se guarda en el usuario.
        if ($request->verification_code == $user->verification_code) {
            $user->markEmailAsVerified();
            event(new Verified($user));

            return redirect()->route('dashboard')->with('success', 'Correo electrónico verificado con éxito.');
        }

        return back()->withErrors(['verification_code' => 'El código de verificación es incorrecto.']);
    }

    public function resendVerificationEmail()
    {
        $user = Auth::user();
        
        // Enviar el correo de verificación de nuevo
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', 'El correo de verificación ha sido reenviado.');
    }
}
