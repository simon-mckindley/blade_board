@php
    $pageTitle = 'Liked Posts';
@endphp

@extends('layouts.default')

@section('title', $pageTitle)

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">Posts</a>
@endsection

@section('pagetitle')
    <x-sort-header title="{{ $pageTitle }}" />
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

    <x-drawer :tags="$tags" :posts="$posts" :action="'user.liked'" />

@endsection