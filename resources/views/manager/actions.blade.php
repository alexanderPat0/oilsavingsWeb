@extends('adminlte::page')

@section('title', 'Action History')

@section('content_header')
    <h1>Action History</h1>
@stop

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Historial de Acciones</h3>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Admin</th>
          <th>Action Type</th>
          <th>Target ID</th>
          <th>Performed At</th>
        </tr>
      </thead>
      <tbody>
        @foreach($actions as $id => $action)
          <tr>
            <td>{{ $id }}</td>
            <td>{{ $admins[$action['admin_id']]['name'] }}</td>
            <td>{{ $action['action_type'] }}</td>
            <td>{{ $action['target_id'] }}</td>
            <td>{{ \Carbon\Carbon::createFromTimestamp($action['performed_at'])->toDateTimeString() }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@stop
