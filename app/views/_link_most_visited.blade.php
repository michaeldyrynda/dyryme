<tr>
    <td>{{ link_to($link->hash, $link->hash) }} ({{ link_to_route('link.hits', $link->hits->count(), [ $link->id, ]) }})</td>
    <td>{{ link_to($link->url, $link->url) }}</td>
    <td>{{ $link->created_at }}</td>
    <td>{{ $link->hits->count() }}</td>
</tr>
