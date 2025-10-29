<h3>User List</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th></tr>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phone }}</td>
    </tr>
    @endforeach
</table>
