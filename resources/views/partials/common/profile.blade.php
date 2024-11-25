@section('additional-css')
    <link rel="stylesheet" href="{{ asset('resources/css/profile.css') }}">
@endsection

<div class="container">
    <div class="accordion" id="accountSettings">
        @include('partials.common.partials.profile-picture')
        @include('partials.common.partials.edit-profile')
        @include('partials.common.partials.edit-password')
        @include('partials.common.partials.prof-acc-request')
        @include('partials.common.partials.delete-acc')
    </div>
</div>