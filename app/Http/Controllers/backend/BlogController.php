<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Gate;
use App\Traits\HandlesImageUploads;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    use HandlesImageUploads;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $posts = BlogPost::query();
            return DataTables::eloquent($posts)
                ->addColumn('category', function ($post) {
                    return $post->category->name;
                })
                ->addColumn('created_at', function ($post) {
                    return Carbon::parse($post->created_at)->format('Y-m-d');
                })
                ->addColumn('photo', function ($post) {
                    $imageUrl = isset($post->photo) ? asset($post->photo) : asset('backend/assets/dist/img/avatar5.png');
                    return '<img src="' . $imageUrl . '" alt="Photo" width="50" height="50">';
                })
                ->addColumn('status', function ($post) {
                    return $post->status == 1 ? "<span class='badge badge-primary'>Active</span>" : " <span class='badge badge-danger'>In Active</span>";
                })
                ->addColumn('action', function ($post) {
                    $actions = '<div class="action">
                    <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        More
                    </button>
                    <div class="dropdown-menu">';


                    $actions .= '<a class="dropdown-item" href="' . route('admin.blog.post.show', $post->id) . '">Details</a>';


                    if (Auth::user()->can('update', $post)) {
                        $actions .= '<div class="dropdown-divider"></div>';
                        $actions .= '<a class="dropdown-item" href="' . route('admin.blog.post.edit', $post->id) . '">Edit</a>';
                    }

                    if (Auth::user()->can('delete', $post)) {
                        $actions .= '<div class="dropdown-divider"></div>';
                        $actions .= '<form class="delete-form" method="POST" action="' . route('admin.blog.post.destroy', ['post' => $post->id]) . '">
                        ' . csrf_field() . '
                        ' . method_field("DELETE") . '
                        <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                    </form>';
                    }

                    $actions .= '</div></div></div>';

                    return $actions;
                })

                ->rawColumns(['action', 'status', 'photo'])
                ->make(true);
        }
        $data['user'] = Auth::user();
        $data['title'] = "Posts";
        return view('backend.blog.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['user'] = Auth::user();
        $data['title'] = "Post Create";
        $data['categories'] = Category::get();
        $data['tags'] = Tag::all();
        return view('backend.blog.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $tags = $data['tag'];
        unset($data['tag']);
        if (isset($data['photo'])) {
            $imagePath = $this->uploadImage($data['photo'], 'upload/admin/blog/post/', 600, 600);
            $data['photo'] = $imagePath;
        }
        $data['status'] = isset($data['status']) && $data['status'] === 'on' ? 1 : 0;
        $data['user_id'] = Auth::user()->id;
        $post = BlogPost::create($data);
        $post->tags()->sync($tags);
        $notification = array(
            'message' => "Post Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.post.index')->with($notification);
    }


    public function show(string $id)
    {
        $data['user'] = Auth::user();
        $data['title'] = "Post Details";
        $data['post'] = BlogPost::findOrFail($id);
        return view('backend.blog.details', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $data['post'] = BlogPost::findOrFail($id);
        $data['user'] = Auth::user();

        // if($data['post']->user->id != $data['user']->id){
        //     abort(403) ; 
        // }


        $this->authorize('post.edit', $data['post']);

        $data['title'] = "Post Edit";
        $data['categories'] = Category::get();
        $data['tags'] = Tag::all();
        return view('backend.blog.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        $post = BlogPost::findOrFail($id);
        $data = $request->validated();

        $tags = $data['tag'];
        unset($data['tag']);
        if (isset($data['photo'])) {
            $this->deleteImage($post->photo);
            $imagePath = $this->uploadImage($data['photo'], 'upload/admin/blog/post/', 600, 600);
            $data['photo'] = $imagePath;
        }
        $data['status'] = isset($data['status']) && $data['status'] === 'on' ? 1 : 0;
        $post->update($data);
        $post->tags()->sync($tags);
        $notification = array(
            'message' => "Post Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.post.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize('post.delete', $post);
        $post->delete();
        $notification = array(
            'message' => "Post Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.post.index')->with($notification);
    }



    public function bulkDelete(Request $request)
    {
        $idsString = $request->input('ids');
        $ids = array_filter(explode(',', $idsString));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No items selected for deletion.');
        }
        $posts = BlogPost::whereIn('id', $ids)->get();
        foreach ($posts as $post) {
            $this->deleteImage($post->photo);
        }
        BlogPost::whereIn('id', $ids)->delete();
        $notification = array(
            'message' => "Selected Posts Deleted Successfully !",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
}
