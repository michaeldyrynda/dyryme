@extends('layouts.master')

@section('content')
    <div class="col-md-6 col-md-offset-3">
        <h2 class="text-center">dyry.me link shortener</h2>
        {{ Form::open(array( 'route' => 'store', 'method' => 'post', )) }}
        <div class="form-group">
            {{ Form::text('url', null, array( 'class' => 'form-control', 'id' => 'url', 'placeholder' => 'URL to shorten', )) }}
            {{ Form::button('Shorten!', array( 'class' => 'btn btn-primary hidden', 'type' => 'submit', )) }}
        </div>
        {{ Form::close() }}
    </div>
@stop
