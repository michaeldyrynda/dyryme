<tr>
    <td>{{ link_to($link->hash, $link->hash) }}</td>
    <td>{{ link_to($link->url, $link->url) }}</td>
    <td>{{ $link->created_at }}</td>
    <td>{{ link_to_route('link.hits', $link->count, [ $link->id, ]) }}</td>
</tr>
