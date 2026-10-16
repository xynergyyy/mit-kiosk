@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-slate-50">
        <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-xl border border-slate-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4 text-white shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-4xl">admin_panel_settings</span>
                </div>
                <h1 class="text-3xl font-extrabold font-manrope text-slate-800 mb-1">MIT Maps Admin</h1>
                <p class="text-slate-500">Sign in to manage your campus sanctuary</p>
            </div>

            <div class="mb-6">
                <button type="button" onclick="const enabled = window.toggleOSK(); this.querySelector('.status').textContent = enabled ? 'ON' : 'OFF'; this.classList.toggle('bg-blue-100', enabled); this.classList.toggle('text-blue-700', enabled); this.classList.toggle('bg-slate-100', !enabled); this.classList.toggle('text-slate-500', !enabled);" 
                    class="w-full py-3 px-4 bg-slate-100 text-slate-500 rounded-2xl font-bold text-xs flex items-center justify-center gap-2 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-lg">keyboard</span>
                    VIRTUAL KEYBOARD: <span class="status">OFF</span>
                </button>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Administrator Email</label>
                    <input type="email" name="email" data-osk="1" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 focus:ring-2 focus:ring-primary/20 transition-all font-medium" placeholder="admin@mit-maps.com">
                    @error('email') <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Master Password</label>
                    <input type="password" name="password" data-osk="1" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 focus:ring-2 focus:ring-primary/20 transition-all font-medium" placeholder="••••••••">
                    @error('password') <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                @error('otp')
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-2xl font-bold text-center" role="alert">
                        {{ $message }}
                    </div>
                @enderror

                <button type="submit" class="w-full py-5 bg-gradient-to-r from-primary to-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Send OTP Verification
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('visitor.kiosk') }}" class="text-sm font-bold text-slate-400 hover:text-primary transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Return to Visitor Guide
                </a>
            </div>
        </div>
    </div>

    @include('partials.osk')
@endsection
