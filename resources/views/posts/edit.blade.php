@extends('layouts.default')

@section('header')
    <a href="{{ route('home') }}" class="back-button">&lt</a>
    @auth
        <a href="{{ route('user.show') }}">{{ auth()->user()->name }}</a>
    @endauth
    <h1>Edit Post</h1>
@endsection

@section('maincontent')   
    <div class="post">
        @if ($post->user->id === auth()->id())
            <form method="POST" action="{{ route('posts.update', $post->id) }}" style="display:inline;">
                @csrf
                @method('PUT')
                <label for="title">
                    <h2 style="margin-block: 0 5px">{{ ucfirst($post->title) }}</h2>
                </label>
                <input type="text" name="title" id="title" value="{{ $post->title }}" required>
                @error('title') <p style="color:crimson">{{ $message }}</p> @enderror
                
                <div style="font-size: 0.8em; display: flex; flex-direction: column;">
                    <span>Created: {{ $post->created_at->diffForHumans() }}</span>
                    <span>Updated: {{ $post->updated_at->format('F j, Y') }}</span>
                </div>

                <label for="content">Edit content</label>
                <textarea name="content" id="content" rows="5" required>{{ $post->content }}</textarea>
                @error('content') <p style="color:crimson">{{ $message }}</p> @enderror

                <br>
                <label>Edit Tags:</label><br>
                @foreach ($tags as $tag)
                    <label>
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                            {{ $post->tags->contains($tag) ? 'checked' : '' }}
                            {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                        {{ $tag->name }}
                    </label><br>
                @endforeach
                @error('tags') <p style="color:crimson">{{ $message }}</p> @enderror

                <button type="submit">Update</button>
            </form>                
        @else
            <p style="color: crimson">Unauthorised access</p>
        @endif
    </div>

    <a href="{{ route('posts.display') }}">Posts</a>
@endsection