@extends('layouts.default')

@section('title', 'Commented Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">Posts</a>
@endsection

@section('pagetitle')
    <x-sort-header title="Commented Posts" action="user.commented" />
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

        <div class="pagination-links">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif
@endsection