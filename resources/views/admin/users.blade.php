@extends('layouts.default')

@section('title', 'Users Admin')

@section('add-link')
    <a class="link" href="{{ route('admin.dashboard') }}">Admin</a>
@endsection

@section('pagetitle', 'Users Admin')

@section('maincontent')
    @if (auth()->user()->isSuper())
        <a class="btn new-admin-btn" href="{{ route('super.register') }}">Create New Admin User</a>
    @endif

    @if ($users->isEmpty())
        <p>No users found!</p>
    @else
        @foreach ($users as $user)
            @if ($user->isAdmin() && !auth()->user()->isSuper())
                @continue
            @endif

            <div class="user-card">
                <div class="user-header">
                    <h3>{{ $user->name }}</h3>
                    <a class="btn" href="{{ route('user.edit', $user->id) }}">Edit</a>
                </div>
                <div class="user-content">
                    <div class="user-email">{{ $user->email }}</div>
                    <div class="user-joined">Joined -> {{ display_time($user->created_at) }}</div>
                    <div class="user-meta">
                    @if ($user->isAdmin())
                        <strong>Admin User</strong>
                    @else
                        <div>Posts &lpar;{{ $user->posts_count }}&rpar;</div>
                        <div>Comments &lpar;{{ $user->comments_count }}&rpar;</div>
                    @endif               
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection