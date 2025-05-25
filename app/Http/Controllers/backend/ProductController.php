<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\HandlesImageUploads;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    use HandlesImageUploads;

    public ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }



    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::query();
            return DataTables::eloquent($products)
                ->addColumn('photo', function ($product) {
                    $imageUrl = isset($product->photo) ? asset($product->photo) : asset('backend/assets/dist/img/avatar5.png');
                    return '<img src="' . $imageUrl . '" alt="Photo" width="50" height="50">';
                })
                ->addColumn('supplier', function ($category) {
                    return $category->suppliers->name ; 
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
        $data['title'] = "Product";
        return view('backend.product.list', $data);
    }


    public function show($id)
    {
        $data['title'] = "Product Details";
        $data['product'] = $this->productService->find($id);
        return view('backend.product.view', $data);
    }


    public function create()
    {
        $data['title'] = "Product";
        $data['suppliers'] = $this->productService->suppliers();
        $data['categories'] =  $this->productService->categories();
        $data['units'] = $this->productService->units();
        return view('backend.product.create', $data);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $this->productService->createProduct($data);
        $notification = array(
            'message' => "Product Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.all.index')->with($notification);
    }



    public function edit(string $id)
    {
        $data['title'] = "Product Edit";
        $data['suppliers'] = $this->productService->suppliers();
        $data['categories'] =  $this->productService->categories();
        $data['units'] = $this->productService->units();
        $data['product'] = $this->productService->find($id);
        return view('backend.product.edit', $data);
    }


    public function update(ProductRequest $request, string $id)
    {
        $product = $this->productService->find($id);
        $data = $request->validated();
        $this->productService->update($data, $id);
        $notification = array(
            'message' => "Product Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.all.index')->with($notification);
    }


    public function destroy(string $id)
    {
        $this->productService->delete($id);
        $notification = array(
            'message' => "Product Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.all.index')->with($notification);
    }
}
