@extends('layouts.default')

@section('title', 'Commented Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">All Posts</a>
@endsection

@section('pagetitle')
<div class="sort-header">
    Commented Posts
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
                <x-post-card :post="$post" :highlight-own="true" />
            @endforeach
        </div>
    @endif
@endsection