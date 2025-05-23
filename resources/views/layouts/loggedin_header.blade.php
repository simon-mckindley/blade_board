<a href="{{ route('home') }}" class="back-button">&lt</a>
@auth
    <span>{{ auth()->user()->name }}</span>
@endauth
<h1>@yield('title')</h1>