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
                                <div>
                                    <h3>
                                        Shop Name Invoice
                                    </h3>
                                    <p>
                                        Shop Address
                                    </p>
                                    <p>
                                        shop@mail.com
                                    </p>
                                </div>
                                <div>
                                    <p>
                                        Invoice Date
                                    </p>
                                    <p>
                                        20/12/2003
                                    </p>
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
                                                            <td>{{ $details->unit_price }}</td>
                                                            <td class="text-right">{{ $details->selling_price }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="3">Payment Status</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->paid_status }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Sub Total</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->total_amount + $invoice->payment->discount_amount }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Discount</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->discount_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Paid Amount</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->paid_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Due Amount</td>
                                                        <td colspan="4" class="text-right">
                                                            {{ $invoice->payment->due_amount }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button id="printButton" class="btn btn-info">
                                                Print
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@include('backend.main.dataTableLibs')


@push('css')
    <style>
        @media print {
            #printButton {
                display: none;
            }
        }
        p {
            margin: 0;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#printButton').on('click', function() {
            window.print();
        });
    </script>
@endpush
