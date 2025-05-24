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
                                    <a href="{{ route('admin.product.all.index') }}" class="btn btn-info">Back</a>
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
                                                    <label for="">Product Name : </label>
                                                    <p class=""> {{ $product->name }}</p>
                                                </div>
                                                <div class="">
                                                    <label for="">Product supplier : </label>
                                                    <p class=""> {{ $product->supplier->name }}</p>
                                                </div>
                                                <div class="">
                                                    <label for="">Product Category : </label>
                                                    <p class=""> {{ $product->category->name }}</p>
                                                </div>
                                                <div class="">
                                                    <label for="">Product Unit : </label>
                                                    <p class=""> {{ $product->unit->name }}</p>
                                                </div>
                                                <div>
                                                    <label for="">Product Photo : </label>
                                                    <p class="">
                                                        <img src="{{ asset($product->photo) }}"
                                                            style="height: 120px ; width : 120px ; object-fit : cover"
                                                            alt="">
                                                    </p>
                                                </div>
                                                <div>
                                                    <label for="">Product Added by : </label>
                                                    <p class=""> {{ $product->user->name }}</p>
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
                                                    {!! $product->status
                                                        ? '<span class="badge badge-primary">Active</span>'
                                                        : '<span class="badge badge-danger">In Active</span>' !!}
                                                </div>
                                                <div>
                                                    <label for="">Product Quantity : </label>
                                                    <span class="badge badge-info">{{$product->quantity}}</span>
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
