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
                                <form class="row" action="{{ route('admin.product.purchase.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <select name='supplier_id' id='supplier_id' class="form-control select2"
                                                style="width: 100%;">
                                                @forelse ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @empty
                                                    <option>No Option Added</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <select name="category_id" id="category_id" class="form-control"
                                                style="width: 100%;">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $defaultCategoryId ?? '') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <select name="product_id" id="product_id" class="form-control"
                                                style="width: 100%;">
                                                <option>No Option Added</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 text-right mb-2">
                                        <label>...</label>
                                        <input type="text" hidden name="purchase_id"
                                            class="form-control @error('purchase_id') is-invalid @enderror"
                                            value="{{ $randNumber }}" placeholder="Purchase Id">
                                        @error('purchase_id')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div>
                                            <button id="addMoreRow" class="btn btn-success" type="button">
                                                Add More
                                            </button>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body table-responsive p-0" style="height: 300px;">
                                                <table class="table table-head-fixed text-nowrap" id="purchaseTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Category</th>
                                                            <th>Product Name</th>
                                                            <th>Unit</th>
                                                            <th>Unit Price</th>
                                                            <th style="width : 10%">Description</th>
                                                            <th>Price</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="display:none;">
                                                        <!-- Dynamic rows will be appended here -->
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="6" class="text-right">
                                                                <input type="text" id="totalPrice" readonly
                                                                    style="width : 200px ; padding-left : 5px ; "
                                                                    placeholder="Total Price">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
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

        tr td {
            border: 1px solid #ddd;
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
                                    $('#category_id').append('<option value="' +
                                        category.id + '">' + category.name +
                                        '</option>');
                                });
                                if (typeof callback === "function") {
                                    var firstCategoryId = $('#category_id option:first').val();
                                    callback(firstCategoryId);
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
                $('#product_id').empty();
                if (categoryId) {
                    $.ajax({
                        url: "{{ route('admin.product.purchase.getProduct') }}",
                        type: "GET",
                        data: {
                            category_id: categoryId
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(key, product) {
                                    $('#product_id').append('<option value="' +
                                        product.id + '">' + product.name +
                                        '</option>');
                                });
                            } else {
                                $('#product_id').append('<option>No Option Added</option>');
                            }
                        }
                    });
                } else {
                    $('#product_id').append('<option>No Option Added</option>');
                }
            }
            var defaultSupplierId = $('#supplier_id').val();
            fetchCategories(defaultSupplierId, fetchProducts);
            $('#supplier_id').on('change', function() {
                fetchCategories($(this).val(), fetchProducts);
            });
            $('#category_id').on('change', function() {
                fetchProducts($(this).val());
            });
        });
    </script>
@endpush


@push('js')
    <script>
        $(document).ready(function() {
            // Existing code for select2 and chaining (your code here)...

            // Utility: Get selected category/product names
            function getSelectedCategoryName() {
                return $('#category_id option:selected').text();
            }

            function getSelectedProductName() {
                return $('#product_id option:selected').text();
            }

            // Utility: Calculate total price
            function updateTotalPrice() {
                let total = 0;
                $('#purchaseTable tbody tr').each(function() {
                    let price = parseFloat($(this).find('.rowPrice').val()) || 0;
                    total += price;
                });
                $('#totalPrice').val(total.toFixed(2));
            }

            // Utility: Calculate row price
            function calculateRowPrice($row) {
                let unit = parseFloat($row.find('.unit').val()) || 0;
                let unitPrice = parseFloat($row.find('.unitPrice').val()) || 0;
                let price = unit * unitPrice;
                $row.find('.rowPrice').val(price.toFixed(2));
                updateTotalPrice();
            }

            // Add More Button Click
            $('#addMoreRow').on('click', function() {
                // Show tbody if hidden
                $('#purchaseTable tbody').show();

                // Get selected values/texts
                let categoryName = getSelectedCategoryName();
                let productName = getSelectedProductName();

                // Build the row
                let row = `
            <tr>
                <td>
                    <input type="text" class="form-control-plaintext" readonly value="${categoryName}" style="width:120px;">
                </td>
                <td>
                    <input type="text" class="form-control-plaintext" readonly value="${productName}" style="width:160px;">
                </td>
                <td>
                    <input type="number" min="0" class="form-control unit" style="width:100px;" placeholder="Unit">
                </td>
                <td>
                    <input type="number" min="0" class="form-control unitPrice" style="width:80px;" placeholder="Unit Price">
                </td>
                <td>
                    <input type="text" class="form-control" style="width:180px;" placeholder="Description">
                </td>
                <td>
                    <input type="text" class="form-control rowPrice" style="width:120px;" readonly value="0.00">
                </td>
                <td class="text-right">
                    <button class="btn btn-warning btnRemoveRow" type="button">x</button>
                </td>
            </tr>
        `;
                $('#purchaseTable tbody').append(row);
                updateTotalPrice();
            });

            // Listen for changes in unit or unit price in any row
            $('#purchaseTable').on('input', '.unit, .unitPrice', function() {
                let $row = $(this).closest('tr');
                calculateRowPrice($row);
            });

            // Remove row
            $('#purchaseTable').on('click', '.btnRemoveRow', function() {
                $(this).closest('tr').remove();
                updateTotalPrice();
                // Hide tbody if no rows left
                if ($('#purchaseTable tbody tr').length === 0) {
                    $('#purchaseTable tbody').hide();
                }
            });

            // Optional: Reset total if page is reloaded
            updateTotalPrice();
        });
    </script>
@endpush
