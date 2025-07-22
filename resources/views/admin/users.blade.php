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

    <form class="auth-form" action="{{ route('admin.users.index') }}" method="GET">
        <div class="input-cont">
            @error('search') <span class="input-error">{{ $message }}</span> @enderror
            <input type="search" id="search" name="search" value="{{ old('search', $searchTerm ?? '') }}" placeholder="Search users...">
            <label style="visibility: hidden; height: 0;" for="search">Search Users</label>
        </div>
    
        <button class="btn submit-btn" type="submit">Search</button>
    </form>   

    @if (isset($users))
        @if ($users->isEmpty())
            <p>No users found!</p>
        @else
            <p>Users found -> &lpar;{{ $users->count() }}&rpar;</p>

            @foreach ($users as $user)
                <div class="user-card">
                    <div class="user-header">
                        <h3>{{ $user->name }}</h3>
                        {{-- Display edit button only if the user is not an admin or if the current user is a super admin --}}
                         @if (!$user->isAdmin() || auth()->user()->isSuper())
                            <a class="btn" href="{{ route('admin.user', $user->id) }}">View</a>
                        @endif
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
                            <div>
                                Status -> <span class="user-status {{ $user->status }}">{{ $user->status->label() }}</span>
                            </div>            
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endif 
@endsection