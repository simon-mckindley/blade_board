@extends('layouts.default')

@section('title', 'My Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
@endsection

@section('pagetitle-sort')
    <x-sort-header title="My Posts" />
@endsection

@section('maincontent')
    @if ($posts->isEmpty())
        <p>No posts found</p>
    @else
        <div class="posts-container">
            @foreach ($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>

        <div class="pagination-links">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif

    <x-drawer :tags="$tags" :posts="$posts" :needsUser="false" :action="'user.posts'" />

@endsection