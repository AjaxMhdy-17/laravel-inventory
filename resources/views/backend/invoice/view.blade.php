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
                                    <a href="{{ route('admin.product.purchase.index') }}" class="btn btn-info">Back</a>
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
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <div class="">
                                                    <label for="">Product Suppler Name : </label>
                                                    <span class=""> {{ $purchase->supplier }}</span>
                                                </div>
                                                <div class="">
                                                    <label for="">Product Category Name : </label>
                                                    <span class=""> {{ $purchase->category }}</span>
                                                </div>
                                                <div class="">
                                                    <label for="">Product Name : </label>
                                                    <span class=""> {{ $purchase->product }}</span>
                                                </div>
                                                <div class="">
                                                    <label for="">Product Description : </label>
                                                    <span class=""> {{ $purchase->description }}</span>
                                                </div>
                                                <div class="">
                                                    <label for="">Product Buying Quantity : </label>
                                                    <span class=""> {{ $purchase->buying_qty }}</span>
                                                </div>

                                                <div class="">
                                                    <label for="">Product Unit Price : </label>
                                                    <span class=""> {{ $purchase->unit_price }}</span>
                                                </div>

                                                <div class="">
                                                    <label for="">Product Total Price : </label>
                                                    <span class=""> {{ $purchase->price }}</span>
                                                </div>
                                                <div>
                                                    <label for="">Product Added by : </label>
                                                    <span class=""> {{ $purchase->user->name }}</span>
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.product.all.index') }}"
                                                        class="btn btn-info">Back</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card-body">
                                                <div>
                                                    <label for="">Product Status : </label>
                                                    {!! $purchase->status
                                                        ? '<span class="badge badge-primary">Approved</span>'
                                                        : '<span class="badge badge-danger">Pending</span>' !!}
                                                </div>
                                                <div class="">
                                                   
                                                    <form
                                                        action="{{ route('admin.product.purchase.status', ['status' => $purchase->id]) }}"
                                                        method="post">
                                                        @csrf

                                                        {!! $purchase->status == 1
                                                            ? '<button class="btn btn-danger">Confirm Not Approved</button>'
                                                            : '<button class="btn btn-warning">Confirm Approve</button>' !!}


                                                    </form>
                                                    {{-- <span class="badge badge-info">{{ }}</span> --}}
                                                </div>
                                            </div>
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
