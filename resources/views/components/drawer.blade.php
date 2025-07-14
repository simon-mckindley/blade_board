
<aside class="drawer closed">
    <div class="drawer-content">
        <form id="filters-form" class="filters" method="GET" action="{{ route($action) }}">
            <h3>Filters</h3>
            <div class="filter-inputs">
                <div class="radio-inputs">
                    <input type="radio" name="filter" id="title" value="title" {{ request('filter') === 'title' ? 'checked' : '' }} checked>
                    <label for="title" tabindex="0">Title</label>
                    @if ($needsUser)
                    <input type="radio" name="filter" value="user" id="user" {{ request('filter') === 'user' ? 'checked' : '' }}>
                    <label for="user" tabindex="0">User</label>
                    @endif
                </div>
                <input type="text" name="query" value="{{ request('query') }}" placeholder="Filter posts...">
            </div>

            
            <div class="filter-inputs date-inputs">
                <div>Date Range</div>
                <div>
                    <label class="date-label" for="start-date">From</label>
                    <input id="start-date" type="date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label class="date-label" for="end-date">To</label>
                    <input id="end-date" type="date" name="end_date" value="{{ request('end_date') }}">
                </div>
            </div>

            <div class="filter-inputs">
                <div>Tags</div>
                <div class="tags-cont">
                    @foreach ($tags as $tag)
                        <input type="checkbox" name="tags[]" id="{{ $tag->name }}" value="{{ $tag->id }}" 
                            {{ in_array($tag->id, request('tags', [])) ? 'checked' : '' }}>
                        <label class="tag-input" for="{{ $tag->name }}">{{ $tag->name }}</label>
                    @endforeach
                </div>
            </div>

            <div class="filter-btns">
                <button class="btn submit-btn" type="reset" onclick="window.location.href='{{ route('posts.display') }}'">
                    Clear</button>
                <button class="btn submit-btn" type="submit">Go</button>
            </div>
        </form>

        <div class="stats">
            <div>Displayed Posts &lpar;<span class="post-count">{{ $posts->total() }}</span>&rpar;</div>
            @if (!$posts->isEmpty())
            <div>Last Post -> {{ $posts->max('updated_at')->diffForHumans() }}</div>
            @endif
        </div>
    </div>

    <button type="button" class="drawer-tab" title="Toggle Drawer">
        <img width="36" src="{{ asset('images/chevron_right_white.png') }}" alt=">">
    </button>
</aside>