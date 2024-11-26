@extends('main.app')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('resources/css/dashboard.css') }}">
    @if (isset($tabCss))
        <link rel="stylesheet" href="{{ $tabCss }}">
    @endif
@endsection

@section('content')
    <div class="dashboard-wrapper">
        <!-- Permanent sidebar -->
        @include('components.sidebar', ['userType' => auth()->user()->user_type])

        <!-- Dynamic content area -->
        <div class="dashboard-content">
            <!-- Permanent topbar -->
            @include('components.topbar', ['userType' => auth()->user()->user_type])

            @if (isset($tabView) && view()->exists($tabView))
                {{-- Dynamically include the selected tab's content --}}
                @include($tabView)
            @else
                <p>No content available.</p>
            @endif
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('resources/js/script.js') }}"></script>
    <script src="{{ asset('resources/js/notification.js') }}"></script>
    <script src="{{ asset('resources/js/modal.js') }}"></script>
@endsection
