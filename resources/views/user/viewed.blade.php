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
            @php
                $viewer = $post->viewers->firstWhere('id', auth()->id());
            @endphp
    
            @if ($viewer)
                <div style="margin-left: 0.5em;">
                    Last Viewed -> {{ display_time($viewer->pivot->updated_at) }}
                </div>
            @endif
    
            <x-post-card :post="$post" />
        @endforeach
    @endif
@endsection