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
            <dt>
                <a class="link" href="{{ route('user.posts') }}" >Posts</a>
                <span>
                    <img class="icon" src="{{ asset('images/post_icon.svg') }}" alt="">
                </span>
            </dt>
            @endif
            <dd>{{ $postCount }}</dd>
        </div>

        <div class="profile-cont count-cont">
            @if ($commentCount === 0)
            <dt>Comments</dt>
            @else
            <dt>
                <a class="link" href="{{ route('user.commented') }}">Comments</a>
                <span>
                    <img class="icon" src="{{ asset('images/comment_icon.svg') }}" alt="">
                </span>
            </dt>
            @endif
            <dd>{{ $commentCount }}</dd>
        </div>

        <div class="profile-cont count-cont">
            @if ($likeCount === 0)
            <dt>Likes</dt>
            @else
            <dt>
                <a class="link" href="{{ route('user.liked') }}">Likes</a>
                <span>
                    <img class="icon" src="{{ asset('images/mood_icon.svg') }}" alt="">
                </span>
            </dt>
            @endif
            <dd>{{ $likeCount }}</dd>
        </div>
        
        <div class="profile-cont count-cont">
            @if ($viewCount === 0)
            <dt>Viewed</dt>
            @else
            <dt>
                <a class="link" href="{{ route('user.viewed') }}">Viewed</a>
                <span>
                    <img class="icon" src="{{ asset('images/view_icon.svg') }}" alt="">
                </span>
            </dt>
            @endif
            <dd>{{ $viewCount }}</dd>
        </div>
        
        @endif

        <div class="profile-cont reported-cont">
            @if ($reportCount === 0)
            <dt>Reports</dt>
            @else
            <dt>
                <button type="button" class="link" onclick="openDialog()">
                    Reports
                </button>
                <span>
                    <img class="icon" src="{{ asset('images/report_icon.svg') }}" alt="">
                </span>
            </dt>
            @endif
            <dd>{{ $reportCount }}</dd>
        </div>

        <div class="profile-cont joined-cont">
            <dt>Joined
                <span>
                    <img class="icon" src="{{ asset('images/time_icon.svg') }}" alt="">
                </span>
            </dt>
            <dd>{{ display_time($user->created_at) }}</dd>
        </div>
    </dl>

    <dialog id="user-reports-dialog" class="user-reports-dialog">
        <div class="btn-cont">
            <button type="button" class="btn admin-btn" onclick="this.closest('dialog').close()">X</button>
        </div>
        <h3>My Reports</h3>
        <div id="reports-cont">
        </div>
    </dialog>
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

            const created = document.createElement('div');
            created.classList.add('date')
            created.textContent = `${new Date(report.created_at).toLocaleDateString('en-AU')}`;

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
            card.append(created, reason, status, updated);

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
</script>
@endsection