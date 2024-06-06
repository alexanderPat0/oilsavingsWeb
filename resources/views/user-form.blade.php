@extends('adminlte::page')

@section('title', $id ? 'Edit User' : 'Add User')

@section('content_header')
    <h1>{{ $id ? 'Edit User' : 'Add User' }}</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ $id ? route('users.update', ['user' => $id]) : route('users.store') }}" method="POST">
        @csrf
        @if($id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" value="{{ $id ? $user['username'] : '' }}" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $id ? $user['email'] : '' }}" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <!-- Main Fuel -->
                <div class="form-group">
                    <label for="mainFuel">Main Fuel:</label>
                    <select name="mainFuel" id="mainFuel" class="form-control">
                        <option value="Sin Plomo 95" {{ $id && $user['mainFuel'] == 'Sin Plomo 95' ? 'selected' : '' }}>Sin Plomo 95</option>
                        <option value="Sin Plomo 98" {{ $id && $user['mainFuel'] == 'Sin Plomo 98' ? 'selected' : '' }}>Sin Plomo 98</option>
                        <option value="Diesel" {{ $id && $user['mainFuel'] == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Diesel+" {{ $id && $user['mainFuel'] == 'Diesel+' ? 'selected' : '' }}>Diesel+</option>
                    </select>
                </div>

                <!-- Submit and Back Buttons -->
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('users.index') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
        </div>
    </form>
</div>
@stop
