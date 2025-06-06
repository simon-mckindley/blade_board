@extends('layouts.default')

@section('title', 'Create Post')

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">All Posts</a>
@endsection

@section('pagetitle', 'Create a Post')

@section('maincontent')    
    @if (session('success'))
    <div style="color: green; margin-top: 10px;">
        <p>{{ session('success')['message'] }}</p>
        <p>
            <a class="link" href="{{ route('posts.show', session('success')['post_id']) }}">
                View new post
            </a>
        </p>
    </div>
    @endif
    
    <form class="post-form" method="POST" action="{{ route('posts.store') }}">
        @csrf

        <div class="input-cont">
            @error('title') <span style="color:crimson">{{ $message }}</span> @enderror
            <input type="text" id="title" name="title" value="{{ old('title') }}">
            <label for="title">Title</label>
        </div>

        <div class="input-cont">
            @error('content') <span style="color:crimson">{{ $message }}</span> @enderror
            <textarea id="content" name="content" rows="6">{{ old('content') }}</textarea>
            <label for="content">Content</label>
        </div>

        <div class="input-cont">
            @error('tags') <span style="color:crimson">{{ $message }}</span> @enderror
            <div class="tags-cont">
                @foreach ($tags as $tag)
                <input type="checkbox" name="tags[]" id="{{ $tag->name }}" value="{{ $tag->id }}"
                    {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                <label class="tag-input" for="{{ $tag->name }}">{{ $tag->name }}</label>
                @endforeach
            </div>
            <label>Tags</label>
        </div>        

        <button class="btn" type="submit">Post it</button>
    </form>    
@endsection