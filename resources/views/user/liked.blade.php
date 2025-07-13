@extends('layouts.default')

@section('title', 'Liked Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">Posts</a>
@endsection

@section('pagetitle')
    <x-sort-header title="Liked Posts" action="user.liked" />
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
@endsection