@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')
    <x-ui.page-header title="Ubah Pengguna" />

    <x-ui.card class="max-w-3xl">
        <form method="POST" action="{{ route('users.update', $user) }}" novalidate>
            @csrf
            @method('PUT')
            @include('users._form')
        </form>
    </x-ui.card>
@endsection
