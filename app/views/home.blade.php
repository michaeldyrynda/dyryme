@extends('layouts.master')

@section('content')
    <div class="col-md-6 col-md-offset-3">
        <h2 class="text-center">dyry.me link shortener</h2>
        {{ Form::open(array( 'route' => 'store', 'method' => 'post', )) }}
        <div class="form-group @if ( $errors->has('url') ) has-error @endif">
            {{ Form::text('url', null, array( 'class' => 'form-control', 'id' => 'url', 'placeholder' => 'URL to shorten', )) }}
            {{ $errors->first('url', '<span class="help-block">:message</span>') }}
        </div>
        {{ Form::close() }}

        @if ( Session::has('hash') )
            <output>{{ link_to(Session::get('hash')) }}</output>
        @endif
    </div>
@stop
