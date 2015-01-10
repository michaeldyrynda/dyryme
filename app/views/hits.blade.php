@extends('layouts.master')

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Hits for {{ $link->hash }} ({{ $link->url }})</h2>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-condensed">
                    <col style="width: 10%;" />
                    <col style="width: 10%;" />
                    <col style="width: 40%;" />
                    <col style="width: 40%;" />
                    <thead>
                    <tr>
                        <th>Visited</th>
                        <th>
                            IP Address<br/>
                            Hostname
                        </th>
                        <th>User Agent</th>
                        <th>Referer</th>
                    </tr>
                    </thead>
                    <tbody>
                    @each('_hit_row', $hits, 'hit', 'raw|<tr><th colspan="6">No hits in the database</th></tr>')
                    </tbody>
                </table>
            </div>

        </div>

        {{ $hits->links() }}
    </div>

@stop

@section('foot_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
