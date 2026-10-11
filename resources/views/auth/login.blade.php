@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="mb-6 text-center">
        <img src="{{ asset('img/logo.png') }}" alt="Logo SiLaundry" class="mx-auto mb-3 h-16 w-16 object-contain">
        <h1 class="text-xl font-bold text-slate-900">{{ config('app.name') }}</h1>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-100">
        <form method="POST" action="{{ route('login.process') }}" class="space-y-5">
            @csrf

            <x-form.field name="email" label="Email" stacked>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       autofocus autocomplete="username"
                       @class(['form-input', 'ring-red-400' => $errors->has('email')])
                       placeholder="mis. admin@silaundry.test">
            </x-form.field>

            <x-form.field name="password" label="Kata Sandi" stacked>
                <input id="password" name="password" type="password" autocomplete="current-password"
                       @class(['form-input', 'ring-red-400' => $errors->has('password')])
                       placeholder="••••••••">
            </x-form.field>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                       class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Ingat saya
            </label>

            <button type="submit" class="btn btn-primary w-full">Masuk</button>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </p>
@endsection
