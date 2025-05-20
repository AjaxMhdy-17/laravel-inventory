<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitCategoryRequest;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Unit::query()->latest();
            return DataTables::eloquent($products)
                ->addColumn('status', function ($product) {
                    return $product->status == 1 ? "<span class='badge badge-primary'>Active</span>" : " <span class='badge badge-danger'>In Active</span>";
                })
                ->addColumn('created_at', function ($product) {
                    return Carbon::parse($product->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($product) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.product.edit', $product->id) . '">Edit</a>
                                <div class="dropdown-divider"></div>
                                <form class="delete-form" method="POST" action="' . route('admin.product.destroy', ['product' => $product->id]) . '">
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
        $data['title'] = "Product";
        return view('backend.product.list', $data);
    }

    public function create()
    {
        $data['title'] = "Product";
        return view('backend.product.create', $data);
    }

    public function store(UnitCategoryRequest $request)
    {
        $data = $request->validated();
        if (isset($data['status'])) {
            $data['status'] = $data['status'] == 'on' ? 1 : 0;
        }
        Unit::create($data);
        $notification = array(
            'message' => "Unit Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.index')->with($notification);
    }



    public function edit(string $id)
    {
        $data['title'] = "Unit";
        $data['unit'] = Unit::findOrFail($id);
        return view('backend.product.edit', $data);
    }


    public function update(UnitCategoryRequest $request, string $id)
    {
        $data = $request->validated();

        if (isset($data['status'])) {
            $data['status'] = $data['status'] == 'on' ? 1 : 0;
        }
        $unit = Unit::findOrFail($id);
        $unit->update($data);
        $notification = array(
            'message' => "Unit Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.index')->with($notification);
    }


    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        $notification = array(
            'message' => "Unit Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.index')->with($notification);
    }
}
