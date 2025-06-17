<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerPaidController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payments = Payment::where('paid_status', 'full_paid')->latest();
            return customerTable($payments);
        }
        $data['title'] = "Credit Paid";
        return view('backend.customerPaid.list', $data);
    }
}
