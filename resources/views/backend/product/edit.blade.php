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
                            <div class="card-body">

                                <form class="card-body"
                                    action="{{ route('admin.product.all.update', ['all' => $product->id]) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ optional($product)->name }}" placeholder="Name">
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
                                                style="width: 100%;">
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ old('supplier_id', $selectedSupplierId ?? '') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <select name="category_id" id="category_id" class="form-control select2"
                                                style="width: 100%;">
                                                <!-- Options will be loaded by AJAX -->
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
                                                    value="{{ optional($product)->quantity }}" placeholder="Quantity">
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
                                                    <input type="checkbox" name="status"
                                                        {{ $product->status == 1 ? 'checked' : '' }} data-toggle="toggle"
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
                                                        id="sliderImageInput">
                                                    <label class="custom-file-label" for="sliderImageInput">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!empty($product->photo))
                                            <div class="mb-3 w__180" id="existingImageWrapper">
                                                <img id="existingImage" class="img-fluid"
                                                    src="{{ asset($product->photo) }}" alt="Existing Image">
                                            </div>
                                        @endif
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

    <script>
        $(document).ready(function() {
            $('#sliderImageInput').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#uploadedImage').attr('src', e.target.result);
                        $('#imagePreviewWrapper').show();
                        $('#existingImageWrapper').hide();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#imagePreviewWrapper').hide();
                    $('#uploadedImage').attr('src', '');
                    $('#existingImageWrapper').show();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            var selectedCategoryId = '{{ old('category_id', $selectedCategoryId ?? '') }}';

            function fetchCategories(supplierId, callback) {
                $('#category_id').empty();
                if (supplierId) {
                    $.ajax({
                        url: "{{ route('admin.product.purchase.getCategory') }}",
                        type: "GET",
                        data: {
                            supplier_id: supplierId
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(key, category) {
                                    var selected = (category.id == selectedCategoryId) ?
                                        'selected' : '';
                                    $('#category_id').append('<option value="' +
                                        category.id + '" ' + selected + '>' + category
                                        .name +
                                        '</option>');
                                });
                                if (typeof callback === "function") {

                                    var catId = selectedCategoryId || $('#category_id option:first')
                                        .val();
                                    callback(catId);
                                }
                            } else {
                                $('#category_id').append('<option>No Option Added</option>');
                                $('#product_id').empty().append('<option>No Option Added</option>');
                            }
                        }
                    });
                } else {
                    $('#category_id').append('<option>No Option Added</option>');
                    $('#product_id').empty().append('<option>No Option Added</option>');
                }
            }

            function fetchProducts(categoryId) {
                // Implement if you want to fetch products according to category
            }
            var defaultSupplierId = $('#supplier_id').val();
            fetchCategories(defaultSupplierId, fetchProducts);
            $('#supplier_id').on('change', function() {
                selectedCategoryId = '';
                fetchCategories($(this).val(), fetchProducts);
            });
        });
    </script>
@endpush
