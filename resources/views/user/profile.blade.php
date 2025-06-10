@extends('layouts.default')

@section('title', 'User Profile')

@section('add-link')
    <a class="link" href="{{ route('user.edit', $user->id) }}">Edit Profile</a>
@endsection

@section('pagetitle',  $user->name)

@section('maincontent')
    <dl class="profile">
        <div class="profile-cont">
            <dt>Email</dt>
            <dd>{{ $user->email }}</dd>
        </div>

        @if ($postCount === 0)
            <div class="profile-cont">
                <dt>Posts</dt>
                <dd>{{ $postCount }}</dd>        
            </div>
        @else
            <div class="profile-cont">
                <a class="link" href="{{ route('user.posts') }}" >
                    <dt>Posts</dt>
                </a>
                <dd>{{ $postCount }}</dd>
            </div>
        @endif

        @if ($commentCount === 0)
            <div class="profile-cont">
                <dt>Comments</dt>
                <dd>{{ $commentCount }}</dd>
            </div>
        @else
            <div class="profile-cont">
                <a class="link" href="{{ route('user.commented') }}">
                    <dt>Comments</dt>
                </a>
                <dd>{{ $commentCount }}</dd>
            </div>
        @endif

        <div class="profile-cont">
            <dt>Joined</dt>
            <dd>{{ display_time($user->created_at) }}</dd>
        </div>
    </dl>    
@endsection