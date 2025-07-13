@props(['title' => 'Posts', 'action' => 'posts.display'])

<div class="sort-header">
    {{ $title }}
    <form method="GET" id="sort-form" class="sort-controls" action="{{ route($action) }}">
        <label for="sort-by">Sort by -></label>
        <select id="sort-by" name="sort" class="sort-select">
            <option value="created" {{ request('sort') === 'created' ? 'selected' : '' }}>Newest</option>
            <option value="comments" {{ request('sort') === 'comments' ? 'selected' : '' }}>Most Commented</option>
            <option value="likes" {{ request('sort') === 'likes' ? 'selected' : '' }}>Most Liked</option>
            <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Most Viewed</option>
        </select>
    </form>    
</div>