@extends('layouts.default')

@section('title', 'Create Post')

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">All Posts</a>
@endsection

@section('pagetitle', 'Create a Post')

@section('maincontent')    
    <form class="post-form" method="POST" action="{{ route('posts.store') }}">
        @csrf

        <div class="input-cont">
            <label for="title">Title</label>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title') <span style="color:crimson">{{ $message }}</span> @enderror
        </div>

        <div class="input-cont">
            <label for="content">Content</label>
            <textarea name="content">{{ old('content') }}</textarea>
            @error('content') <span style="color:crimson">{{ $message }}</span> @enderror
        </div>

        <div class="input-cont">
            <label>Tags</label>
            <div class="tags-cont">
                @foreach ($tags as $tag)
                <input type="checkbox" name="tags[]" id="{{ $tag->name }}" value="{{ $tag->id }}"
                    {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                <label class="tag-input" for="{{ $tag->name }}">{{ $tag->name }}</label>
                @endforeach
            </div>
            @error('tags') <span style="color:crimson">{{ $message }}</span> @enderror
        </div>        

        <button class="btn" type="submit">Post it</button>
    </form>

    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            <p>{{ session('success')['message'] }}</p>
            <p>
                <a class="link" href="{{ route('posts.show', session('success')['post_id']) }}">
                    View Post
                </a>
            </p>
        </div>
    @endif
    
@endsection