@extends('layouts.master')

@section('content')
    <div class="col-sm-2 col-sm-offset-5">
        <fieldset>
            <legend>Please Login</legend>

            @if ( Session::has('login_error') )
                <div class="alert alert-danger">
                    {{ Session::get('login_error') }}
                </div>
            @endif

            {!! Form::open([ 'route' => 'authenticate', 'method' => 'post', ]) !!}
                <div class="form-group">
                    {!! Form::text('username', null, [ 'class' => 'form-control', 'id' => 'username', 'placeholder' => 'Email Address', 'type' => 'email', ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::password('password', [ 'class' => 'form-control', 'id' => 'password', 'placeholder' => 'Password', ]) !!}
                </div>
                <div class="controls pull-right">
                    <a href="{{ route('create') }}" class="btn btn-link" title="Cancel">Cancel</a>
                    <button class="btn btn-primary" name="login" type="submit">Login</button>
                </div>
            {!! Form::close() !!}
        </fieldset>
    </div>
@stop
