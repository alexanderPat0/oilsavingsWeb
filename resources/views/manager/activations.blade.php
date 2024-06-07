@extends('adminlte::page')

@section('title', 'Pending Verification')

@section('content_header')
    <h1>Pending Activation</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <ul class="list-group">
            @forelse ($pendings as $id => $pending)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $pending['email'] }}
                    <button class="btn btn-primary btn-sm" onclick="sendActivation('{{ $id }}')">Send Now</button>
                </li>
            @empty
                <li class="list-group-item">No pending verifications.</li>
            @endforelse
        </ul>
    </div>
</div>
<script>
function sendActivation(id) {
    fetch(`/manager/activations/send/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id })
    })
    .then(response => response.json())
    .then(data => {
        alert('Verification link sent!');
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send the link.');
    });
}
</script>
@stop
