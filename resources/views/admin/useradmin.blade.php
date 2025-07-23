@extends('layouts.default')

@section('title', 'Admin User Management')

@section('add-link')
    <a class="link" href="{{ route('admin.users.index') }}">Users Admin</a>
@endsection

@section('pagetitle', 'Admin User Management')

@section('maincontent')
    @if (auth()->user()->isAdmin())
    <div class="admin-user-actions">
        <form class="admin-user-form" action="{{ route('user.update', $user->id) }}" method="POST" id="password-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email}}">
            <input type="hidden" name="status" value="{{ $user->status}}">
            <div class="form-head">
                <label for="password">Reset Password</label>
                <button type="button" class="edit-btn" data-form="password" title="Reset Password">
                    <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="">
                </button>
            </div>

            @error('password') <span class="input-error">{{ $message }}</span> @enderror

            <div class="password-body">
                <div class="input-cont">
                    <input type="text" name="password" id="password" class="password-input" autocomplete="new-password" disabled>
                </div>
                <div class="input-cont">
                    <input type="text" name="password_confirmation" id="password_confirmation" class="password-input" autocomplete="new-password" disabled>
                    <label for="password_confirmation">Confirm Password</label>
                </div>
                <button class="btn warning-btn submit-btn" type="submit" disabled>Reset Password</button>
            </div>
        </form>

        <form class="admin-user-form" action="{{ route('user.update', $user->id) }}" method="POST" id="status-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email}}">
            <div class="form-head">
                <label for="status">Status</label>
                <button type="button" class="edit-btn" data-form="status" title="Update Status">
                    <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="">
                </button>
            </div>

            <div class="status-body">
                <select name="status" id="status" class="{{ $user->status }}" disabled>
                    @foreach (\App\Enums\UserStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ $user->status->value === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                    @endforeach
                </select>
                <div class="submit-btn-cont">
                    <button class="btn warning-btn submit-btn" type="submit" disabled>Update Status</button>
                </div>
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
            <dt>Reported Posts</dt>
            <dd>&lpar;{{ $postCount }}&rpar;</dd>
            @if ($postCount > 0)
            <a class="btn" href="{{ route('user.posts') }}" >View</a>
            @endif
        </div>

        <div class="data-cont linked-cont">
            <dt>Reported Comments</dt>
            <dd>&lpar;{{ $commentCount }}&rpar;</dd>
            @if ($commentCount > 0)
            <a class="btn" href="{{ route('user.commented') }}">View</a>
            @endif
        </div>
        
        @endif
    </dl>

    @else
    <p>Not authorised</p>
    @endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const OPEN_DELAY = 800;
        const CLOSE_DELAY = 200;
        const forms = document.querySelectorAll('form');

        // Open form if contains error message
        forms.forEach(form => {
            if (form.querySelector('.input-error')) {
                formOpen(form);
            }
        });

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleForm(btn.dataset.form));
        });

        function toggleForm(formName) {
            forms.forEach(form => {
                const openForm = form.classList.contains('open');
                formClose(form);
                if (form.id === formName + '-form' && !openForm) {
                    formOpen(form);
                }
            });
        }

        function formOpen(form) {
            form.classList.add('open');
            form.querySelectorAll('input, select, .btn').forEach(el => {
                setTimeout(() => {
                    el.disabled = false;
                    if (form.id === 'password-form' && el.classList.contains('password-input')) {
                        // Reset password value to default on open
                        el.value = '123456';
                    }
                }, OPEN_DELAY);
            });
        }

        function formClose(form) {
            form.classList.remove('open');
            form.querySelectorAll('input, select, .btn').forEach(el => {
                setTimeout(() => {
                    el.disabled = true;
                    if (form.id === 'password-form' && el.classList.contains('password-input')) {
                        el.value = '';
                    }
                }, CLOSE_DELAY);
            });
        }
    });
</script>
@endsection