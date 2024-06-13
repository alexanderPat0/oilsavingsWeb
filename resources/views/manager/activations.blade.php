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
                    <button class="btn btn-primary btn-sm activate-button" data-id="{{ $id }}" data-url="{{ route('manager.activate', $id) }}">Activate as administrator</button>
                </li>
            @empty
                <li class="list-group-item">No pending activations.</li>
            @endforelse
        </ul>
    </div>
</div>
@stop

@section('css')
@parent
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
@parent
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.activate-button', function (e) {
        e.preventDefault();
        var button = $(this);
        var url = button.data('url');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to activate this account?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, activate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST', // As it's a post route
                    data: {
                        '_token': '{{ csrf_token() }}', // CSRF token is required for POST methods in Laravel
                    },
                    success: function(response) {
                        if (response.success) {
                            var row = button.closest('li'); 
                            row.remove();
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Activated!",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire('Error', 'Activation failed. Please try again.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'Activation failed. Please try again.', 'error');
                    }
                });
            }
        });
    });
</script>
@stop