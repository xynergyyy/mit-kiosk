@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50">
    <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4 text-white shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-4xl">vpn_key</span>
            </div>
            <h1 class="text-3xl font-extrabold font-manrope text-slate-800 mb-1">Verify OTP</h1>
            <p class="text-slate-500">We've sent a 6-digit code to your email</p>
        </div>

        @if (session('status'))
            @php $isWarning = str_contains(session('status'), 'could not be delivered') || str_contains(session('status'), 'failed') || str_contains(session('status'), 'credentials'); @endphp
            <div class="mb-4 text-sm font-bold {{ $isWarning ? 'text-amber-700 bg-amber-50 border border-amber-200' : 'text-emerald-600 bg-emerald-50/60' }} p-4 rounded-xl text-center leading-relaxed">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('auth.otp') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-600 mb-2">6-Digit Verification Code</label>
                <input type="text" name="otp" maxlength="6" required autofocus class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-center text-3xl font-black text-slate-800 tracking-[0.5em] focus:ring-2 focus:ring-primary/20 transition-all font-medium" placeholder="000000">
                @error('otp') <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full py-5 bg-gradient-to-r from-primary to-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                Verify and Log In
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm font-bold text-slate-400">Did not receive the email?</p>
            <form action="{{ route('auth.otp.resend') }}" method="POST">
                @csrf
                <button type="submit" class="mt-2 text-primary font-black hover:underline transition-all active:scale-95">Resend Code</button>
            </form>
        </div>
    </div>
</div>
@endsection
