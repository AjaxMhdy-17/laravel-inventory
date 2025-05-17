<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Fact;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::query();
            return DataTables::eloquent($categories)
                ->addColumn('created_at', fn($category) => Carbon::parse($category->created_at)->format('Y-m-d'))
                ->addColumn('action', function ($category) {
                    return '
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                            More
                        </button>
                        <div class="dropdown-menu">
                            <form class="delete-form" method="POST" action="' . route('admin.blog.category.destroy', ['category' => $category->id]) . '">
                                ' . csrf_field() . method_field("DELETE") . '
                                <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                            </form>
                        </div>
                    </div>
                ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['user'] = Auth::user();
        $data['title'] = "Blog Category";
        $data['categories'] = Category::all();
        return view('backend.blog.category.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['user'] = Auth::user();
        $data['title'] = "Catgory Section";
        $data['fact'] = Fact::first();
        return view('backend.blog.category.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|unique:categories'
        ], [
            'name' => 'Please add category name',
            'name.unique' => 'Category name already exists'
        ]);
        Category::create($data);
        $notification = array(
            'message' => "Tags Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.blog.category.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id) ; 
        $category->delete() ; 
        $notification = array(
            'message' => "Category Deleted Successfully !",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
}
