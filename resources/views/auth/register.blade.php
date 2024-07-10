@extends('layouts.login')

@section('content')
<div class="m-grid__item m-grid__item--fluid m-login__wrapper">
    <div class="m-login__container">

        <div class="m-login__logo">
            <a href="#">
                <img src="{{url('public/assets/demo/default/media/img/logo/logo.png')}}">
            </a>
        </div>
    
        <div class="m-login__signup">
            <div class="m-login__head">
                <h3 class="m-login__title">Sign Up</h3>
                <div class="m-login__desc">Enter your details to create your account:</div>
            </div>
            {!!Form::open(array('route' => 'register', 'method' => 'POST', 'class' => 'm-login__form m-form'))!!}
                {{ csrf_field() }}
                <div class="form-group m-form__group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                    {!! Form::text("first_name", old('first_name'), array('class' => 'form-control m-input', 'placeholder' =>"First Name" )) !!}
                    @if ($errors->has('first_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group m-form__group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                    {!! Form::text("last_name", old('last_name'), array('class' => 'form-control m-input', 'placeholder' =>"Last Name" )) !!}
                    @if ($errors->has('last_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group m-form__group {{ $errors->has('username') ? ' has-error' : '' }}">
                    {!! Form::text("username", old('username'), array('class' => 'form-control m-input', 'placeholder' =>"Username" )) !!}
                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group m-form__group {{ $errors->has('email') ? ' has-error' : '' }}">
                    {!! Form::email("email", old('email'), array('class' => 'form-control m-input', 'placeholder' =>"Email" )) !!}
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group m-form__group {{ $errors->has('password') ? ' has-error' : '' }}">
                    {!! Form::password("password",  array('class' => 'form-control m-input', 'placeholder' =>"Password" )) !!}
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group m-form__group">
                    {!! Form::password("password_confirmation",  array('class' => 'form-control m-input', 'placeholder' =>"Confirm Password" )) !!}
                </div>
                <div class="m-login__form-action">
                    {!! Form::submit("Sign Up",  array('class' => 'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air')) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>    
</div>

@endsection
