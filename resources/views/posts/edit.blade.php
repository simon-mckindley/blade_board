@extends('layouts.default')

@section('title', 'Edit Post')

@section('cdns')
    <script src="https://cdn.tiny.cloud/1/v3fkqpljj4j2kezzon857vndatqa01pjyxocgfcnx3ejkh84/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#content',
            menubar: false,
            plugins: 'link lists',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link',
            height: 300,
            content_style: 'body { background-color: #ebebeb; color: #073f41; }',
            setup: function (editor) {
                editor.on('input', function () {
                console.log('TinyMCE input event triggered');
                    document.getElementById('submit-btn').removeAttribute('disabled');
                });
            }
        });
    </script>
@endsection

@section('add-link')
    <a class="btn warning-btn" href="{{ route('posts.show', $post->id) }}">Cancel</a>
@endsection

@section('pagetitle', 'Edit -> ' . ucwords($post->title))

@section('maincontent')

    @if ($post->user->id === auth()->id())
        <div class="edit-meta">
            <span>Created -> {{ display_time($post->created_at) }}</span>
            <span>Updated -> {{ display_time($post->updated_at) }}</span>
        </div>

        <form class="post-form" method="POST" action="{{ route('posts.update', $post->id) }}">
            @csrf
            @method('PUT')

            <div class="input-cont">
                @error('title') <span class="input-error">{{ $message }}</span> @enderror
                <input type="text" name="title" id="title" value="{{ $post->title }}">
                <label for="title">Title</label>
            </div>
            
            <div class="input-cont">
                @error('content') <span class="input-error">{{ $message }}</span> @enderror
                <textarea name="content" id="content" rows="5">{{ $post->content }}</textarea>
                <label for="content">Content</label>
            </div>

            <div class="input-cont">
                @error('tags') <span class="input-error">{{ $message }}</span> @enderror
                <div class="tags-cont">
                    @foreach ($tags as $tag)
                    <input type="checkbox" name="tags[]" id="{{ $tag->name }}" value="{{ $tag->id }}"
                        {{ $post->tags->contains($tag) ? 'checked' : '' }}
                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                    <label class="tag-input" for="{{ $tag->name }}" tabindex="0">{{ $tag->name }}</label>
                    @endforeach
                </div>
                <label>Tags</label>
            </div>

            <button class="btn" type="submit" id="submit-btn" disabled>Change it</button>
        </form>                
    @else
        <p class="input-error">Unauthorised access</p>
    @endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const submitBtn = document.getElementById('submit-btn');

        // Enable the submit button if any input is changed
        document.querySelector('input[type="text"]').addEventListener('input', function() {
            submitBtn.removeAttribute('disabled');
        });

        document.querySelectorAll('[name="tags[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', (event) => {
                submitBtn.removeAttribute('disabled');
            });
        });

    });
</script>
@endsection