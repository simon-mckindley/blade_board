@props(['title' => 'Posts'])

<div class="sort-header">
    {{ $title }}
    <div class="sort-controls">
        <label for="sort-by">Sort by -> </label>
        <select id="sort-by" class="sort-select">
            <option value="created">Newest</option>
            <option value="comments">Most Commented</option>
            <option value="likes">Most Liked</option>
            <option value="views">Most Viewed</option>
        </select>
    </div>
</div>