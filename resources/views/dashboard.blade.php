@extends('layouts.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/dashboard.css') }}">
@endsection

@section('content')
    <div class="dashboard-wrapper">
        @include('components.sidebar', ['userType' => auth()->user()->user_type])

        <div class="dashboard-content">
            @yield('tab-content') 
        </div>
    </div>
@endsection

@section('tab-content')
    @if (view()->exists($tabView ?? ''))
        @include($tabView)
    @else
        <p>No specific information available to display here.</p>
    @endif
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
@endsection
