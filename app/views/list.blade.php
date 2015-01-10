@extends('layouts.master')

@section('content')
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Stored Links</h2>
            </div>

            <table class="table table-striped table-small">
                <thead>
                <tr>
                    <th>Hash</th>
                    <th>URL</th>
                    <th>Created</th>
                    <th>IP Address</th>
                    <th>Hostname</th>
                    <th>User Agent</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                @each('_link_row', $links, 'link', 'raw|<tr><th colspan="7">No links in the database</th></tr>')
                </tbody>
            </table>

            {{ $links->links() }}
        </div>
    </div>
