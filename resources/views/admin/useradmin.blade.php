@extends('layouts.default')

@section('title', 'Admin User Management')

@section('add-link')
    <a class="link" href="{{ route('admin.users.index') }}">Users Admin</a>
@endsection

@section('pagetitle', 'Admin User Management')

@section('maincontent')
    @if (auth()->user()->isAdmin())
    <div class="admin-user-actions">
        <form class="admin-user-form" action="" method="post" id="status-form">
            <div class="form-head">
                <label for="status">Status</label>
                <button type="button" class="edit-btn" data-form="status" title="Update Status">
                    <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="">
                </button>
            </div>

            <div class="status-body">
                <select name="status" id="status" >
                    @foreach (\App\Enums\UserStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ $user->status->value === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                    @endforeach
                </select>
                <div class="submit-btn-cont">
                    <button class="btn warning-btn" type="submit" >Update Status</button>
                </div>
            </div>
        </form>

        <form class="admin-user-form" action="" method="post" id="password-form">
            <div class="form-head">
                <label for="password">Reset Password</label>
                <button type="button" class="edit-btn" data-form="password" title="Reset Password">
                    <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="">
                </button>
            </div>

            <div class="password-body">
                <div class="input-cont">
                    @error('password') <span class="input-error">{{ $message }}</span> @enderror
                    <input type="text" name="password" id="password" autocomplete="new-password" >
                </div>
                <div class="input-cont">
                    <input type="text" name="password_confirmation" id="password_confirmation" autocomplete="new-password" >
                    <label for="password_confirmation">Confirm Password</label>
                </div>
                <button class="btn warning-btn" type="submit" >Reset Password</button>
            </div>
        </form>
    </div>

    <dl class="admin-user-data">
        <div class="data-cont">
            <dd>{{ $user->name }}</dd>
        </div>

        <div class="data-cont">
            <dt>Email</dt>
            <dd>{{ $user->email }}</dd>
        </div>

        <div class="data-cont">
            <dt>Joined</dt>
            <dd>{{ display_time($user->created_at) }}</dd>
        </div>

        @if (!$user->isAdmin())
            
        <div class="data-cont linked-cont">
            <dt>Posts</dt>
            <dd>&lpar;{{ $postCount }}&rpar;</dd>
            @if ($postCount > 0)
            <a class="btn" href="{{ route('user.posts') }}" >View Posts</a>
            @endif
        </div>

        <div class="data-cont linked-cont">
            <dt>Comments</dt>
            <dd>&lpar;{{ $commentCount }}&rpar;</dd>
            @if ($commentCount > 0)
            <a class="btn" href="{{ route('user.commented') }}">View Comments</a>
            @endif
        </div>
        
        @endif
    </dl>

    @else
    <p>Not authorised</p>
    @endif
@endsection