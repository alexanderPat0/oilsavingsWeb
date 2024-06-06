<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <br /><br />

  @if($id)
    <form action="{{ route('users.update', ['user' => $id]) }}" method="POST">
      @method('PUT')
      <h2>Edit User</h2>
  @else
    <form action="{{ route('users.store') }}" method="POST">
      <h2>Add User</h2>
  @endif
      @csrf 
      <br /><br />
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="{{ $id ? $user['username'] : '' }}" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ $id ? $user['email'] : '' }}" required>
      </div>
      <div class="mb-3">
        <label for="mainFuel" class="form-label">Main Fuel</label>
        <select class="form-control" id="mainFuel" name="mainFuel">
          <option value="Sin Plomo 95" {{ $id && $user['mainFuel'] == 'Sin Plomo 95' ? 'selected' : '' }}>Sin Plomo 95</option>
          <option value="Sin Plomo 98" {{ $id && $user['mainFuel'] == 'Sin Plomo 98' ? 'selected' : '' }}>Sin Plomo 98</option>
          <option value="Diesel" {{ $id && $user['mainFuel'] == 'Diesel' ? 'selected' : '' }}>Diesel</option>
          <option value="Diesel+" {{ $id && $user['mainFuel'] == 'Diesel+' ? 'selected' : '' }}>Diesel+</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Save</button>
      <a href="{{ route('users.index') }}" class="btn btn-danger">Back</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</div>
</body>
</html>
