@php
    $pageTitle = 'Admin User Management';
@endphp

@extends('layouts.default')

@section('title', $pageTitle)

@section('add-link')
    <a class="link" href="{{ route('admin.users.index') }}">Users Admin</a>
@endsection

@section('pagetitle')
    <h2>{{ $pageTitle }}</h2>
@endsection

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
            <dt>Reports Made</dt>
            <dd>&lpar;{{ $reportCount }}&rpar;</dd>
            @if ($reportCount > 0)
            <button type="button" class="btn admin-btn" onclick="openDialog()" >View</button>
            @endif
        </div>
        
        @endif
    </dl>

    <dialog id="user-reports-dialog" class="user-reports-dialog">
        <div class="btn-cont">
            <button type="button" class="btn admin-btn" onclick="this.closest('dialog').close()">X</button>
        </div>
        <h3>User Reports</h3>
        <div id="reports-cont">
        </div>
    </dialog>

    @else
    <p>Not authorised</p>
    @endif
@endsection

@section('scripts')
<script>
    const dialogSpinner = `            
        <div style="
            width: 2em;
            aspect-ratio: 1;
            margin: 2em auto;
            border-radius: 1000px;
            border: dashed 3px var(--text-color);
            border-bottom-color: transparent;
            animation: spinner 1200ms ease-in-out alternate infinite;
            ">
        </div>
        
        <style>
            @keyframes spinner {100% {rotate: 450deg;}}
        </style>`;

    function renderReports(reports) {
        const container = document.getElementById('reports-cont');
        container.innerHTML = ''; // Clear previous content

        reports.forEach(report => {
            const card = document.createElement('div');
            card.classList.add('user-report-card');

            const header = document.createElement('div');
            header.classList.add('head');

            const link = document.createElement('button');
            link.innerText = 'View';
            link.classList.add('btn', 'admin-btn');
            link.type = 'button';
            link.onclick = '';

            const created = document.createElement('div');
            created.classList.add('date')
            created.textContent = `${new Date(report.created_at).toLocaleDateString('en-AU')}`;

            header.append(created, link);

            const reason = document.createElement('div');
            reason.classList.add('reason');
            reason.textContent = `${report.reason_label}`;

            const status = document.createElement('div');
            status.classList.add('report-status', report.status);
            status.textContent = `${report.status_label}`;

            const updated = document.createElement('div');
            updated.classList.add('date');
            updated.textContent = `Updated -> ${new Date(report.updated_at).toLocaleDateString('en-AU')}`;

            // Append all elements to the card
            // card.appendChild(created);
            // card.appendChild(reason);
            // card.appendChild(status);
            // card.appendChild(updated);
            card.append(header, reason, status, updated);

            // Add the card to the container
            container.appendChild(card);
        });
    }

    async function loadReports(dialog) {
        try {
            const response = await fetch(`{{ route('user.reports', $user->id) }}`);

            if (!response.ok) throw new Error('Failed to fetch report');

            const data = await response.json();
            console.log(data);

            renderReports(data.reports);

        }
        catch (error) {
            console.error('Error loading reports:', error);
            dialog.close();
            alert('Could not load reports.');
        }
    }

    function openDialog() {
        const dialog = document.getElementById('user-reports-dialog');
        const container = document.getElementById('reports-cont');
        container.innerHTML = dialogSpinner; // Clear previous content
        dialog.showModal();
        loadReports(dialog);
    }
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