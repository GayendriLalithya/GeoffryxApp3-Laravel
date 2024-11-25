@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/profile.css') }}">
@endsection

<div class="container">
    <div class="accordion" id="accountSettings">
        @include('pages.common.partials.profile-picture')
        @include('pages.common.partials.edit-profile')
        @include('pages.common.partials.edit-password')

        @if (auth()->user()->user_type === 'customer')
            @include('pages.common.partials.prof-acc-request', ['userType' => auth()->user()->user_type])
        @endif
        
        @include('pages.common.partials.delete-acc')
    </div>
</div>