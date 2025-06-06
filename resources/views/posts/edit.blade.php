@extends('layouts.default')

@section('title', 'Edit Post')

@section('add-link')
    <a class="btn warning-btn" href="{{ route('posts.show', $post->id) }}">Cancel</a>
@endsection

@section('pagetitle', 'Edit -> ' . ucfirst($post->title))

@section('maincontent')
    @if ($post->user->id === auth()->id())
        <form class="post-form" method="POST" action="{{ route('posts.update', $post->id) }}">
            @csrf
            @method('PUT')
            
            <div>
                <button type="submit" class="edit-btn" id="edit-name-btn" data-field="name" title="Edit Post">
                    <img height="24" src="{{ asset('images/edit_square.svg') }}" alt="Edit Post">
                </button>
                
                <div style="font-size: 0.8em; display: flex; flex-direction: column;">
                    <span>Created: {{ $post->created_at->diffForHumans() }}</span>
                    <span>Updated: {{ $post->updated_at->format('j F Y') }}</span>
                </div>
            </div>

            <div class="input-cont">
                @error('title') <span style="color:crimson">{{ $message }}</span> @enderror
                <input type="text" name="title" id="title" value="{{ $post->title }}" required readonly>
                <label for="title">Title</label>
            </div>
            
            <div class="input-cont">
                @error('content') <span style="color:crimson">{{ $message }}</span> @enderror
                <textarea name="content" id="content" rows="5" required readonly>{{ $post->content }}</textarea>
                <label for="content">Edit content</label>
            </div>

            <div class="input-cont">
                <div class="tags-cont">
                    @foreach ($tags as $tag)
                    <input type="checkbox" name="tags[]" id="{{ $tag->name }}" value="{{ $tag->id }}"
                    {{ $post->tags->contains($tag) ? 'checked' : '' }}
                    {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                    <label class="tag-input" for="{{ $tag->name }}">{{ $tag->name }}</label>
                    @endforeach
                </div>
                <label>Tags</label>
            </div>
            @error('tags') <span style="color:crimson">{{ $message }}</span> @enderror

            <button class="btn" type="submit">Change it</button>
        </form>                
    @else
        <p style="color: crimson">Unauthorised access</p>
    @endif
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.input-cont input');
        const editBtn = document.querySelector('.edit-btn');

        // Toggles the edit state of the input fields
        editBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const field = this.getAttribute('data-field');
                toggleEdit(field);
            });
        });

        // Enable the submit button when any input field is modified
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                submitBtn.removeAttribute('disabled');
            });
        });

        function toggleEdit(field) {
            const input = document.getElementById(field);
            input.toggleAttribute('readonly');

            if (field === 'password') {
                const confirmInput = document.getElementById('password_confirmation');
                confirmInput.toggleAttribute('readonly');
            }
        }
    });
</script>