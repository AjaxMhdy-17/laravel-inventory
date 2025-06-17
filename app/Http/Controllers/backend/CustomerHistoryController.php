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
            return customerTable($payments);
        }
        $data['title'] = "Customer History";
        return view('backend.customerHistory.list', $data);
    }
}
