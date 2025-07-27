@extends('layouts.default')

@section('title', 'Posts')

@auth
    @section('add-link')
        @if (auth()->user()->isAdmin())
        <a class="link" href="{{ route('admin.dashboard') }}">Admin</a>
        @else
        <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
        @endif
    @endsection
@endauth

@guest
    @section('add-link')
        <a class="btn" href="{{ route('login') }}">Sign in</a>
    @endsection
@endguest

@section('pagetitle-sort')
    <x-sort-header />
@endsection

@section('maincontent')
    @if ($posts->isEmpty())
        <p>No posts found!</p>
    @else
        <div class="posts-container">
            @foreach ($posts as $post)
                <x-post-card :post="$post" :highlight-own="true" />
            @endforeach
        </div>

        <div class="pagination-links">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif

    <x-drawer :tags="$tags" :posts="$posts" />

@endsection


{{-- Lorem ipsum dolor, sit amet consectetur adipisicing elit. Obcaecati maiores quibusdam 
voluptatem suscipit quaerat excepturi illum dolore tempore, distinctio quasi nisi veritatis 
nam eaque sint quo eum recusandae dignissimos delectus? --}}