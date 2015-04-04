@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Links per day (last 7 days)</h2>
                </div>
                
                <div class="panel-body" id="dailyLinksChart">
                    @columnchart('DailyLinksChart', 'dailyLinksChart')
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Hits per day (last 7 days)</h2>
                </div>

                <div class="panel-body" id="dailyHitsChart">
                    @columnchart('DailyHitsChart', 'dailyHitsChart')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Most Visited Links</h2>
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
                            <th>Hits</th>
                        </tr>
                        </thead>
                        <tbody>
                        @each('_link_most_visited', $popular, 'link', 'raw|<tr><th colspan="7">No information available</th></tr>')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Most Active Creators</h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Links</th>
                        </tr>
                        </thead>
                        <tbody>
                        @each('_link_most_active', $creators, 'link', 'raw|<tr><th colspan="2">No information available</th></tr>')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Stored Links ({{ $links->total() }})</h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <col style="width: 5%;"/>
                        <col style="width: 5%;"/>
                        <col style="width: 15%;"/>
                        <col style="width: 25%;"/>
                        <col style="width: 10%;"/>
                        <col style="width: 10%;"/>
                        <col style="width: 20%;"/>
                        <col style="width: 5%;"/>
                        <thead>
                        <tr>
                            <th>Thumb</th>
                            <th>Hash</th>
                            <th>URL</th>
                            <th>Page Title</th>
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
                        @each('_link_row', $links, 'link', 'raw|<tr><th colspan="7">No information available</th></tr>')
                        </tbody>
                    </table>
                </div>

            </div>

            {!! $links->render() !!}
        </div>
    </div>
@stop

@section('foot_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
