<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::query();
            return DataTables::eloquent($products)
                ->addIndexColumn()
                ->addColumn('photo', function ($product) {
                    $imageUrl = isset($product->photo) ? asset($product->photo) : asset('backend/assets/dist/img/avatar5.png');
                    return '<img src="' . $imageUrl . '" alt="Photo" width="50" height="50">';
                })
                ->addColumn('supplier', function ($category) {
                    return $category->suppliers->name;
                })
                ->addColumn('category', function ($product) {
                    $categoryName = optional($product->category)->name ?? 'N/A';
                    return "<span>{$categoryName}</span>";
                })
                ->addColumn('unit', function ($product) {
                    $unitName = optional($product->unit)->name ?? 'N/A';
                    return "<span>{$unitName}</span>";
                })
                ->addColumn('created_at', function ($product) {
                    return Carbon::parse($product->created_at)->format('Y-m-d');
                })
                ->addColumn('status', function ($product) {
                    return $product->status == 1 ? "<span class='badge badge-primary'>Active</span>" : " <span class='badge badge-danger'>In Active</span>";
                })
                ->addColumn('action', function ($product) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.product.all.show', ['all' => $product->id]) . '">View</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="' . route('admin.product.all.edit', ['all' => $product->id]) . '">Edit</a>
                                <div class="dropdown-divider"></div>
                                 <form class="delete-form" method="POST" action="' . route('admin.product.all.destroy', ['all' => $product->id]) . '">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                     <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                                </form>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'status', 'photo', 'supplier', 'unit', 'category'])
                ->make(true);
        }
        $data['title'] = "Invoice";
        return view('backend.invoice.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = "Invoice";
        $data['categories'] = Category::all();
        $data['products'] = Product::all();
        $data['randNumber'] = randNumber();
        return view('backend.invoice.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
