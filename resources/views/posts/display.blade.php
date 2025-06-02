@extends('layouts.default')

@section('title', 'Posts')

@section('pagetitle', 'Posts')

@section('maincontent')
    @if (session('success'))
        <div style="color: green">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('posts.create') }}">Create a Post</a>

    @foreach ($posts as $post)
        <x-post-card :post="$post" :highlight-own="true" />
    @endforeach

    @if ($posts->isEmpty())
        <p>No posts found</p>
    @endif
@endsection