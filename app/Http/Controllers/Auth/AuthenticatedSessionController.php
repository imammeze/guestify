<?php

namespace App\Http\Controllers\Auth;

use Log;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
       $nip = $request->input('nip');
        $defaultPassword = env('DEFAULT_LOGIN_PASSWORD', 'password');

        $user = User::where('nip', $nip)->first();
        
        if (!$user) {
            return back()->withErrors(['nip' => 'NIP tidak ditemukan'])->onlyInput('nip');
        }
        
        if (!Hash::check($defaultPassword, $user->password)) {
            $user->password = Hash::make($defaultPassword);
            $user->save();
        }

        if (Auth::attempt(['nip' => $nip, 'password' => $defaultPassword])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['nip' => trans('auth.failed')])->onlyInput('nip');
    }


    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}