@extends('layouts.master')

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Stored Links ({{ $links->count() }})</h2>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-condensed">
                    <col style="width: 5%;"/>
                    <col style="width: 30%;"/>
                    <col style="width: 10%;"/>
                    <col style="width: 10%;"/>
                    <col style="width: 40%;"/>
                    <col style="width: 5%;"/>
                    <thead>
                    <tr>
                        <th>Hash</th>
                        <th>URL</th>
                        <th>Created</th>
                        <th>
                            IP Address<br/>
                            Hostname
                        </th>
                        <th>User Agent</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @each('_link_row', $links, 'link', 'raw|<tr><th colspan="6">No links in the database</th></tr>')
                    </tbody>
                </table>
            </div>

        </div>

        {{ $links->links() }}
    </div>

@stop

@section('foot_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
