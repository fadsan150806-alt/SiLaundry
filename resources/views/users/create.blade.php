@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <x-ui.page-header title="Tambah Pengguna" />

    <x-ui.card class="max-w-3xl">
        <form method="POST" action="{{ route('users.store') }}" novalidate>
            @csrf
            @include('users._form')
        </form>
    </x-ui.card>
@endsection
