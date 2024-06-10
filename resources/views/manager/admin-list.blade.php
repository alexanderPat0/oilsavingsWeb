@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Admins</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Administrators list</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Es Super Admin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $id => $admin)
                    <tr>
                        <td>{{ $id }}</td>
                        <td>{{ $admin['name'] }}</td>
                        <td>{{ $admin['email'] }}</td>
                        <td>{{ $admin['is_super_admin'] ? 'Sí' : 'No' }}</td>
                        <td>
                            <a href="{{ route('manager.admin-edit', ['admin' => $admin['adminId']]) }}"
                                class="btn btn-success btn-sm btn-rounded">Edit</a>
                            <button class="btn btn-danger btn-sm btn-rounded delete-button" data-id="{{ $admin['adminId'] }}"
                                data-url="{{ route('manager.admin-destroy', ['admin' => $admin['adminId']]) }}">Delete</button>
                        </td>
                    </tr>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
    $('.delete-button').on('click', function () {
        var id = $(this).data('id');
        var url = $(this).data('url');  // No necesitas construir la URL aquí, ya está predefinida

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
                $('#delete-form').attr('action', url).submit();
            }
        });
    });
</script>
@stop