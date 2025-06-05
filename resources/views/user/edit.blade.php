@extends('layouts.default')

@section('title', 'Edit Profile')

@section('add-link')
    <a class="btn warning-btn" href="{{ route('user.show') }}">Cancel</a>
@endsection

@section('pagetitle', 'Edit Profile')

@section('maincontent')
    @if ($user->id === auth()->id())
        <form class="auth-form" method="POST" action="{{ route('user.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="input-cont">
                @error('name') <span style="color:crimson">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="text" name="name" id="name" value="{{ $user->name }}" required readonly>
                    <button type="submit" class="edit-btn" id="edit-name-btn" data-field="name" title="Edit Name">
                        <img height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="name">Name</label>    
            </div>

            <div class="input-cont">
                @error('email') <span style="color:crimson">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="email" name="email" id="email" value="{{ $user->email }}" required readonly>
                    <button type="submit" class="edit-btn" id="edit-email-btn" data-field="email" title="Edit Email">
                        <img height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="email">Email</label>
            </div>

            <div class="input-cont">
                @error('password') <span style="color:crimson">{{ $message }}</span> @enderror
                <div class="edit-input">
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep password" readonly>
                    <button type="submit" class="edit-btn" id="edit-password-btn" data-field="password" title="Edit Password">
                        <img height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Info">
                    </button>
                </div>
                <label for="password">Password</label>
            </div>

            <div class="input-cont">
                <input type="password" name="password_confirmation" id="password_confirmation" readonly>
                <label for="password_confirmation">Confirm Password</label>
            </div>

            <button class="btn" type="submit" id="submit-btn" disabled>Change me</button>
        </form>

    @else
        <p style="color: crimson">Unauthorised access</p>
    @endif
@endsection


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