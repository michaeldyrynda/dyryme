@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h2>Access Denied</h2>
            <p>You do not have permission to access this resource. Go {!! link_to_route('create', 'home') !!}?</p>
        </div>
    </div>
@stop
