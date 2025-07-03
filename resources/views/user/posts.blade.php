@extends('layouts.default')

@section('title', 'My Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
@endsection

@section('pagetitle')
<div class="sort-header">
    My Posts
    <div class="sort-controls">
        <label for="sort-by">Sort by -> </label>
        <select id="sort-by" class="sort-select">
            <option value="created">Newest</option>
            <option value="likes">Most Liked</option>
            <option value="comments">Most Commented</option>
            <option value="views">Most Viewed</option>
        </select>
    </div>
</div>
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
    @endif
@endsection