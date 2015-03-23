<div class="alert alert-{{ $alert_type }}">
    @foreach ($alert_messages->all() as $alert_message)
        {{ $alert_message }}
    @endforeach
</div>
