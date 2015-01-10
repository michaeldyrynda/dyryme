<tr @if ( $link->trashed() )class="danger"@endif>
    <td>{{ link_to($link->hash, $link->hash) }} ({{ link_to_route('link.hits', $link->hits->count(), [ $link->id, ]) }})</td>
    <td>{{ link_to($link->url, $link->url) }}</td>
    <td>{{ $link->created_at }}</td>
    <td>
        {{ $link->remoteAddress or '<span class="text-muted">&ndash;</span>' }}<br >
        @if ( $link->hostname )<span data-toggle="tooltip" title="{{ $link->hostname }}">{{ str_limit($link->hostname, 50) }}</span>@else<span class="text-muted">&ndash;</span>@endif
    </td>
    <td>@if ( $link->userAgent)<span data-toggle="tooltip" title="{{ $link->userAgent }}">{{ str_limit($link->userAgent) }}</span>@else<span class="text-muted">&ndash;</span>@endif</td>
    <td>
        @if ( $link->trashed() )
            {{ Form::open([ 'route' => [ 'link.activate', $link->id, ], 'method' => 'put', ]) }}
            <button class="btn btn-xs btn-success" title="Activate link"><span class="glyphicon glyphicon-check"></span></button>
            {{ Form::close() }}
        @else
            {{ Form::open([ 'route' => [ 'link.destroy', $link->id, ], 'method' => 'delete', ]) }}
            <button class="btn btn-xs btn-danger" title="Delete link"><span class="glyphicon glyphicon-trash"></span></button>
            {{ Form::close() }}
        @endif
    </td>
</tr>
