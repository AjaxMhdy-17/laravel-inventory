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

                                    {!! $invoice->status == 0
                                        ? '<a href="#" class="btn btn-info mr-3"
                                                                                                                                                                                                                                                                    onclick="event.preventDefault(); document.getElementById(\'print-form-' .
                                            $invoice->id .
                                            '\').submit();">
                                                                                                                                                                                                                                                                    Approve
                                                                                                                                                                                                                                                                  </a>'
                                        : '<a href="#" class="btn btn-info mr-3">Already Approved</a>' !!}

                                    <form id="print-form-{{ $invoice->id }}"
                                        action="{{ route('admin.invoice.approve.invoice', ['id' => $invoice->id]) }}"
                                        method="POST" style="display: none;">
                                        @csrf
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
                                                        <td>Description : {!! $invoice->status == 1
                                                            ? " <span class='badge badge-success'>Paid</span>"
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
                                                        {!! $invoice->status == 0 ? '<th>Stock After Approved</th>' : '' !!}
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

                                                            @if ($invoice->status == 0)
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
