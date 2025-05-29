<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
        $data['customers'] = Customer::all();
        $data['randNumber'] = randNumber();
        return view('backend.invoice.create', $data);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $val = $request->validate([
            'category_id' => 'sometimes',
            'customer_id' => 'sometimes',
            'product_id' => 'sometimes',
            'invoice_description' => 'sometimes',
            'paid_status' => 'required',
            'paid_amount' => Rule::when($request->paid_status === 'partial_paid', 'required', 'sometimes'),
        ], [
            // 'category_id.required' => 'Please Select A Category',
            // 'product_id.required' => 'Please Select A Product',
            // 'customer_id.required' => 'Please Select A Customer Or Create A New One',
            'paid_status.required' => 'Please Select A Payment Status',
            'paid_amount.required' => 'Please Enter Partial Paid Amount',
        ]);


//         id	user_id	invoice_no	invoice_description	status
// 0 => pending , 1 => approved	created_at	updated_at	

        $invoice = Invoice::create([
            'user_id' => Auth::user()->id , 
            'invoice_no' => randNumber(), 
            'invoice_description' => $val['invoice_description'] , 
            
        ]);


        $data = $request->validate([
            'purchase_items' => 'required|array|min:1',
            'purchase_items.*.product_id' => 'required|exists:products,id',
            'purchase_items.*.category_id' => 'required|exists:categories,id',
            'purchase_items.*.category' => 'required|string',
            'purchase_items.*.product' => 'required|string',
            'purchase_items.*.unit' => 'required|numeric|min:1',
            'purchase_items.*.unit_price' => 'required|numeric|min:0',
            'purchase_items.*.price' => 'required|numeric|min:0',
            'purchase_items.*.description' => 'nullable|string',
        ], [
            'purchase_items.required' => 'You must add at least one purchase item.',
            'purchase_items.*.category_id.exists' => 'Invalid category selected.',
            'purchase_items.*.product_id.exists' => 'Invalid product selected.',
            'purchase_items.*.category.required' => 'sometimes.',
            'purchase_items.*.product.required' => 'sometimes.',
            'purchase_items.*.unit.required' => 'required.',
            'purchase_items.*.unit_price.required' => 'required.',
            'purchase_items.*.price.required' => 'required.',
        ]);

        dump($val) ; 
        dd($data);




        $notification = array(
            'message' => "Please Select All Fields!",
            'alert-type' => 'danger'
        );
        return back()->with($notification);

        // if ($val['category_id'] == null) {
        //     $notification = array(
        //         'message' => "Please Select All Fields!",
        //         'alert-type' => 'danger'
        //     );
        //     return back()->with($notification);
        // }

        // dd();

        // $data = $request->validate([
        //     'category_id' => 'required',
        //     'product_id' => 'required',
        //     'purchase_id' => 'required',
        //     'invoice_description' => 'sometimes',
        //     'paid_status' => 'required',
        //     'paid_amount' => 'sometimes',
        //     'customer_id' => 'sometimes',
        // ]);
        // dd($data);
    }

    public function validateData($request) {}

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
