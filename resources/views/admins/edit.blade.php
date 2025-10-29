@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Edit Admin</h2>
    <form method="POST" action="{{ route('admins.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="profile_pic" class="form-label">Profile Picture</label>
            @if($user->profile)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$user->profile) }}" width="100" alt="Current Profile Picture">
                </div>
            @endif
            <input type="file" name="profile_pic" id="profile_pic" class="form-control">
            @error('profile_pic')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="resume" class="form-label">Resume</label>
            @if($user->resume)
                <div class="mb-2">
                    <a href="{{ asset('storage/'.$user->resume) }}" target="_blank">Current Resume</a>
                </div>
            @endif
            <input type="file" name="resume" id="resume" class="form-control">
            @error('resume')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admins.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection