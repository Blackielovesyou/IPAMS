{{-- resources/views/errors/no-access.blade.php --}}

@extends('layouts.app')
@if (auth()->user()->user_type == 0) 
    <script>
        location.href = '/admin';
    </script>
@else 
    <script>
        location.href = '/user/home';
    </script>
@endif
@section('content')
    <div class="alert alert-danger">
        <h1><strong>Unauthorized Access!</strong></h1> You do not have permission to access this module.
    </div>
@endsection
