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
                                <a class="dropdown-item" href="' . route('admin.invoice.all.show', $payment->invoice_id) . '">View</a>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'photo'])
                ->make(true);
        }
        $data['title'] = "Credit Paid";
        return view('backend.customerPaid.list', $data);
    }
}
