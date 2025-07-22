@extends('layouts.default')

@section('title', 'Edit Profile')

@section('add-link')
    @if (auth()->user()->isAdmin())
    <a class="btn warning-btn" href="{{ route('admin.users.index') }}">Back</a>
    @else
    <a class="btn warning-btn" href="{{ route('user.show') }}">Back</a>
    @endif
@endsection

@section('pagetitle', 'Edit Profile')

@section('maincontent')
    @if ($user->id === auth()->id() || auth()->user()->isAdmin())
        <div class="user-date">
            <dt>Last Updated</dt>
            <dd>{{ display_time($user->updated_at) }}</dd>
        </div>

        <form class="auth-form" method="POST" action="{{ route('user.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="input-cont">
                @error('name') <span class="input-error">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="text" name="name" id="name" value="{{ $user->name }}" required readonly autocomplete="username">
                    <button type="button" class="edit-btn" id="edit-name-btn" data-field="name" title="Edit Name">
                        <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="name">
                    <img class="icon" src="{{ asset('images/edit_note_icon.svg') }}" alt="">
                    <span>Name</span>
                </label>    
            </div>

            <div class="input-cont">
                @error('email') <span class="input-error">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="email" name="email" id="email" value="{{ $user->email }}" required readonly autocomplete="email">
                    <button type="button" class="edit-btn" id="edit-email-btn" data-field="email" title="Edit Email">
                        <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="email">
                    <img class="icon" src="{{ asset('images/email_icon.svg') }}" alt="">
                    <span>Email</span>
                </label>
            </div>

            <div class="input-cont">
                @error('password') <span class="input-error">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep password" readonly autocomplete="new-password">
                    <button type="button" class="edit-btn" id="edit-password-btn" data-field="password" title="Edit Password">
                        <img class="icon" height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="password">
                    <img class="icon" src="{{ asset('images/password_icon.svg') }}" alt="">
                    <span>Password</span>
                </label>
            </div>

            <div class="input-cont">
                <input type="password" name="password_confirmation" id="password_confirmation" readonly autocomplete="new-password">
                <label for="password_confirmation"><span>Confirm Password</span></label>
            </div>

            <div class="edit-actions">
                <button class="btn submit-btn" type="submit" id="submit-btn" disabled>Change me</button>
                @if (auth()->user()->isAdmin())
                <div style="width: min-content;">
                    <label for="status">Status</label>
                    <select name="status" id="status" style="padding: 0;">
                        @foreach (\App\Enums\UserStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ $user->status->value === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <button type="button" class="delete-btn" onclick="document.getElementById('delete-user-dialog').showModal()" title="Delete User">
                    <img class="icon" height="24" src="{{ asset('images/delete_icon.svg') }}" alt="">
                </button>
            </div>
        </form>

    @else
        <p class="input-error">Unauthorised access</p>
    @endif

    <dialog id="delete-user-dialog" class="delete-confirm-dialog">
        <h3>Are you sure you want to delete this user account?</h3>
        <p>This action cannot be undone!</p>
        <form method="POST" action="{{ route('user.destroy', $user->id) }}">
            @csrf
            @method('DELETE')
            <div class="dialog-actions">
                <button type="button" class="btn" onclick="this.closest('dialog').close()">No, don't</button>
                <button type="submit" class="btn delete-btn submit-btn">Definitely</button>
            </div>
        </form>
    </dialog>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.edit-input input');
            const editBtns = document.querySelectorAll('.edit-btn');
            const submitBtn = document.getElementById('submit-btn');

            // Toggles the edit state of the input fields
            editBtns.forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    const field = this.getAttribute('data-field');
                    toggleEdit(field);
                });
            });

            // Enable the submit button when any input field is modified
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    submitBtn.removeAttribute('disabled');
                });
            });

            // Enable the submit button when the status is changed
            document.getElementById('status')?.addEventListener('change', 
                () => submitBtn.removeAttribute('disabled'));

            function toggleEdit(field) {
                const input = document.getElementById(field);
                input.toggleAttribute('readonly');

                if (field === 'password') {
                    const confirmInput = document.getElementById('password_confirmation');
                    confirmInput.toggleAttribute('readonly');
                }
            }
        });
    </script>
@endsection