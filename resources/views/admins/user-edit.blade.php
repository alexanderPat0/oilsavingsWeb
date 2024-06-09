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
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" value="{{ $id ? $user['username'] : '' }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $id ? $user['email'] : '' }}" required>
                </div>

                @if(!$id)
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                @endif

                <div class="form-group">
                    <label for="mainFuel">Main Fuel:</label>
                    <select name="mainFuel" id="mainFuel" class="form-control">
                        <option value="Sin Plomo 95" {{ $id && $user['mainFuel'] == 'Sin Plomo 95' ? 'selected' : '' }}>Sin Plomo 95</option>
                        <option value="Sin Plomo 98" {{ $id && $user['mainFuel'] == 'Sin Plomo 98' ? 'selected' : '' }}>Sin Plomo 98</option>
                        <option value="Diesel" {{ $id && $user['mainFuel'] == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="Diesel+" {{ $id && $user['mainFuel'] == 'Diesel+' ? 'selected' : '' }}>Diesel+</option>
                    </select>
                </div>

                <div class="form-group">
                    <button id="saveButton" type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('admins.user-list') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
        </div>
    </form>
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
        $('#saveButton').prop('disabled', true);
        var initialValues = {};
        $('input, select, textarea').each(function () {
            initialValues[$(this).attr('name')] = $(this).val();
        });

        function checkFormChanges() {
            var isChanged = false;
            $('input, select, textarea').each(function () {
                if (initialValues[$(this).attr('name')] !== $(this).val()) {
                    isChanged = true;
                }
            });
            $('#saveButton').prop('disabled', !isChanged);
        }

        $('input, select, textarea').on('change input', checkFormChanges);
    });
</script>
<script>
    $(document).ready(function () {
        var errorMessage = "{{ session('error') ? session('error') : '' }}";
        errorMessage = errorMessage.replace(/\\'/g, "\\'");
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessage,
            });
        }
    });
</script>
@stop
