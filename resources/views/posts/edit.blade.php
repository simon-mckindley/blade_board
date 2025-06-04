@extends('layouts.default')

@section('title', 'Edit Post')

@section('add-link')
    <a class="btn" class="post-title" href="{{ route('posts.show', $post->id) }}">Cancel</a>
@endsection

@section('pagetitle', 'Edit Post')

@section('maincontent')
    <div class="">
        @if ($post->user->id === auth()->id())
            <form method="POST" action="{{ route('posts.update', $post->id) }}" style="display:inline;">
                @csrf
                @method('PUT')
                <label for="title">
                    <h2 style="margin-block: 0 5px">{{ ucfirst($post->title) }}</h2>
                </label>
                <input type="text" name="title" id="title" value="{{ $post->title }}" required>
                @error('title') <span style="color:crimson">{{ $message }}</span> @enderror
                
                <div style="font-size: 0.8em; display: flex; flex-direction: column;">
                    <span>Created: {{ $post->created_at->diffForHumans() }}</span>
                    <span>Updated: {{ $post->updated_at->format('j F Y') }}</span>
                </div>

                <label for="content">Edit content</label>
                <textarea name="content" id="content" rows="5" required>{{ $post->content }}</textarea>
                @error('content') <span style="color:crimson">{{ $message }}</span> @enderror

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
                @error('tags') <span style="color:crimson">{{ $message }}</span> @enderror

                <button class="btn" type="submit">Change it</button>
            </form>                
        @else
            <p style="color: crimson">Unauthorised access</p>
        @endif
    </div>
@endsection