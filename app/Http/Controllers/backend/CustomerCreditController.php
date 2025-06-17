<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerCreditController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payments = Payment::whereNot('paid_status', 'full_paid')->latest();
            return DataTables::eloquent($payments)
                ->addColumn('name', function ($payment) {
                    return $payment->customer->name;
                })
                ->addColumn('invoice_id', function ($payment) {
                    return $payment->invoice_id;
                })
                ->addColumn('created_at', function ($payment) {
                    return Carbon::parse($payment->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($payment) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.invoice.all.show', $payment->invoice_id) . '">Invoice Details</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="' . route('admin.customer.history.index', ['id' => $payment->customer_id]) . '">Customer History</a>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'photo'])
                ->make(true);
        }
        $data['title'] = "Credit Customer";
        return view('backend.customerCredit.list', $data);
    }


    public function customerInvoiceDetail($id)
    {
        $data['title'] = "Invoice Details";
        $data['invoice'] = Invoice::with(['user', 'payment.customer', 'invoice_details.product.suppliers', 'invoice_details.category'])->findOrFail($id);
        $data['invoice_details'] = $data['invoice']->invoice_details->where('invoice_id', $id);

        return view('backend.invoice.view', $data);
    }
}
