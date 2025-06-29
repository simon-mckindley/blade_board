@extends('layouts.default')

@section('title', 'Tags Admin')

@section('add-link')
    <a class="btn" href="{{ route('admin.dashboard') }}">Admin</a>
@endsection

@section('pagetitle', 'Tags Admin')

@section('maincontent')
    <h2>Manage Tags</h2>

    <form class="post-form" method="POST" action="">
        @csrf

        <div class="input-cont">
            @error('tags') <span class="input-error">{{ $message }}</span> @enderror
            <div class="tags-cont">
                @foreach ($tags as $tag)
                <input type="radio" name="tags[]" id="{{ $tag->name }}" value="{{ $tag->id }}"
                    {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                <label class="tag-input" for="{{ $tag->name }}">{{ $tag->name }}</label>
                @endforeach
            </div>
            <label>Tags</label>
        </div>        

        <div class="input-cont">
            @error('name') <span class="input-error">{{ $message }}</span> @enderror
            <input type="text" id="name-edit" name="name" value="{{ old('name') }}">
            <label for="name-edit">Name</label>
        </div>

        <button class="btn" type="submit">Update tag</button>
    </form>
    
    <form class="post-form" method="POST" action="{{ route('tags.store') }}">
        @csrf        

        <div class="input-cont">
            @error('name') <span class="input-error">{{ $message }}</span> @enderror
            <input type="text" id="name" name="name" value="{{ old('name') }}">
            <label for="name">Name</label>
        </div>

        <button class="btn" type="submit">Create tag</button>
    </form>
@endsection