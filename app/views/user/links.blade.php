@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Stored Links ({{ $links->getTotal() }})</h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <col style="width: 5%;"/>
                        <col style="width: 15%;"/>
                        <col style="width: 20%;"/>
                        <col style="width: 10%;"/>
                        <col style="width: 10%;"/>
                        <col style="width: 35%;"/>
                        <col style="width: 5%;"/>
                        <thead>
                        <tr>
                            <th>Hash</th>
                            <th>URL</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>
                                IP Address<br/>
                                Hostname
                            </th>
                            <th>User Agent</th>
                        </tr>
                        </thead>
                        <tbody>
                        @each('_link_row', $links, 'link', 'raw|<tr><th colspan="7">No information available</th></tr>')
                        </tbody>
                    </table>
                </div>

            </div>

            {{ $links->links() }}
        </div>
    </div>
@stop
