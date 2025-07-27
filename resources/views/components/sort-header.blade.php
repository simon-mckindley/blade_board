@props(['title' => 'Posts'])

<h2>{{ $title }}</h2>
<div class="sort-controls">
    <label for="sort-by">Sort by -></label>
    <select id="sort-by" name="sort" class="sort-select" form="filters-form">
        <option value="created" {{ request('sort') === 'created' ? 'selected' : '' }}>Newest</option>
        <option value="comments" {{ request('sort') === 'comments' ? 'selected' : '' }}>Most Commented</option>
        <option value="likes" {{ request('sort') === 'likes' ? 'selected' : '' }}>Most Liked</option>
        <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Most Viewed</option>
    </select>
</div>