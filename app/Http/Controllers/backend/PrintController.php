<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function invoicePrint(Request $request , $id)
    {
        $data['title'] = "Print Invoice";
        $data['invoice'] = Invoice::with(['user', 'payment.customer', 'invoice_details.product.suppliers', 'invoice_details.category'])->findOrFail($id);
        $data['invoice_details'] = $data['invoice']->invoice_details->where('invoice_id', $id);


        return view('backend.pdf.invoice_pdf', $data);
    }
}
