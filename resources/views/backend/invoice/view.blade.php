@extends('backend.main.layout')

@section('title', $title)

@section('content')
    <div>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card__header">
                                <h3 class="card-title">{{ $title }}</h3>
                                <div>
                                    Invoice Number : {{ $invoice->invoice_no }}
                                </div>
                                <div>
                                    {!! $invoice->status == 0 || $invoice->isDelivered == 0
                                        ? '<a href="#" class="btn btn-info mr-3 show-alert"
                                                                                                                                                                                                                                                                                                                                            data-invoice-id="' .
                                            $invoice->id .
                                            '"
                                                                                                                                                                                                                                                                                                                                            data-is-delivered="' .
                                            $invoice->isDelivered .
                                            '"> Approve </a>'
                                        : '<a href="#" class="btn btn-info mr-3">Already Approved</a>' !!}

                                    <form id="print-form-{{ $invoice->id }}"
                                        action="{{ route('admin.invoice.approve.invoice', ['id' => $invoice->id]) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="due_paid_amount" value="0">

                                        {{-- always include input, default false --}}
                                        <input type="hidden" name="isDelivered"
                                            value="{{ $invoice->isDelivered == 0 ? 'false' : 'true' }}">
                                    </form>


                                    <a href="{{ route('admin.invoice.all.index') }}" class="btn btn-primary">Back</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6"></div>
                                        <div class="col-sm-12 col-md-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-dark">
                                                <thead>
                                                    <tr>
                                                        {{-- <th scope="col"> </th>
                                                        <th scope="col"> : </th>
                                                        <th scope="col"> : </th>
                                                        <th scope="col"> : </th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Customer Info :</th>
                                                        <td>Name : {{ $invoice->payment->customer->name }}</td>
                                                        <td>Phone : {{ $invoice->payment->customer->phone }}</td>
                                                        <td>Email : {{ $invoice->payment->customer->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row"></th>
                                                        <td>Description : {!! $invoice->status == 1 && $invoice->isDelivered == 1
                                                            ? " <span class='badge badge-success'>Completed</span>"
                                                            : "<span class='badge badge-warning'>Not Paid</span>" !!} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-dark">
                                                <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Category</th>
                                                        <th>Product Name</th>
                                                        <th>Current Stock</th>
                                                        <th>Quantity</th>
                                                        {!! $invoice->status == 0 && $invoice->isDelivered == 0 ? '<th>Stock After Delivery</th>' : '' !!}
                                                        <th>Unit Price</th>
                                                        <th class="text-right">Total Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($invoice_details as $idx => $details)
                                                        <tr>
                                                            <th>{{ $idx }}</th>
                                                            <td>{{ $details->category->name }}</td>
                                                            <td>{{ $details->product->name }}</td>
                                                            <td>{{ $details->product->quantity }}</td>
                                                            <td>{{ $details->selling_qty }}</td>

                                                            @if ($invoice->status == 0 && $invoice->isDelivered == 0)
                                                                <td>
                                                                    {{ $details->product->quantity - $details->selling_qty }}
                                                                </td>
                                                            @endif

                                                            <td>{{ $details->unit_price }}</td>
                                                            <td class="text-right">{{ $details->selling_price }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="4">Payment Status</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->paid_status }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">Sub Total</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->total_amount + $invoice->payment->discount_amount }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">Discount</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->discount_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">Customer Will Pay</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->total_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">Paid Amount</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->paid_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">Due Amount</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->due_amount }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <a class="btn btn-info"
                                                href="{{ route('admin.invoice.print', ['id' => $invoice->id]) }}">
                                                Print
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@include('backend.main.dataTableLibs')


@push('css')
    <style>
        #swal-input-delivered {
            margin-top: 10px;
        }

        th.sorting_disabled::before,
        th.sorting_disabled::after {
            content: "" !important;
        }

        .card__header {
            display: flex;
            justify-content: space-between;
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding: .75rem 1.25rem;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }

        .action .dropdown-menu {
            position: absolute;
            /* top: -20px !important;  */
            left: -50px !important;
        }
    </style>
@endpush

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.show-alert', function(event) {
                event.preventDefault();

                let invoiceId = $(this).data('invoice-id');
                let isDeliveredFlag = $(this).data('is-delivered'); // 0 = not delivered
                let form = $('#print-form-' + invoiceId);

                let htmlInputs =
                    '<input id="swal-input-amount" class="swal2-input" type="number" placeholder="Due Paid Amount" value="0">';

                if (isDeliveredFlag == 0) {
                    // Show dropdown if not yet delivered
                    htmlInputs +=
                        '<select id="swal-input-delivered" class="swal2-input">' +
                        '<option value="false" selected>Is Delivered? No</option>' +
                        '<option value="true">Is Delivered? Yes</option>' +
                        '</select>';
                } else {
                    // Show plain text if already delivered
                    htmlInputs +=
                        '<div class="swal2-html-container" style="margin-top:10px;">Product Delivered</div>';
                }

                Swal.fire({
                    title: 'Approve Invoice',
                    html: htmlInputs,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Approve',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const dueAmount = document.getElementById('swal-input-amount').value;
                        let isDelivered = form.find('input[name="isDelivered"]')
                            .val(); // use existing value

                        if (document.getElementById('swal-input-delivered')) {
                            isDelivered = document.getElementById('swal-input-delivered').value;
                        }

                        if (dueAmount === '' || isNaN(dueAmount)) {
                            Swal.showValidationMessage('Please enter a valid amount');
                            return false;
                        }

                        return {
                            due_paid_amount: dueAmount,
                            isDelivered: isDelivered
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.find('input[name="due_paid_amount"]').val(result.value
                            .due_paid_amount);
                        form.find('input[name="isDelivered"]').val(result.value.isDelivered);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
