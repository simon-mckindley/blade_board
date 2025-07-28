@php
    $pageTitle = 'User Profile';
@endphp

@extends('layouts.default')

@section('title', $pageTitle)

@section('add-link')
    <a class="link" href="{{ route('user.edit', $user->id) }}">Edit Profile</a>
@endsection

@section('pagetitle')
    <h2>{{ $user->name }}</h2>
@endsection

@section('maincontent')
    <dl class="profile">
        <div class="profile-cont">
            <dt>Email
                <span>
                    <img class="icon" src="{{ asset('images/email_icon.svg') }}" alt="">
                </span>
            </dt>
            <dd>{{ $user->email }}</dd>
        </div>

        @if (!auth()->user()->isAdmin())
            
        <div class="profile-cont count-cont">
            @if ($postCount === 0)
            <dt>Posts</dt>
            @else
            <a class="link" href="{{ route('user.posts') }}" >
                <dt>Posts
                    <span>
                        <img class="icon" src="{{ asset('images/post_icon.svg') }}" alt="">
                    </span>
                </dt>
            </a>
            @endif
            <dd>{{ $postCount }}</dd>
        </div>

        <div class="profile-cont count-cont">
            @if ($commentCount === 0)
            <dt>Comments</dt>
            @else
            <a class="link" href="{{ route('user.commented') }}">
                <dt>Comments
                    <span>
                        <img class="icon" src="{{ asset('images/comment_icon.svg') }}" alt="">
                    </span>
                </dt>
            </a>
            @endif
            <dd>{{ $commentCount }}</dd>
        </div>

        <div class="profile-cont count-cont">
            @if ($likeCount === 0)
            <dt>Likes</dt>
            @else
            <a class="link" href="{{ route('user.liked') }}">
                <dt>Likes
                    <span>
                        <img class="icon" src="{{ asset('images/mood_icon.svg') }}" alt="">
                    </span>
                </dt>
            </a>
            @endif
            <dd>{{ $likeCount }}</dd>
        </div>
        
        <div class="profile-cont count-cont">
            @if ($viewCount === 0)
            <dt>Viewed</dt>
            @else
            <a class="link" href="{{ route('user.viewed') }}">
                <dt>Viewed
                    <span>
                        <img class="icon" src="{{ asset('images/view_icon.svg') }}" alt="">
                    </span>
                </dt>
            </a>
            @endif
            <dd>{{ $viewCount }}</dd>
        </div>
        
        @endif

        <div class="profile-cont joined-cont">
            <dt>Joined
                <span>
                    <img class="icon" src="{{ asset('images/time_icon.svg') }}" alt="">
                </span>
            </dt>
            <dd>{{ display_time($user->created_at) }}</dd>
        </div>
    </dl>    
@endsection