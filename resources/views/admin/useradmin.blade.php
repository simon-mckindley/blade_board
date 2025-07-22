@extends('layouts.default')

@section('title', 'Admin User Management')

@section('add-link')
    <a class="link" href="{{ route('admin.users.index') }}">Users Admin</a>
@endsection

@section('pagetitle', 'Admin User Management')

@section('maincontent')
    @if (auth()->user()->isAdmin())
    <dl class="">
        <div class="">
            <dd>{{ $user->name }}</dd>
        </div>

        <div class="">
            <dt>Email</dt>
            <dd>{{ $user->email }}</dd>
        </div>

        <div class="">
            <dt>Joined</dt>
            <dd>{{ display_time($user->created_at) }}</dd>
        </div>

        @if (!$user->isAdmin())
            
        <div class="">
            <dt>Posts</dt>
            <dd>&lpar;{{ $postCount }}&rpar;</dd>
            @if ($postCount > 0)
            <a class="btn" href="{{ route('user.posts') }}" >View Posts</a>
            @endif
        </div>

        <div class="">
            <dt>Comments</dt>
            <dd>&lpar;{{ $commentCount }}&rpar;</dd>
            @if ($commentCount > 0)
            <a class="btn" href="{{ route('user.commented') }}">View Comments</a>
            @endif
        </div>
        
        @endif
    </dl>

    <div class="admin-user-actions">
        <form class="" action="" method="post" id="status">
            <button type="button" class="edit-btn" data-form="status" title="Update Status">
                <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="">
            </button>
            <label for="status">Status</label>
            <select name="status" id="status" disabled>
                @foreach (\App\Enums\UserStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ $user->status->value === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <button class="btn warning-btn" type="submit" disabled>Upadate Status</button>
        </form>

        <form class="" action="" method="post" id="password">
            <button type="button" class="edit-btn" data-form="password" title="Reset Password">
                <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="">
            </button>
            <div class="input-cont">
                @error('password') <span class="input-error">{{ $message }}</span> @enderror
                <input type="text" name="password" id="password" value="123456" autocomplete="new-password" disabled>
                <label for="password"><span>Password</span></label>
            </div>

            <div class="input-cont">
                <input type="text" name="password_confirmation" id="password_confirmation" value="123456" autocomplete="new-password" disabled>
                <label for="password_confirmation"><span>Confirm Password</span></label>
            </div>

            <button class="btn warning-btn" type="submit" disabled>Reset Password</button>
        </form>
    </div>
    @endif 
@endsection