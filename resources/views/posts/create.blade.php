@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt</a>
    @auth
        <span>{{ auth()->user()->name }}</span>
    @endauth
    <h1>Create</h1>
@endsection

@section('maincontent')   
    <h2>Create a new item</h2>
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf

        <div>
            <label>Title:</label>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title') <p style="color:crimson">{{ $message }}</p> @enderror
        </div>

        <div>
            <label>Content:</label>
            <textarea name="content">{{ old('content') }}</textarea>
            @error('content') <p style="color:crimson">{{ $message }}</p> @enderror
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
            @error('tags') <p style="color:crimson">{{ $message }}</p> @enderror
        </div>        

        <button type="submit">Publish</button>
    </form>
    
@endsection