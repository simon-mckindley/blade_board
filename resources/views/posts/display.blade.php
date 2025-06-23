@extends('layouts.default')

@section('title', 'Posts')

@section('add-link')
    <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
@endsection

@section('pagetitle', 'Posts')

@section('maincontent')
    @if ($posts->isEmpty())
        <p>No posts found</p>
    @else
        @foreach ($posts as $post)
            <x-post-card :post="$post" :highlight-own="true" />
        @endforeach
    @endif

    <aside class="drawer">
        <div class="drawer-content">
            <form id="filters-form" class="filters">
                <h3>Filters</h3>
                <div class="radio-inputs">
                    <input type="radio" name="filter" id="title" value="title" checked>
                    <label for="title">Title</label>
                    <input type="radio" name="filter" value="user" id="user">
                    <label for="user">User</label>
                </div>

                <input type="text" name="query" placeholder="Filter posts..." value="{{ request('query') }}">
                
                <div class="filter-inputs date-inputs">
                    <div>Date Range</div>
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" value="{{ request('end_date') }}">
                </div>

                <div class="filter-inputs">
                    <div>Tags</div>
                    <select name="tag" multiple>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn filter-btn" type="submit">Go</button>
            </form>

            <div class="stats">
                <h3>Stats</h3>
                <div>Total Posts &lpar;{{ $posts->count() }}&rpar;</div>
                <div>Last Post -> {{ $posts->max('updated_at')->diffForHumans() }}</div>
            </div>
        </div>

        <button type="button" class="drawer-tab" title="Toggle Drawer">
            <img width="36" src="{{ asset('images/chevron_right_white.png') }}" alt=">">
        </button>
    </aside>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const drawer = document.querySelector('.drawer');
            
            document.querySelector('.drawer-tab').addEventListener('click', function() {
                drawer.classList.toggle('closed');
            });

            document.getElementById('filters-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                const filter = formData.get('filter');
                const query = formData.get('query'); 
                const startDate = formData.get('start_date');
                const endDate = formData.get('end_date');
                const tags = formData.getAll('tag');

                console.log({ filter, query, startDate, endDate, tags });
            });
        });
    </script>
@endsection