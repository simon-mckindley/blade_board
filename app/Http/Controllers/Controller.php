<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function postSortOrder ($query, $sort)
    {
        if ($sort === 'views') {
            $query->withCount('viewers');
        }

        switch ($sort) {
            case 'comments':
                $query->orderBy('comments_count', 'desc');
                break;
            case 'likes':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'views':
                $query->orderBy('viewers_count', 'desc');
                break;
            default:
                $query->orderBy('posts.created_at', 'desc');
                break;
        }
    }

    public function postFilter ($query, $request) {
        // Filter by title/user
        if ($request->filled('query') && $request->filled('filter')) {
            $term = strtolower($request->input('query'));
            $filter = $request->input('filter');

            $query->whereHas('user', function ($q) use ($filter, $term) {
                if ($filter === 'user') {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
                }
            });

            if ($filter === 'title') {
                $query->whereRaw('LOWER(title) LIKE ?', ["%{$term}%"]);
            }
        }

        // Filter by tag
        if ($request->filled('tags')) {
            $tagIds = $request->input('tags');
            foreach ($tagIds as $tagId) {
                $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('tags.id', $tagId);
                });
            }
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('posts.created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('posts.created_at', '<=', $request->input('end_date'));
        }
    }
}
