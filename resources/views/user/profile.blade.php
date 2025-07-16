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

        @if (!auth()->user()->isAdmin())
            
        <div class="profile-cont count-cont">
            @if ($postCount === 0)
            <dt>Posts</dt>
            @else
            <a class="link" href="{{ route('user.posts') }}" >
                <dt>Posts</dt>
            </a>
            @endif
            <dd>{{ $postCount }}</dd>
        </div>

        <div class="profile-cont count-cont">
            @if ($commentCount === 0)
            <dt>Comments</dt>
            @else
            <a class="link" href="{{ route('user.commented') }}">
                <dt>Comments</dt>
            </a>
            @endif
            <dd>{{ $commentCount }}</dd>
        </div>

        <div class="profile-cont count-cont">
            @if ($likeCount === 0)
            <dt>Likes</dt>
            @else
            <a class="link" href="{{ route('user.liked') }}">
                <dt>Likes</dt>
            </a>
            @endif
            <dd>{{ $likeCount }}</dd>
        </div>
        
        <div class="profile-cont count-cont">
            @if ($viewCount === 0)
            <dt>Viewed</dt>
            @else
            <a class="link" href="{{ route('user.viewed') }}">
                <dt>Viewed</dt>
            </a>
            @endif
            <dd>{{ $viewCount }}</dd>
        </div>
        
        @endif

        <div class="profile-cont joined-cont">
            <dt>Joined</dt>
            <dd>{{ display_time($user->created_at) }}</dd>
        </div>
    </dl>    
@endsection