@extends('backend.main.layout')

@section('title', $title)

@section('content')


    <div>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title }} Create</h1>
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
                            <div class="card-body">

                                <form class="card-body" action="{{ route('admin.product.all.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="Name">
                                        @error('name')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control select2"
                                                style="width: 100%;" required>
                                                <option value="">Select Category</option>
                                                @forelse ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ old('supplier_id', $defaultSupplierId ?? '') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @empty
                                                    <option>No Option Added</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <select name="category_id" id="category_id" class="form-control"
                                                style="width: 100%;" required>
                                                <option>No Option Added</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label>Unit Name</label>
                                            <select name='unit_id' class="form-control select2" style="width: 100%;">
                                                @forelse ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @empty
                                                    <option>No Option Added</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Quantity</label>
                                                <input type="text" name="quantity"
                                                    class="form-control @error('quantity') is-invalid @enderror"
                                                    value="{{ old('quantity') }}" placeholder="Quantity">
                                                @error('quantity')
                                                    <div class="error__msg">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 text-right">
                                                <label>Status</label>
                                                <div>
                                                    <input type="checkbox" name="status" data-toggle="toggle"
                                                        data-size="sm" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="sliderImageInput">Photo</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="photo" class="custom-file-input"
                                                        id="sliderImageInput" value="{{ old('photo') }}">
                                                    <label class="custom-file-label" for="sliderImageInput">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 w__180" id="imagePreviewWrapper" style="display: none;">
                                            <img id="uploadedImage" class="img-fluid" src=""
                                                alt="New Image Preview">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button class="btn btn-success" type="submit">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection



@push('css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">
@endpush

@push('css')
    <style>
        .w__180 {
            width: 180px;
        }

        .select2-container .select2-selection--single {
            height: 40px !important;
            line-height: 40px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
    </style>
@endpush


@push('js')
    <script src="{{ asset('backend/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endpush


@push('js')
    <script>
        $(document).ready(function() {
            $('#sliderImageInput').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#uploadedImage').attr('src', e.target.result);
                        $('#imagePreviewWrapper').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#imagePreviewWrapper').hide();
                    $('#uploadedImage').attr('src', '');
                }
            });
        });

        $(function() {
            $('.select2').select2()
        });
    </script>
@endpush



@push('js')
    <script>
        $(document).ready(function() {
            // Initialize all select2s
            $('#supplier_id, #category_id, #product_id').select2({
                width: '100%'
            });

            // Fetch categories based on supplier
            function fetchCategories(supplierId, callback) {
                $('#category_id').empty().append('<option value="">Loading...</option>').trigger('change.select2');
                $('#product_id').empty().append('<option value="">Select Product</option>').trigger(
                    'change.select2');

                if (supplierId) {
                    $.ajax({
                        url: "{{ route('admin.product.purchase.getCategory') }}",
                        type: "GET",
                        data: {
                            supplier_id: supplierId
                        },
                        success: function(data) {
                            $('#category_id').empty().append(
                                '<option value="">Select Category</option>');
                            if (data.length > 0) {
                                $.each(data, function(key, category) {
                                    $('#category_id').append('<option value="' + category.id +
                                        '">' + category.name + '</option>');
                                });
                            } else {
                                $('#category_id').append('<option>No Option Added</option>');
                                $('#product_id').empty().append('<option>No Option Added</option>');
                            }

                            // Refresh Select2
                            $('#category_id').trigger('change.select2');

                            // Callback to fetch products for first category
                            if (typeof callback === "function") {
                                const firstCategoryId = $('#category_id option:first').val();
                                callback(firstCategoryId);
                            }
                        }
                    });
                } else {
                    $('#category_id').html('<option value="">Select Category</option>').trigger('change.select2');
                    $('#product_id').html('<option value="">Select Product</option>').trigger('change.select2');
                }
            }

            // Stub (implement if needed)
            function fetchProducts(categoryId) {
                // You can implement this function later if needed
            }

            // On page load
            const defaultSupplierId = $('#supplier_id').val();
            fetchCategories(defaultSupplierId, fetchProducts);

            // When supplier changes
            $('#supplier_id').on('change', function() {
                fetchCategories($(this).val(), fetchProducts);
            });

            // If needed:
            // $('#category_id').on('change', function () {
            //     fetchProducts($(this).val());
            // });
        });
    </script>
@endpush
