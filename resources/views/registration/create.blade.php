@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h2 class="text-center">Register an account</h2>
            <div class="alert alert-info">Registering for an account allows you to retrieve links you have created and track hits.</div>
            {!! Form::open(array( 'route' => 'register', 'method' => 'post', )) !!}
            <div class="form-group @if ( $errors->has('email_address') ) has-error @endif">
                {!! Form::text('email_address', null, array( 'class' => 'form-control input-lg', 'id' => 'email_address', 'placeholder' => 'Email address', 'required' => 'required', )) !!}
                {!! $errors->first('email_address', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group @if ( $errors->has('password') ) has-error @endif">
                {!! Form::password('password', array( 'class' => 'form-control input-lg', 'id' => 'password', 'placeholder' => 'Password', )) !!}
                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group @if ( $errors->has('password_confirmation') ) has-error @endif">
                {!! Form::password('password_confirmation', array( 'class' => 'form-control input-lg', 'id' => 'password_confirmation', 'placeholder' => 'Password Confirmation', )) !!}
                {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
            </div>

            <div class="controls">
                {!! Form::button('Register', array( 'class' => 'btn btn-default', 'type' => 'submit', )) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
