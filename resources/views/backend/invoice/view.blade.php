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
                                    <a href="{{ route('admin.invoice.all.index') }}" class="btn btn-info">Back</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card--body">
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
                                                        <td>Description : Everything is done</td>
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
                                                        <th scope="">SL</th>
                                                        <th scope="">Category</th>
                                                        <th scope="">Current Stock</th>
                                                        <th scope="">Quantity</th>
                                                        <th scope="">Unit Price</th>
                                                        <th scope="">Total Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>5</th>
                                                        <td>Name : {{ $invoice->payment->customer->name }}</td>
                                                        <td>Phone : {{ $invoice->payment->customer->phone }}</td>
                                                        <td>Email : {{ $invoice->payment->customer->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row"></th>
                                                        <td>Description : Everything is done</td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
