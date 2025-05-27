<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $purchases = Purchase::query();
            return DataTables::eloquent($purchases)
                // ->addColumn('photo', function ($product) {
                //     $imageUrl = isset($product->photo) ? asset($product->photo) : asset('backend/assets/dist/img/avatar5.png');
                //     return '<img src="' . $imageUrl . '" alt="Photo" width="50" height="50">';
                // })
                // ->addColumn('supplier', function ($product) {
                //     $supplierName = optional($product->supplier)->name ?? 'N/A';
                //     return "<span>{$supplierName}</span>";
                // })

                ->addColumn('status', function ($purchase) {
                    return $purchase->status == 1 ? "<span class='badge badge-primary'>Approved</span>" : " <span class='badge badge-danger'>Pending</span>";
                })
                ->addColumn('created_at', function ($product) {
                    return Carbon::parse($product->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($purchase) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.product.purchase.show', ['purchase' => $purchase->id]) . '">View</a>
                                <div class="dropdown-divider"></div>
                                 <form class="delete-form" method="POST" action="' . route('admin.product.purchase.destroy', ['purchase' => $purchase->id]) . '">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                     <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                                </form>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'status', 'category'])
                ->make(true);
        }
        $data['title'] = "Purchase";
        return view('backend.purchase.list', $data);
    }


    public function getCategory(Request $request)
    {
        $id = $request->supplier_id;
        $supplier = Supplier::findOrFail($id);
        return response()->json(
            $supplier->categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name
            ])
        );
    }


    public function getProduct(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        return response()->json(
            $category->product->map(fn($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->quantity,
            ])
        );
    }


    public function create()
    {
        $data['title'] = "Purchase";
        $data['suppliers'] = Supplier::all();
        $data['categories'] = Category::all();
        $data['products'] = Product::all();
        $data['randNumber'] = randNumber();
        return view('backend.purchase.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'purchase_items' => 'required|array|min:1',
            'purchase_items.*.supplier_id' => 'required|exists:suppliers,id',
            'purchase_items.*.product_id' => 'required|exists:products,id',
            'purchase_items.*.category_id' => 'required|exists:categories,id',
            'purchase_items.*.supplier' => 'required|string',
            'purchase_items.*.category' => 'required|string',
            'purchase_items.*.product' => 'required|string',
            'purchase_items.*.unit' => 'required|numeric|min:1',
            'purchase_items.*.unit_price' => 'required|numeric|min:0',
            'purchase_items.*.price' => 'required|numeric|min:0',
            'purchase_items.*.description' => 'nullable|string',
        ], [
            'purchase_items.required' => 'You must add at least one purchase item.',
            'purchase_items.*.supplier_id.required' => 'required.',
            'purchase_items.*.category_id.exists' => 'Invalid category selected.',
            'purchase_items.*.product_id.exists' => 'Invalid product selected.',
            'purchase_items.*.category.required' => 'sometimes.',
            'purchase_items.*.product.required' => 'sometimes.',
            'purchase_items.*.supplier.required' => 'sometimes.',
            'purchase_items.*.unit.required' => 'required.',
            'purchase_items.*.unit_price.required' => 'required.',
            'purchase_items.*.price.required' => 'required.',
            // Description is nullable, so no required message needed
        ]);



        foreach ($data['purchase_items'] as $idx => $item) {

            $purchase = [
                'user_id' => Auth::user()->id,
                'product_id' => $item['product_id'],
                'category_id' =>  $item['category_id'],
                'supplier_id' =>  $item['supplier_id'],
                'supplier' => $item['supplier'],
                'category' =>  $item['category'],
                'product' =>  $item['product'],
                'purchase_no' => randNumber(),
                'description' =>  $item['description'],
                'buying_qty' =>  $item['unit'],
                'unit_price' => $item['unit_price'],
                'price' =>  $item['price'],
            ];
            Purchase::create($purchase);
        }

        $notification = array(
            'message' => "Purchase Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.purchase.index')->with($notification);
    }



    public function show($id)
    {
        $data['title'] = "Purchase Details";

        $data['purchase'] = Purchase::with('user')->findOrFail($id);

        return view('backend.purchase.view', $data);
    }


    public function statusAction($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->status == 0 ? $purchase['status'] = 1 : $purchase['status'] = 0;
        $purchase->save();
        $notification = array(
            'message' => "Product Updated Successfully !",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }


    // public function edit(string $id)
    // {
    //     $data['title'] = "Product Edit";
    //     // $data['suppliers'] = $this->productService->suppliers();
    //     // $data['categories'] =  $this->productService->categories();
    //     // $data['units'] = $this->productService->units();
    //     // $data['product'] = $this->productService->find($id);
    //     return view('backend.product.edit', $data);
    // }


    // public function update(Request $request, string $id)
    // {
    //     // $product = $this->productService->find($id);
    //     $data = $this->validateData($request);
    //     // $this->productService->update($data, $id);
    //     $notification = array(
    //         'message' => "Product Updated Successfully !",
    //         'alert-type' => 'success'
    //     );
    //     return redirect()->route('admin.product.index')->with($notification);
    // }


    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();
        $notification = array(
            'message' => "Purchase Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.product.purchase.index')->with($notification);
    }
}
