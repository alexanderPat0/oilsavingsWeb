<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">  
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  </head>
<body>
<div class="container">
  <br /><br />
	<h2>Users</h2>

    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm btn-rounded float-end">Add User</a>
    <br /><br />
    <table class="table table-bordered table-hover">
        <thead>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Main Fuel</th>
            <th>Edit user</th>
            <th>Delete user</th>
        </thead>

        <tbody>
        @foreach($users as $id => $user)
            <tr>
                <td>{{ $user['id'] }}</td>
                <td>{{ $user['username'] }}</td>
                <td>{{ $user['email'] }}</td>
                <td>{{ $user['mainFuel'] }}</td>
                <td><a href="{{ route('users.edit', ['user' => $id]) }}" class="btn btn-success btn-sm btn-rounded">Edit</a></td>
                <td><a href="{{ route('users.destroy', ['user' => $id]) }}" class="btn btn-danger btn-sm btn-rounded">Delete</a></td>

            </tr>
            @endforeach
        </tbody>
    </table>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>