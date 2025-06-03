@extends('layouts.default')

@section('title', 'Posts')

@section('add-link')
    <a href="{{ route('posts.create') }}">Create a Post</a>
@endsection

@section('pagetitle', 'Posts')

@section('maincontent')
    @if (session('success'))
        <div style="color: green">
            {{ session('success') }}
        </div>
    @endif

    @if ($posts->isEmpty())
        <p>No posts found</p>
    @else
        @foreach ($posts as $post)
            <x-post-card :post="$post" :highlight-own="true" />
        @endforeach
    @endif
@endsection