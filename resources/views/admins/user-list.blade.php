@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Usuarios</h1>
@stop

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Users list</h3>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Main Fuel</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
      <tr>
        <td>{{ $user['id'] }}</td>
        <td>{{ $user['username'] }}</td>
        <td>{{ $user['email'] }}</td>
        <td>{{ $user['mainFuel'] }}</td>
        <td>
        <a href="{{ route('admins.user-edit', ['user' => $user['id']]) }}"
          class="btn btn-success btn-sm btn-rounded">Edit</a>
        <button class="btn btn-danger btn-sm btn-rounded delete-button" data-id="{{ $user['id'] }}"
          data-url="{{ route('admins.user-destroy', ['user' => $user['id']]) }}">Delete</button>
        </td>
      </tr>
    @endforeach
        <form id="delete-form" action="" method="POST" style="display: none;">
          @csrf
          @method('DELETE')
        </form>
      </tbody>
    </table>
  </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $('.delete-button').on('click', function () {
    var userId = $(this).data('id');
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