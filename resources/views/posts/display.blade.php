@extends('layouts.default')

@section('title', 'Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
@endsection

@section('pagetitle', 'Posts')

@section('maincontent')
    @if ($posts->isEmpty())
        <p>No posts found</p>
    @else
        @foreach ($posts as $post)
            <x-post-card :post="$post" :highlight-own="true" />
        @endforeach
    @endif

    {{-- <aside style="
        color: black;
        position: fixed;
        top: 5rem;
        right: 2rem;
        width: 200px;
        background-color: #f8f9fa;">
        <div>One</div>
        <div>Two</div>
        <div>Three</div>
        <div>Four</div>
        <div>Five</div>
    </aside> --}}
@endsection