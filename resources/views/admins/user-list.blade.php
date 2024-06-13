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
        </tr>
        </tr>
      </thead>
      <tbody>
        @if($users)
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
    @else
    <tr>
      <td>No users found.</td>
    </tr>
  @endif
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
  $('.delete-button').on('click', function (e) {
    e.stopPropagation();
    var button = $(this);
    var url = button.data('url');
    var id = $(this).data('id');

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
          method: 'POST',
          data: {
            '_method': 'DELETE',
            '_token': '{{ csrf_token() }}' 
          },
          success: function (response) {
            // Elimina la fila de la tabla
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
            Swal.fire('Error', 'The user could not be deleted.', 'error');
          }
        });
      }
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    // Comprueba si la página se ha cargado después de un inicio de sesión
    if (sessionStorage.getItem('justLoggedIn') === 'true') {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Welcome!",
        showConfirmButton: false,
        timer: 1500,
        padding: "3em",
        color: "#716add",
        backdrop: `
                  rgba(0,0,0,0.4)
                `
      });
      sessionStorage.removeItem('justLoggedIn');
    }
  });
</script>
@stop