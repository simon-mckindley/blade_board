<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tags');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:20|unique:tags,name',
        ]);

        try {
            $tag = Tag::create([
                'name' => $request->input('name'),
            ]);

            // Clear the cache for all tags
            Cache::forget('all_tags');

            return redirect()
                ->route('admin.tags.index')
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Tag created!'
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'There was a problem creating the tag: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'tag' => 'required|exists:tags,id', // Ensure a tag ID is selected
            'name-edit' => 'required|string|min:3|max:20|unique:tags,name,' . $request->input('tag'),
        ], [
            'name-edit.required' => 'The tag name is required.',
            'name-edit.min' => 'The name field must be at least 3 characters.',
            'name-edit.max' => 'The name field must be no more than 20 characters.',
            'name-edit.unique' => 'The tag name must be unique.',
        ]);

        try {
            Tag::whereId($request->input('tag'))->update([
                'name' => $request->input('name-edit'),
            ]);

            // Clear the cache for all tags
            Cache::forget('all_tags');

            return redirect()
                ->route('admin.tags.index')
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Tag updated!'
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', [
                    'type' => 'error',
                    'message' => 'There was a problem updating the tag: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        // Clear the cache for all tags
        Cache::forget('all_tags');

        return redirect()
            ->route('admin.tags.index')
            ->with('alert', [
                'type' => 'info',
                'message' => 'Tag deleted successfully!',
            ]);
    }
}
