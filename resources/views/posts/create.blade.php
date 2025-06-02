@extends('layouts.default')

@section('title', 'Create Post')

@section('pagetitle', 'Create a Post')

@section('maincontent')   
    <a href="{{ route('posts.display') }}">View All Posts</a>
    
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf

        <div>
            <label>Title:</label>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title') <span style="color:crimson">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Content:</label>
            <textarea name="content">{{ old('content') }}</textarea>
            @error('content') <span style="color:crimson">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Tags:</label><br>
            @foreach ($tags as $tag)
                <label>
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                    {{ $tag->name }}
                </label><br>
            @endforeach
            @error('tags') <span style="color:crimson">{{ $message }}</span> @enderror
        </div>        

        <button type="submit">Publish</button>
    </form>

    @if (session('success'))
        <div style="color: green; margin-top: 10px;">
            <p>{{ session('success')['message'] }}</p>
            <p>
                <a href="{{ route('posts.show', session('success')['post_id']) }}">
                    View Post
                </a>
            </p>
        </div>
    @endif
    
@endsection