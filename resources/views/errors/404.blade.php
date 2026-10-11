@extends('layouts.guest')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    @include('errors._message', [
        'code' => 404,
        'heading' => 'Halaman Tidak Ditemukan',
        'icon' => 'triangle-alert',
    ])
@endsection
