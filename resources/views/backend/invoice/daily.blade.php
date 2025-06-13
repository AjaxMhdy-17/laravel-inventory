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
                                    <p>
                                        Date Range : From <span class="badge badge-info">{{ $start_date }}</span> To <span
                                            class="badge badge-info">{{ $end_date }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p>
                                        Invoice Date
                                    </p>
                                    <p>
                                        {{ now()->format('Y-m-d') }}
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
                                        <div class="col-12 table-responsive">
                                            <table class="table table-dark">
                                                <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Customer Name</th>
                                                        <th>Invoice No</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Description</th>
                                                        <th>Paid</th>
                                                        <th>Due</th>
                                                        <th>Total Amount</th>
                                                        <th>Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($invoices as $idx =>  $invoice)
                                                        <tr>
                                                            <td>{{ $idx + 1 }}</td>
                                                            <td>{{ $invoice->payment->customer->name }}</td>
                                                            <td>{{ $invoice->invoice_no }}</td>
                                                            <td>{{ Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}
                                                            </td>
                                                            <td>{{ $invoice->status }}</td>
                                                            <td>{{ $invoice->invoice_description }}</td>
                                                            <td>{{ $invoice->payment->paid_amount ?? '' }}</td>
                                                            <td>{{ $invoice->payment->due_amount ?? '' }}</td>
                                                            <td>{{ $invoice->payment->total_amount ?? '' }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.invoice.all.show', ['all' => $invoice->id]) }}"
                                                                    class="btn btn-warning">view</a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="11" class="text-center">
                                                                No Item Found 
                                                            </td>
                                                        </tr>
                                                    @endforelse

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">

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
