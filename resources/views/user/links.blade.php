@extends('layouts.master')

@section('content')
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
                        <col style="width: 30%;"/>
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
                        @each('_link_row', $links, 'link', 'raw|<tr><th colspan="8">No information available</th></tr>')
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

            $('[data-toggle="lightbox"]').click(function (e) {
                e.preventDefault();
                $(this).ekkoLightbox();
            });
        });
    </script>
@stop
