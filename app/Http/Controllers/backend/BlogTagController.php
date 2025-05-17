<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['user'] = Auth::user();
        $data['title'] = "Blog Tag";
        $data['tags'] = Tag::all();
        $data['selectedTags'] = Tag::pluck('name')->toArray();
        return view('backend.blog.tag.create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $data = $request->validate([
            'tag' => 'required|array',
            'tag.*' => 'required|string'
        ]);
        $prevTags = Tag::all();
        $inputTags = $data['tag'];
        // Tag::truncate();
        if (count($inputTags) >= count($prevTags)) {
            foreach ($data['tag'] as $tag) {
                Tag::firstOrCreate(['name' => $tag]);
            }
            $notification = array(
                'message' => "Tags Added Successfully !",
                'alert-type' => 'success'
            );
        } else {
            $filteredTags = $prevTags->filter(function ($tag) use ($inputTags) {
                return !in_array($tag->name, $inputTags);
            });
            foreach ($filteredTags as $tag) {
                foreach ($tag->blogPost as $post) {
                    $tagCount =  $post->tags()->count();
                    if ($tagCount == 1) {
                        $post->delete();
                    } else {
                        $post->tags()->detach($tag->id);
                    }
                }
                if ($tag->blogPost()->count() === 0) {
                    $tag->delete();
                }
            }
            $notification = array(
                'message' => "Tags Removed Successfully !",
                'alert-type' => 'success'
            );
        }

        return back()->with($notification);
    }
}
