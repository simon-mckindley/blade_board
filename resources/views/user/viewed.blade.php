@extends('layouts.default')

@section('title', 'Viewed Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">Posts</a>
@endsection

@section('pagetitle', 'Viewed Posts')

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