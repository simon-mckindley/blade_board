@extends('layouts.default')

@section('title', 'Liked Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">All Posts</a>
@endsection

@section('pagetitle', 'Liked Posts')

@section('maincontent')
    @if ($posts->isEmpty())
        <p>No posts found</p>
    @else
        @foreach ($posts as $post)
            <x-post-card :post="$post" />
        @endforeach
    @endif
@endsection