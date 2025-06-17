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
            return customerTable($payments);
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
