@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-8">
            <h1>{{ __('Hello, '. auth()->user()->name . '!') }}</h1>
            
            <role-component></role-component>

            <user-component></user-component>
            {{-- <div class="card">
                <div class="card-header">{{ __('Manage Roles') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
