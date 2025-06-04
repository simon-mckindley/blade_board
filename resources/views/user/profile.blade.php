@extends('layouts.default')

@section('title', 'User Profile')

@section('add-link')
    <a class="link" href="{{ route('user.edit', $user->id) }}">Edit Profile</a>
@endsection

@section('pagetitle',  $user->name)

@section('maincontent')
    <dl>
        <dt>Email</dt>
        <dd>{{ $user->email }}</dd>

        @if ($postCount === 0)
            <dt>Posts</dt>
            <dd>{{ $postCount }}</dd>        
        @else
            <a class="link" href="{{ route('user.posts') }}" >
                <dt>Posts</dt>
                <dd>{{ $postCount }}</dd>
            </a>
        @endif

        @if ($commentCount === 0)
            <dt>Comments</dt>
            <dd>{{ $commentCount }}</dd>
        @else
            <a class="link" href="{{ route('user.commented') }}">
                <dt>Comments</dt>
                <dd>{{ $commentCount }}</dd>
            </a>
        @endif

        <dt>Joined</dt>
        <dd>{{ $user->created_at }}</dd>

        <dt>Last Updated</dt>
        <dd>{{ $user->updated_at }}</dd>
    </dl>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif
    
@endsection