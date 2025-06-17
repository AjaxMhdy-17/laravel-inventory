<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerHistoryController extends Controller
{
    public function index(Request $request, $id)
    {

        if ($request->ajax()) {
            $payments = Payment::where('customer_id', $id)->latest();
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
                                <a class="dropdown-item" href="' . route('admin.invoice.all.show', $payment->invoice_id) . '">Customer History</a>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'photo'])
                ->make(true);
        }
        $data['title'] = "Customer History";
        return view('backend.customerHistory.list', $data);
    }
}
