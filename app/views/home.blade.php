@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h2 class="text-center">dyry.me link shortener</h2>
            {{ Form::open(array( 'route' => 'store', 'method' => 'post', )) }}
            <div class="form-group @if ( $errors->has('url') )has-error @endif">
                <div class="input-group input-group-lg">
                    {{ Form::text('longUrl', null, array( 'class' => 'form-control input-lg', 'id' => 'url', 'placeholder' => 'Enter URL to shorten', 'required' => 'required', 'aria-describedby' => 'urlErrorStatus', )) }}
                    <span class="input-group-btn">
                        {{ Form::button('Submit', array( 'class' => 'btn btn-lg btn-default', 'type' => 'submit', )) }}
                    </span>
                </div>

                @if ( $errors->has('url') )
                    {{ $errors->first('url', '<span class="help-block">:message</span>') }}
                @endif
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
