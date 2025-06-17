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
                                <h3 class="card-title">List Of {{ $title }}</h3>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('admin.product.all.create') }}" class="btn btn-primary d-block">
                                        Add {{ $title }}
                                    </a>
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
                                        <div class="col-sm-12">
                                            <div class="card-body table-responsive">
                                                <table class="table table-bordered table-striped myDatatable"
                                                    style="width : 100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Photo</th>
                                                            <th>Supplier</th>
                                                            <th>Category</th>
                                                            <th>Unit</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
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

@push('js')
    <script>
        $(document).ready(function() {
            $('.myDatatable').DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                ajax: {
                    url: '{{ route('admin.product.all.index') }}',
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        className: "text-center",
                    },
                    {
                        data: 'supplier',
                        name: 'supplier',
                        orderable: false,
                        className: "text-center",
                    },
                    {
                        data: 'category',
                        name: 'category',
                        orderable: false,
                        className: "text-center",
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        className: "text-center",
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: "text-center",
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: "text-right",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('change', '.row-checkbox, #select-all', function() {
                const selected = $('.row-checkbox:checked').length;
                $('#bulk-delete').toggleClass('d-none', selected === 0);
            });

            $(document).on('change', '#select-all', function() {
                $('.row-checkbox').prop('checked', this.checked).trigger('change');
            });

            // SweetAlert on delete click
            $(document).on('click', '.show-alert-delete-box', function(event) {
                event.preventDefault();
                const form = $(this).closest("form");
                const selectedIds = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No rows selected',
                        text: 'Please select at least one row to delete.',
                    });
                    return;
                }
                $('#bulk-delete-ids').val(selectedIds.join(','));
            });

        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.show-alert-delete-box', function(event) {
                event.preventDefault();
                var form = $(this).closest("form");
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you really want to delete this item?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush



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
