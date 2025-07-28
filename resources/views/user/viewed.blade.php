@php
    $pageTitle = 'Viewed Posts';
@endphp

@extends('layouts.default')

@section('title', $pageTitle)

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">Posts</a>
@endsection

@section('pagetitle')
    <h2>{{ $pageTitle }}</h2>
@endsection

@section('maincontent')
    @if ($posts->isEmpty())
        <p>No posts found</p>
    @else
        @foreach ($posts as $post)
            <div style="margin-left: 0.5em;">
                Last Viewed -> {{ display_time($post->last_viewed_at) }}
            </div>

            <x-post-card :post="$post" />
        @endforeach

        <div class="pagination-links">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    @endif
@endsection