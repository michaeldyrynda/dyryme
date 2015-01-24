@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h2 class="text-center">dyry.me link shortener</h2>
            {{ Form::open(array( 'route' => 'store', 'method' => 'post', )) }}
            @if ( ! $errors->isEmpty() )
                @include('_alert', [ 'alert_type' => 'danger', 'alert_messages' => $errors, ])
            @endif
            <div class="form-group @if ( $errors->has('url') )has-error has-feedback @endif">
                {{ Form::text('longUrl', null, array( 'class' => 'form-control input-lg', 'id' => 'url', 'placeholder' => 'Enter URL to shorten', 'required' => 'required', 'aria-describedby' => 'urlErrorStatus', )) }}
                @if ( $errors->has('url') )
                    <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
                    <span id="urlErrorStatus" class="sr-only">(error)</span>
                @endif
            </div>

            <div class="controls">
                {{ Form::button('Submit', array( 'class' => 'btn btn-default', 'type' => 'Submit', )) }}
            </div>
            {{ Form::close() }}

            @if ( Session::has('hash') )
                <output>{{ link_to(Session::get('hash')) }}</output>
            @endif
        </div>
    </div>
@stop

@section('foot_scripts')
    <script type="text/javascript">
        document.getElementById('url').focus();
    </script>
@stop
