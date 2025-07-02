@extends('layouts.default')

@section('title', 'Posts')

@section('add-link')
    @if (auth()->user()->isAdmin())
    <a class="link" href="{{ route('admin.dashboard') }}">Admin</a>
    @else
    <a class="link" href="{{ route('posts.create') }}">Create a Post</a>
    @endif
@endsection

@section('pagetitle', 'Posts')

@section('maincontent')
    @if ($posts->isEmpty())
        <p>No posts found!</p>
        <p>Why not create one -> <a class="link" href="{{ route('posts.create') }}">HERE</a> ?</p>
    @else
        @foreach ($posts as $post)
            <x-post-card :post="$post" :highlight-own="true" />
        @endforeach

        <aside class="drawer closed">
            <div class="drawer-content">
                <form id="filters-form" class="filters">
                    <h3>Filters</h3>
                    <div class="filter-inputs">
                        <div class="radio-inputs">
                            <input type="radio" name="filter" id="title" value="title" checked>
                            <label for="title" tabindex="0">Title</label>
                            <input type="radio" name="filter" value="user" id="user">
                            <label for="user" tabindex="0">User</label>
                        </div>
                        <input type="text" name="query" placeholder="Filter posts...">
                    </div>

                    
                    <div class="filter-inputs date-inputs">
                        <div>Date Range</div>
                        <div>
                            <label class="date-label" for="start-date">From</label>
                            <input id="start-date" type="date" name="start-date">
                        </div>
                        <div>
                            <label class="date-label" for="end-date">To</label>
                            <input id="end-date" type="date" name="end-date">
                        </div>
                    </div>

                    <div class="filter-inputs">
                        <div>Tags</div>
                        <div class="tags-cont">
                            @foreach ($tags as $tag)
                                <input type="checkbox" name="tags[]" id="{{ $tag->name }}" value="{{ $tag->id }}">
                                <label class="tag-input" for="{{ $tag->name }}">{{ $tag->name }}</label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-btns">
                        <button class="btn" type="reset" onclick="resetFilters()">Clear</button>
                        <button class="btn" type="submit">Go</button>
                    </div>
                </form>

                <div class="stats">
                    <h3>Stats</h3>
                    <div>Displayed Posts &lpar;<span class="post-count">{{ $posts->count() }}</span>&rpar;</div>
                    <div>Last Post -> {{ $posts->max('updated_at')->diffForHumans() }}</div>
                </div>
            </div>

            <button type="button" class="drawer-tab" title="Toggle Drawer">
                <img width="36" src="{{ asset('images/chevron_right_white.png') }}" alt=">">
            </button>
        </aside>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const drawer = document.querySelector('.drawer');
            
            document.querySelector('.drawer-tab').addEventListener('click', function() {
                drawer.classList.toggle('closed');
            });

            // Close drawer on small screens
            if (window.innerWidth >= 1024) {
                drawer.classList.remove('closed'); 
            }

            // Set date labels
            document.querySelectorAll('input[type="date"]').forEach(input => {
                const update = () => {
                    input.parentElement.classList.toggle('has-input', input.value !== '');
                };

                input.addEventListener('input', update);
                update(); // run once on page load
            });

            document.getElementById('filters-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const filter = formData.get('filter'); // 'title' or 'user'
                const query = formData.get('query')?.toLowerCase() || '';
                const startDate = formData.get('start-date');
                const endDate = formData.get('end-date');
                const tags = formData.getAll('tags[]');

                const posts = document.querySelectorAll('.post-card');
                let postCount = 0;

                posts.forEach(post => {
                    const title = post.dataset.title.toLowerCase();
                    const user = post.dataset.user.toLowerCase();
                    const postTags = post.dataset.tags.split(','); // ['6', '7']
                    const postDate = post.dataset.created;

                    // Check if query matches title or user
                    const queryMatch =
                        (filter === 'title' && title.includes(query)) ||
                        (filter === 'user' && user.includes(query));

                    // Check if post has at least one of the selected tags
                    const tagMatch =
                        tags.length === 0 || tags.every(tag => postTags.includes(tag));

                    // Date match
                    let dateMatch = true;
                    if (startDate && endDate) {
                        dateMatch = postDate >= startDate && postDate <= endDate;
                    } else if (startDate) {
                        dateMatch = postDate >= startDate;
                    } else if (endDate) {
                        dateMatch = postDate <= endDate;
                    }

                    // Hide all posts initially
                    post.style.display = 'none';

                    // Show based on matches
                    if (queryMatch && tagMatch && dateMatch) {
                        postCount++;
                        setTimeout(() => {
                            post.style.display = '';
                        }, timeout = 300);
                    }
                });

                document.querySelector('.post-count').textContent = postCount;
            });
            
        });

        function resetFilters() {
            let postCount = 0;

            // Reset to date labels
            document.querySelectorAll('input[type="date"]').forEach(input => {
                input.parentElement.classList.remove('has-input');
            });

            // Show All Posts
            document.querySelectorAll('.post-card').forEach(post => {
                post.style.display = 'none';
                postCount++;

                setTimeout(() => {
                    post.style.display = '';
                }, timeout = 300);
            });
            document.querySelector('.post-count').textContent = postCount;
        }
    </script>
@endsection

{{-- Lorem ipsum dolor, sit amet consectetur adipisicing elit. Obcaecati maiores quibusdam 
voluptatem suscipit quaerat excepturi illum dolore tempore, distinctio quasi nisi veritatis 
nam eaque sint quo eum recusandae dignissimos delectus? --}}