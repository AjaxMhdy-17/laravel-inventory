<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitCategoryRequest;
use App\Models\Category;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::query()->latest();
            return DataTables::eloquent($categories)
                ->addColumn('status', function ($category) {
                    return $category->status == 1 ? "<span class='badge badge-primary'>Active</span>" : " <span class='badge badge-danger'>In Active</span>";
                })
                ->addColumn('created_at', function ($category) {
                    return Carbon::parse($category->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($category) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.customer.category.edit', $category->id) . '">Edit</a>
                                <div class="dropdown-divider"></div>
                                 <form class="delete-form" method="POST" action="' . route('admin.customer.category.destroy', ['category' => $category->id]) . '">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                     <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                                </form>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $data['title'] = "Category";
        return view('backend.category.list', $data);
    }


    public function create()
    {
        $data['title'] = "Category";
        return view('backend.category.create', $data);
    }

    public function store(UnitCategoryRequest $request)
    {
        $data = $request->validated();
        if (isset($data['status'])) {
            $data['status'] = $data['status'] == 'on' ? 1 : 0;
        }
        Category::create($data);
        $notification = array(
            'message' => "Category Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.category.index')->with($notification);
    }



    public function edit(string $id)
    {
        $data['title'] = "Category";
        $data['category'] = Category::findOrFail($id);
        return view('backend.category.edit', $data);
    }


    public function update(UnitCategoryRequest $request, string $id)
    {
        $data = $request->validated();

        if (isset($data['status'])) {
            $data['status'] = $data['status'] == 'on' ? 1 : 0;
        }
        $category = Category::findOrFail($id);
        $category->update($data);
        $notification = array(
            'message' => "Category Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.category.index')->with($notification);
    }


    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        $notification = array(
            'message' => "Category Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.category.index')->with($notification);
    }
}
