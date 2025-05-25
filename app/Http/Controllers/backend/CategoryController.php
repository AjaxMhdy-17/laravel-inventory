<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::with('suppliers')->latest();

            return DataTables::eloquent($categories)
                ->addColumn('supplier', function ($category) {
                    // Assuming 'suppliers' is a relationship in Category model
                    return $category->suppliers->pluck('name')->implode(', ');
                })
                ->addColumn('created_at', function ($category) {
                    return Carbon::parse($category->created_at)->format('Y-m-d');
                })
                ->addColumn('status', function ($product) {
                    return $product->status == 1 ? "<span class='badge badge-primary'>Active</span>" : " <span class='badge badge-danger'>In Active</span>";
                })
                ->addColumn('action', function ($category) {
                    return '
                   <div class="action">
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            More
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('admin.product.category.edit', $category->id) . '">Edit</a>
                            <div class="dropdown-divider"></div>
                             <form class="delete-form" method="POST" action="' . route('admin.product.category.destroy', ['category' => $category->id]) . '">
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
        $data['title'] = "Category Create";
        $data['suppliers'] = Supplier::all();
        return view('backend.category.create', $data);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        isset($data['status']) ?  $data['status'] = 1 : $data['status'] = 0;
        $id = $data['supplier_id'];
        unset($data['supplier_id']);
        $cleandInput = cleanWithoutcharAndNumber($data['name']);
        $category = Category::firstOrCreate(
            ['cleaned_name' => $cleandInput],
            array_merge($data, ['cleaned_name' => $cleandInput])
        );
        $category->suppliers()->syncWithoutDetaching($id);
        $notification = array(
            'message' => "Category Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.category.index')->with($notification);
    }



    public function edit(string $id)
    {
        $data['title'] = "Category Edit";
        $data['category'] = Category::findOrFail($id);
        $data['suppliers'] = Supplier::all();
        return view('backend.category.edit', $data);
    }


    public function update(Request $request, string $id)
    {

        $data = $this->validateData($request);
        isset($data['status']) ?  $data['status'] = 1 : $data['status'] = 0;
        $supplierId = $data['supplier_id'];
        unset($data['supplier_id']);
        $category = Category::findOrFail($id);
        $category->update($data);
        $category->suppliers()->sync($supplierId);
        $notification = array(
            'message' => "Category Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.category.index')->with($notification);
    }


    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        $notification = array(
            'message' => "Category Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.category.index')->with($notification);
    }

    public function validateData($request)
    {
        return $request->validate([
            'name' => 'required',
            'status' => 'nullable',
            'supplier_id' => 'required'
        ]);
    }
}
