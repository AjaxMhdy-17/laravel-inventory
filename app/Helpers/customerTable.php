 <?php

    use Carbon\Carbon;
    use Yajra\DataTables\Facades\DataTables;

    function customerTable($payments)
    {
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
