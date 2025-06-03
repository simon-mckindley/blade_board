@extends('layouts.default')

@section('title', 'Commented Posts')

@section('add-link')
    <a href="{{ route('posts.display') }}">All Posts</a>
@endsection

@section('pagetitle', 'Commented Posts')

@section('maincontent')
    @if ($posts->isEmpty())
        <p>No posts found</p>
    @else
        @foreach ($posts as $post)
            <x-post-card :post="$post" />
        @endforeach
    @endif
@endsection