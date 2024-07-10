@extends('layouts.admin.login')

@section('content')
    <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
        <div class="m-login__container">
            <div class="m-login__logo">
                <a href="#">
                    <img src="{{ url('public/assets/demo/default/media/img/logo/logo.png') }}">
                </a>
            </div>
            <div class="m-login__signin">
                <div class="m-login__head">
                    <h3 class="m-login__title">Sign In To Admin</h3>

                </div>
                {!!Form::open(array('route' => 'admin.login.submit', 'method' => 'POST', 'class' => 'm-login__form m-form'))!!}
                    {{ csrf_field() }}
                    @if($errors->has('login_error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('login_error') }}
                          </div>
                    @endif
                    <input type="hidden" name='type' value="admin" />
                    <div class="form-group m-form__group {{ $errors->has('account_id') ? ' has-error' : '' }}">
                        {!! Form::text("account_id", old('account_id'), array('class' => 'form-control m-input', 'placeholder' =>"Account Id" )) !!}
                        
                        @if ($errors->has('account_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('account_id') }}</strong>
                            </span>
                        @endif
                    </div>
                    
                    <div class="form-group m-form__group {{ $errors->has('password') ? ' has-error' : '' }}">
                        {!! Form::password("password",  array('class' => 'form-control m-input m-login__form-input--last', 'placeholder' =>"Password" )) !!}

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <?php /*
                    <div class="row m-login__form-sub">
                        <div class="col m--align-left">
                            <label class="m-checkbox m-checkbox--focus">
                                {!! Form::checkbox('remember', old('remember') ) !!} Remember me
                                <span></span>
                            </label>
                        </div>
                        <div class="col m--align-right">
                            <a href="javascript:;" id="m_login_forget_password" class="m-link">Forget Password ?</a>
                        </div>
                    </div>
                    */ ?>
                    <div class="m-login__form-action">
                        {!! Form::submit("Sign In",  array('class' => 'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air')) !!}
                    </div>
                {!! Form::close() !!}
            </div>
            
        </div>
    </div>

@endsection