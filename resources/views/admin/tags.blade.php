@php
    $pageTitle = 'Tags Admin';
@endphp

@extends('layouts.default')

@section('title', $pageTitle)

@section('add-link')
    <a class="link" href="{{ route('admin.dashboard') }}">Admin</a>
@endsection

@section('pagetitle')
    <h2>{{ $pageTitle }}</h2>
@endsection

@section('maincontent')
    <form class="post-form" method="POST" action="">
        @csrf
        @method('PUT')

        <div class="input-cont">
            @error('tags') <span class="input-error">{{ $message }}</span> @enderror
            <div class="tags-cont">
                @foreach ($tags as $tag)
                <input type="radio" name="tag" id="{{ $tag->name }}" value="{{ $tag->id }}"
                    {{ old('tag') == $tag->id ? 'checked' : '' }}>
                <label class="tag-input" for="{{ $tag->name }}" tabindex="0">{{ $tag->name }}</label>
                @endforeach
            </div>
        </div>        

        <div id="edit-container" class="container tag-edit-container">
            <div>
                <div class="input-cont">
                    @error('name-edit') <span class="input-error">{{ $message }}</span> @enderror
                    <input type="text" id="name-edit" name="name-edit" value="{{ old('name-edit') }}">
                    <label for="name-edit">
                        <img class="icon" src="{{ asset('images/edit_note_icon.svg') }}" alt="">
                        <span>Update Name</span>
                    </label>
                </div>

                <div class="edit-btn-cont">
                    <button type="submit" class="btn submit-btn">Update tag</button>
                    <button type="button" class="delete-btn" onclick="document.getElementById('delete-tag-dialog').showModal()" title="Delete Tag">
                        <img class="icon" height="24" src="{{ asset('images/delete_icon.svg') }}" alt="Delete Tag">
                    </button>
                </div>
            </div>
        </div>
    </form>

    <button class="btn add-tag-btn" type="button" title="Add a Tag">
        <img class="icon" height="24" src="{{ asset('images/add_icon.svg') }}" alt="Add Tag">
    </button>
    
    <div id="add-container" class="container tag-form-container">
        <div>
            <form class="post-form" method="POST" action="{{ route('admin.tags.store') }}">
                @csrf        

                <div class="input-cont">
                    @error('name') <span class="input-error">{{ $message }}</span> @enderror
                    <input type="text" id="name" name="name" value="{{ old('name') }}">
                    <label for="name">
                        <img class="icon" src="{{ asset('images/edit_note_icon.svg') }}" alt="">
                        <span>Name</span>
                    </label>
                </div>

                <button class="btn submit-btn" type="submit">Create tag</button>
            </form>
        </div>
    </div>

    <dialog id="delete-tag-dialog" class="delete-confirm-dialog">
        <h3 id="delete-tag-name"></h3>
        <h3>Are you sure you want to delete this tag?</h3>
        <p>This action cannot be undone!</p>
        <form method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="dialog-actions">
                <button type="button" class="btn" onclick="this.closest('dialog').close()">No, don't</button>
                <button type="submit" class="btn delete-btn submit-btn">Definitely</button>
            </div>
        </form>
    </dialog>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addTagForm = document.getElementById('add-container');
            const editTagForm = document.getElementById('edit-container');
            const deleteTagForm = document.querySelector('#delete-tag-dialog form');
            const addBtn = document.querySelector('.add-tag-btn');
            const addBtnImg = addBtn.querySelector('img');
 
            addBtn.addEventListener('click', function() {
                addTagForm.classList.toggle('open');
                addBtnImg.src = addTagForm.classList.contains('open') ? 
                    '{{ asset('images/chevron_up_icon.svg') }}' : 
                    '{{ asset('images/add_icon.svg') }}';
            });

            document.querySelectorAll('.tag-input').forEach((tagInput) => {
                tagInput.addEventListener('click', function(input) {
                    editTagForm.classList.add('open');
                    setTimeout(() => {
                        const tagId = document.querySelector('input[name="tag"]:checked').value;
                        console.log(tagId);
                        deleteTagForm.action = `tags/${tagId}`;
                    }, timeout = 100);
                });
            });

            if (editTagForm.querySelector('.input-error')) {
                editTagForm.classList.add('open');  
            }

            if (addTagForm.querySelector('.input-error')) {
                addTagForm.classList.add('open');  
            }
        });
    </script>
@endsection