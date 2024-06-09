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
        @foreach($users as $id => $user)
      <tr>
        <i class="fas fa-house"></i>
        <td>{{ $user['id'] }}</td>
        <td>{{ $user['username'] }}</td>
        <td>{{ $user['email'] }}</td>
        <td>{{ $user['mainFuel'] }}</td>
        <td>
        <a href="{{ route('admins.user-edit', ['user' => $id]) }}"
          class="btn btn-success btn-sm btn-rounded">Edit</a>
        <a href="{{ route('admins.user-destroy', ['user' => $id]) }}" class="btn btn-danger btn-sm btn-rounded"
          onclick="return confirm('Are you sure?')">Delete</a>
        </td>
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
  $(document).ready(function () {
    console.log("sessionStorage value:", sessionStorage.getItem('justLoggedIn'));
    if (sessionStorage.getItem('justLoggedIn') === 'true') {
      console.log("Showing Swal");
      Swal.fire({
        width: 300,
        padding: "3em",
        color: "#716add",
        position: "top-end",
        icon: "success",
        title: "Correctly logged in!",
        showConfirmButton: false,
        timer: 1500,
        backdrop: `
          rgba(145,145,145,0.4)
        `
      });
      sessionStorage.removeItem('justLoggedIn');
    }
  });
</script>
@stop