@extends('layouts.default')

@section('title', 'Tags Admin')

@section('add-link')
    <a class="btn" href="{{ route('admin.dashboard') }}">Admin</a>
@endsection

@section('pagetitle', 'Tags Admin')

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
                <label class="tag-input" for="{{ $tag->name }}">{{ $tag->name }}</label>
                @endforeach
            </div>
        </div>        

        <div id="edit-container" class="container tag-edit-container">
            <div>
                <div class="input-cont">
                    @error('name-edit') <span class="input-error">{{ $message }}</span> @enderror
                    <input type="text" id="name-edit" name="name-edit" value="{{ old('name-edit') }}">
                    <label for="name-edit">Update Name</label>
                </div>

                <button class="btn" type="submit">Update tag</button>
            </div>
        </div>
    </form>

    <button class="btn add-tag-btn" type="button" title="Add a Tag">
        <img height="24" src="{{ asset('images/add_icon.svg') }}" alt="Add Tag">
    </button>
    
    <div id="add-container" class="container tag-form-container">
        <div>
            <form class="post-form" method="POST" action="{{ route('tags.store') }}">
                @csrf        

                <div class="input-cont">
                    @error('name') <span class="input-error">{{ $message }}</span> @enderror
                    <input type="text" id="name" name="name" value="{{ old('name') }}">
                    <label for="name">Name</label>
                </div>

                <button class="btn" type="submit">Create tag</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addTagForm = document.getElementById('add-container');
            const editTagForm = document.getElementById('edit-container');
            const addBtn = document.querySelector('.add-tag-btn');
            const addBtnImg = addBtn.querySelector('img');
 
            addBtn.addEventListener('click', function() {
                addTagForm.classList.toggle('open');
                addBtnImg.src = addTagForm.classList.contains('open') ? 
                    '{{ asset('images/chevron_up_icon.svg') }}' : 
                    '{{ asset('images/add_icon.svg') }}';
            });

            document.querySelectorAll('.tag-input').forEach((tagInput) => {
                tagInput.addEventListener('click', function() {
                    editTagForm.classList.add('open');
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