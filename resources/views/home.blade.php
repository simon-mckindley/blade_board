@extends('layouts.default')

@section('header')
    <div class="home-title">BLADE_<span>board</span></div>
@endsection

@auth
    @section('add-link')
        <button form="logout" class="btn warning-btn submit-btn" type="submit">Logout</button>
    @endsection
@endauth

@section('pagetitle', 'Welcome')

@section('maincontent')
    @auth
        <p>
            Good <span class="time"></span> 
            <strong style="font-size: 1.1em">{{ ucwords(auth()->user()->name) }}</strong> 
            <br>
            What would you like to do today?
        </p>
        
        <div class="home-actions">
        @if (auth()->user()->isAdmin())
            <a class="btn" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        @else
            <a class="btn" href="{{ route('posts.create') }}">Create</a>
            <a class="btn" href="{{ route('posts.display') }}">Posts</a>
            <a class="btn" href="{{ route('user.show') }}">Profile</a>
        @endif
        </div>

        <form id="logout" method="POST" action="{{ route('logout') }}">
            @csrf
        </form>
    @endauth
    
    @guest
        <div class="home-actions">
            <p>Welcome to <i>BLADE_board</i><br>
            A place to share your thoughts and ideas with the world<br>
            Sign in or sign up to start sharing</p>
            <a class="btn" href="{{ route('login') }}">Sign in</a>
            <a class="btn" href="{{ route('register') }}">Sign up</a>
            <a class="btn guest-btn" href="{{ route('posts.display') }}">Continue as Guest</a>
        </div>
    @endguest

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timeElement = document.querySelector('.time');
            if (!timeElement) return; // Ensure the element exists
            
            // Get the current hour and set the time of day
            const hour = new Date().getHours();

            if (hour < 12) {
                timeElement.textContent = 'morning';
            } else if (hour < 18) {
                timeElement.textContent = 'afternoon';
            } else {
                timeElement.textContent = 'evening';
            }
        });
    </script>
@endsection