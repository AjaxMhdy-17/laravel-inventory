<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\Product;
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
            $invoices = Invoice::with(['user', 'invoice_details'])->orderBy('created_at', 'desc');
            return $this->table($invoices);
        }
        $data['title'] = "Invoice";
        return view('backend.invoice.list', $data);
    }

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
            'totalPrice' => ['required', 'numeric', 'min:0'],
            'discountPrice' => [
                'required',
                'numeric',
                'min:0',
                // function ($attribute, $value, $fail) use ($request) {
                //     if ($value > $request->input('totalPrice')) {
                //         $fail('Discount cannot be greater than total price.');
                //     }
                // }
            ],
            'paid_status' => 'required',
            'paid_amount' => [
                Rule::when($request->paid_status === 'partial_paid', ['required', 'numeric', 'min:0']),
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->paid_status === 'partial_paid') {
                        $total = floatval($request->input('totalPrice'));
                        $discount = floatval($request->input('discountPrice'));
                        $paid = floatval($value);

                        $netTotal = $total;
                        if ($paid > $netTotal) {
                            $fail('Paid amount cannot be greater than total price after discount.');
                        }
                    }
                }
            ],
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
            'purchase_items.*.unit.min' => 'this unit is 0.',
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
        $data['title'] = "Invoice Details";
        $data['invoice'] = Invoice::with(['user', 'payment.customer', 'invoice_details.product.suppliers', 'invoice_details.category'])->findOrFail($id);
        $data['invoice_details'] = $data['invoice']->invoice_details->where('invoice_id', $id);

        return view('backend.invoice.view', $data);
    }

    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        InvoiceDetail::where('invoice_id', $invoice->id)->delete();
        Payment::where('invoice_id', $invoice->id)->delete();
        $notification = array(
            'message' => "Invoice Deleted Successfully !",
            'alert-type' => 'danger'
        );
        return back()->with($notification);
    }


    public function approveInvoice(Request $request,  $id)
    {

        $data = $request->validate([
            'due_paid_amount' => 'sometimes',
            'isDelivered' => 'sometimes',
        ]);

        $invoice = Invoice::with(['invoice_details', 'payment'])->findOrFail($id);
        $due_amount = (int) $invoice->payment->due_amount - (int) $data['due_paid_amount'];

        if ($due_amount < 0) {
            $notification = array(
                'message' => "Please Enter Amount Carefully !",
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
        $message = "Payment Successful!";
        DB::transaction(function () use ($invoice, $data, $due_amount, $id) {

            $ids = $invoice->invoice_details->pluck('product_id');
            $products = Product::whereIn('id', $ids)->orderBy('id')->get();
            $invoice_details = $invoice->invoice_details->sortBy('product_id')->values();

            if ($invoice->isDelivered == 0 && $data['isDelivered'] == "true") {

                foreach ($products as $idx => $product) {
                    if ((int)$product['quantity'] < (int)$invoice_details[$idx]['selling_qty']) {
                        $notification = array(
                            'message' => "Insufficient Stock !",
                            'alert-type' => 'warning'
                        );
                        return back()->with($notification);
                    }
                    $product['quantity'] = (string)((int)$product['quantity'] - (int) $invoice_details[$idx]['selling_qty']);
                    $product->save();
                }
            }

            $invoice->payment->paid_amount = (int) $invoice->payment->paid_amount + (int) $data['due_paid_amount'];
            $invoice->payment->current_paid_amount = (int) $invoice->payment->current_paid_amount + (int) $data['due_paid_amount'];
            $invoice->payment->due_amount = $due_amount;
            $delivered = $data['isDelivered'] === 'true' ? 1 : 0;
            $invoice->isDelivered = $delivered;
            $invoice->save();
            $invoice->payment->save();
            $invoice = Invoice::with('payment')->findOrFail($id);
            $flag = $invoice->payment->paid_amount == $invoice->payment->total_amount;
            if ($flag == true) {
                $invoice->status = 1;
                $invoice->save();
                $invoice->payment->update([
                    "paid_status" => "full_paid"
                ]);
                $message = "Invoice Approved Successfully !";
            }
        });

        $notification = array(
            'message' => $message,
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }

    public function pendingInvoice(Request $request)
    {
        if ($request->ajax()) {
            $invoices = Invoice::with(['user', 'invoice_details'])->orderBy('created_at', 'desc')->where('status', 1);
            return $this->table($invoices);
        }
        $data['title'] = "Pending Invoice";
        return view('backend.invoice.paidList', $data);
    }

    public function approvedInvoice(Request $request)
    {
        if ($request->ajax()) {
            $invoices = Invoice::with(['user', 'invoice_details'])->orderBy('created_at', 'desc')->where('status', 0);
            return $this->table($invoices);
        }
        $data['title'] = "Paid Invoice";
        return view('backend.invoice.pendingList', $data);
    }


    public function dailyInvoiceForm()
    {
        $data['title'] = "Range Invoice";
        return view('backend.invoice.dailyInvoiceForm', $data);
    }


    public function dailyInvoice(Request $request)
    {
        $data = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $data['invoices'] = Invoice::with(['invoice_details', 'payment.customer'])->whereBetween('created_at', [$data['start_date'], $data['end_date']])->get();
        $data['title'] = "Range Invoice";
        return view('backend.invoice.daily', $data);
    }



    public function table($invoices)
    {
        return DataTables::eloquent($invoices)
            ->addIndexColumn()
            ->addColumn('name', function ($invoice) {
                return $invoice->user->name ?? 'N/A';
            })
            ->addColumn('invoice_description', function ($invoice) {
                return $invoice->invoice_description ?? 'N/A';
            })
            ->addColumn('created_at', function ($product) {
                return Carbon::parse($product->created_at)->format('Y-m-d');
            })
            ->addColumn('status', function ($invoice) {
                return $invoice->status == 1 ? "<span class='badge badge-primary'>Paid</span>" : " <span class='badge badge-danger'>Not Paid</span>";
            })
            ->addColumn('action', function ($invoice) {
                return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.invoice.all.show', ['all' => $invoice->id]) . '">View</a>
                                <div class="dropdown-divider"></div>
                                 <form class="delete-form" method="POST" action="' . route('admin.invoice.all.destroy', ['all' => $invoice->id]) . '">
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
}
