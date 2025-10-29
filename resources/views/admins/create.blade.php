@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Add User</h2>
    <form method="POST" action="{{ route('admins.store') }}" enctype="multipart/form-data">
        @csrf
        <label>Name</label><input type="text" name="name" class="form-control"><br>
        <label>Email</label><input type="email" name="email" class="form-control"><br>
        <label>Phone</label><input type="text" name="phone" class="form-control"><br>
        <label>Profile Pic</label><input type="file" name="profile_pic" class="form-control"><br>
        <label>Resume</label><input type="file" name="resume" class="form-control"><br>
        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
