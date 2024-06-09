@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
<h1>Edit Admin</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('manager.admin-update', ['admin' => $id]) }}" method="POST">
        @csrf
        @method('PUT') 

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $admin['name'] }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $admin['email'] }}"
                        required>
                </div>

                <div class="form-group">
                    <button id="saveButton" type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('manager.admin-list') }}" class="btn btn-danger">Back</a>
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
        // Almacena los valores iniciales
        $('#saveButton').prop('disabled', true);
        var initialValues = {};
        $('input, select, textarea').each(function () {
            initialValues[$(this).attr('name')] = $(this).val();
        });

        // Función para verificar si los datos del formulario han cambiado
        function checkFormChanges() {
            var isChanged = false;
            $('input, select, textarea').each(function () {
                if (initialValues[$(this).attr('name')] !== $(this).val()) {
                    isChanged = true;
                }
            });
            $('#saveButton').prop('disabled', !isChanged);
        }

        // Verificar cambios en cada input
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