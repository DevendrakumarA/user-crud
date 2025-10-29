<h3>User List</h3>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Profile</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Resume</th>
    </tr>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>
            @if($user->profile)
            <img src="{{ asset('storage/'.$user->profile) }}" alt="Profile" width="50">
            <a href="{{ asset('storage/'.$user->profile) }}">View Profile</a>
            @else
            No Image
            @endif
        </td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phone }}</td>
        <td>
            @if($user->resume)
            <a href="{{ asset('storage/'.$user->resume) }}">View Resume</a>
            @else
            No Resume
            @endif
        </td>
    </tr>
    @endforeach
</table>