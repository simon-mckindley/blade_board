@extends('layouts.default')

@section('title', 'My Posts')

@section('pagetitle', 'My Posts')

@section('maincontent')
    <a href="{{ route('posts.create') }}">Create a Post</a>
    <br>

    @foreach ($posts as $post)
        <x-post-card :post="$post" />
    @endforeach

    @if ($posts->isEmpty())
        <p>No posts found</p>
    @endif

@endsection