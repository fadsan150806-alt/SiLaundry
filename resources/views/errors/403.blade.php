@extends('layouts.guest')

@section('title', 'Akses Ditolak')

@section('content')
    @include('errors._message', [
        'code' => 403,
        'heading' => 'Akses Ditolak',
        'icon' => 'shield-alert',
    ])
@endsection
