<tr>
    <td>{{ $hit->created_at }}</td>
    <td>
        {{ $hit->remoteAddress or '<span class="text-muted">&ndash;</span>' }}<br >
        @if ( $hit->hostname )<span data-toggle="tooltip" title="{{ $hit->hostname }}">{{ str_limit($hit->hostname, 50) }}</span>@else<span class="text-muted">&ndash;</span>@endif
    </td>
    <td>@if ( $hit->userAgent)<span data-toggle="tooltip" title="{{ $hit->userAgent }}">{{ str_limit($hit->userAgent) }}</span>@else<span class="text-muted">&ndash;</span>@endif</td>
    <td>@if ( $hit->referer)<span data-toggle="tooltip" title="{{ $hit->referer }}">{{ str_limit($hit->referer) }}</span>@else<span class="text-muted">&ndash;</span>@endif</td>
</tr>
