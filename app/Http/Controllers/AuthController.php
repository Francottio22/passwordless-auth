<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'numeric', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $twilio_sid = config('services.twilio.sid');
        $twilio_auth_token = config('services.twilio.auth_token');
        $twilio_verify_sid = config('services.twilio.verify_sid');

        // Depurar variables de entorno
        // dd($twilio_sid, $twilio_auth_token, $twilio_verify_sid);

        $twilio = new Client($twilio_sid, $twilio_auth_token);

        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($data['phone_number'], 'sms');

        User::create([
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('verify')->with(['phone_number' => $data['phone_number']]);
    }

    public function showVerificationForm(Request $request)
    {
        $phone_number = $request->session()->get('phone_number');
        return view('auth.verify', compact('phone_number'));
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);

        $twilio_sid = config('services.twilio.sid');
        $twilio_auth_token = config('services.twilio.auth_token');
        $twilio_verify_sid = config('services.twilio.verify_sid');

        // Depurar variables de entorno
        // dd($twilio_sid, $twilio_auth_token, $twilio_verify_sid);

        $twilio = new Client($twilio_sid, $twilio_auth_token);

        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create(['code' => $data['verification_code'], 'to' => $data['phone_number']]);

        if ($verification->valid) {
            $user = tap(User::where('phone_number', $data['phone_number']))->update(['isVerified' => true]);
            Auth::login($user->first());
            return redirect()->route('Home')->with(['message' => 'Número de teléfono verificado']);
        }

        return back()->with(['phone_number' => $data['phone_number'], 'error' => '¡Código de verificación inválido!']);
    }
}
