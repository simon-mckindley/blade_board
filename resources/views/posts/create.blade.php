@extends('layouts.default')

@section('title', 'Create Post')

@section('cdns')
    <script src="https://cdn.tiny.cloud/1/v3fkqpljj4j2kezzon857vndatqa01pjyxocgfcnx3ejkh84/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#content',
            menubar: false,
            plugins: 'link lists',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link',
            height: 300,
            content_style: 'body { background-color: #ebebeb; color: #073f41; }'
        });
    </script>
@endsection

@section('add-link')
    <a class="link" href="{{ route('posts.display') }}">All Posts</a>
@endsection

@section('pagetitle', 'Create a Post')

@section('maincontent')  

    @if (session('new_post'))
        <p>
            <a class="link" href="{{ route('posts.show', session('new_post')['post_id']) }}">
                View new post
            </a>
        </p>
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