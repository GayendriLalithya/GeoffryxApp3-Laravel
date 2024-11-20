@extends('main.app')

@section('title', 'Guest Page')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/style.css') }}">
    @yield('additional-css')
@endsection

@section('content')
    @include('components.header')

    <div class="main-content">
        @yield('guest-content')
    </div>

    @include('components.footer')
@endsection
