<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function store(Request $request)
    {
        $val = $request->validate([
            'category_id' => 'sometimes',
            'customer_id' => 'sometimes',
            'product_id' => 'sometimes',
            'invoice_description' => 'sometimes',
            'totalPrice' => 'required',
            'discountPrice' => 'required',
            'paid_status' => 'required',
            'paid_amount' => Rule::when($request->paid_status === 'partial_paid', 'required', 'sometimes'),
            'name' => 'sometimes',
            'email' => 'sometimes',
            'phone' => 'sometimes',
        ], [
            'totalPrice.required' => 'required',
            'discountPrice.required' => 'required',
            'paid_status.required' => 'Please Select A Payment Status',
            'paid_amount.required' => 'Please Enter Partial Paid Amount',
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

        $invoice = new Invoice();
        $invoice->user_id = Auth::user()->id;
        $invoice->invoice_no = randNumber();
        $invoice->invoice_description = $val['invoice_description'];
        $invoice->status = 0;
        DB::transaction(function () use ($val, $data, $invoice) {
            if ($invoice->save()) {
                foreach ($data['purchase_items'] as $idx => $item) {
                    $invoice_detail = new InvoiceDetail();
                    $invoice_detail->status = 0;
                    $invoice_detail->product_id = $item['product_id'];
                    $invoice_detail->category_id = $item['category_id'];
                    $invoice_detail->invoice_id =  $invoice->id;
                    $invoice_detail->selling_qty = $item['unit'];
                    $invoice_detail->unit_price =  $item['unit_price'];
                    $invoice_detail->selling_price =  $item['price'];
                    $invoice_detail->description =  $item['description'];
                    $invoice_detail->save();
                }
                if ($val['customer_id'] == 0) {
                    $customer = new Customer();
                    $customer->name = $val['name'];
                    $customer->email = $val['email'];
                    $customer->phone = $val['phone'];
                    $customer->save();
                    $customer_id = $customer->id;
                } else {
                    $customer_id = $val['customer_id'];
                }
                $payment = new Payment();
                $payment->invoice_id = $invoice->id;
                $payment->customer_id = $customer_id;
                $payment->total_amount = $val['totalPrice'];
                $payment->discount_amount = $val['discountPrice'];

                $payment->total_amount_after_discount = $val['totalPrice'] - $val['discountPrice'];

                if ($val['paid_status'] == 'partial_paid') {
                    $payment->paid_status = "partial_paid";
                    $payment->paid_amount = $val['paid_amount'];
                    $payment->current_paid_amount = $val['paid_amount'];
                    $payment->due_amount = $val['totalPrice'] - $val['paid_amount'];
                } else if ($val['paid_status'] == 'full_paid') {
                    $payment->paid_status = "full_paid";
                    $payment->due_amount = 0;
                    $payment->paid_amount = $val['totalPrice'];
                    $payment->current_paid_amount = $val['totalPrice'];
                } else if ($val['paid_status'] == 'full_due') {
                    $payment->paid_status = "full_due";
                    $payment->due_amount = $val['totalPrice'];
                    $payment->paid_amount = 0;
                    $payment->current_paid_amount = 0; 
                }
                $payment->save();
            }
        });
        $notification = array(
            'message' => "Invoice Added Successfully !",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }

    public function validateData($request) {}


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
