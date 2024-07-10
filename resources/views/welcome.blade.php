@extends('layouts.login')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="jumbotron text-center">
                <h1>Dashboard</h1>
                <a href="{{ url('/login') }}" class="btn btn-primary">Login</a>
                <a href="{{ url('/register') }}" class="btn btn-success">Register</a>
            </div>
        </div>
    </div>
</div>
@endsection