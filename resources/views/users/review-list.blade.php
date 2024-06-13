@extends('adminlte::page')

@section('title', 'Review Dashboard')

@section('content_header')
<h1>Reviews by Place</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Review List</h3>
    </div>
    <div class="card-body">
        @if (!empty($reviewsByPlace))
            @foreach ($reviewsByPlace as $placeName => $reviews)
                <div class="mb-4">
                    <h4>{{ $placeName }}</h4>
                    <table class="table table-bordered table-hover" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 20%;">User</th>
                                <th style="width: 40%;">Review</th>
                                <th style="width: 10%;">Rating</th>
                                <th style="width: 20%;">Date</th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                <tr class="review-row" data-review="{{ json_encode($review) }}">
                                    <td>{{ $review['username'] }}</td>
                                    <td>{{ $review['review'] }}</td>
                                    <td>{{ $review['rating'] }}</td>
                                    <td>{{ $review['date'] }}</td>
                                    <td>
                                        <!-- <button class="btn btn-danger btn-sm btn-rounded delete-button"
                                                        data-id="{{ $review['placeIduserId'] }}"
                                                        data-url="{{ route('users.review-destroy', ['review' => $review['placeIduserId']]) }}">Create Review</button> -->
                                        <button class="btn btn-danger btn-sm btn-rounded delete-button"
                                            data-id="{{ $review['placeIduserId'] }}"
                                            data-url="{{ route('users.review-destroy', ['review' => $review['placeIduserId']]) }}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                            <form id="create-form" action="" method="POST" style="display: none;">
                                @csrf
                                @method('POST')
                            </form>
                            <form id="delete-form" action="" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </tbody>
                    </table>
                </div>
            @endforeach
        @else
            <div class="alert alert-info" role="alert">
                No reviews available.
            </div>
        @endif
    </div>
</div>
@stop

@section('css')
@parent
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    th,
    td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Evento para mostrar los detalles de la review
    $(document).on('click', '.review-row', function (e) {
        const review = $(this).data('review');
        Swal.fire({
            title: 'Review Details',
            html: `
                <strong>User:</strong> ${review.username}<br>
                <strong>Review:</strong> ${review.review}<br>
                <strong>Rating:</strong> ${review.rating}<br>
                <strong>Date:</strong> ${review.date}
            `,
            icon: 'info'
        });
    });

    $('.delete-button').on('click', function (e) {
        e.stopPropagation();
        var button = $(this);
        var url = button.data('url');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST', // Cambia a DELETE si tu backend lo soporta
                    data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}' // CSRF token
                    },
                    success: function (response) {
                        var row = button.closest('tr');
                        row.remove();
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Deleted!",
                            showConfirmButton: false,
                            timer: 1500,
                            padding: "3em",
                            color: "#716add",
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire('Error', 'The review could not be deleted.', 'error');
                    }
                });
            }
        });
    });
</script>
@stop