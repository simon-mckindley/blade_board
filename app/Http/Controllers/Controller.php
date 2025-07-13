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
                $query->orderBy('created_at', 'desc');
                break;
        }
    }
}
