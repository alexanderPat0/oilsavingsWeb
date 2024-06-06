@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Usuarios</h1>
@stop

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Tabla de Usuarios</h3>
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
      @foreach($users as $id => $user)
      <tr>
      <td>{{ $user['id'] }}</td>
      <td>{{ $user['username'] }}</td>
      <td>{{ $user['email'] }}</td>
      <td>{{ $user['mainFuel'] }}</td>
      <td><a href="{{ route('users.edit', $id) }}" class="btn btn-success btn-sm btn-rounded">Edit</a> 
       <a href="{{ route('users.destroy', ['user' => $id]) }}" class="btn btn-danger btn-sm btn-rounded">Delete</a></td>
      </td>

      </tr>
    @endforeach
    @endforeach
      </tbody>
    </table>
  </div>
</div>
@stop