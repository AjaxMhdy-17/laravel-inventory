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
                                            <div class="card-body table-responsive p-0" style="max-height: 300px;">


                                                <table class="table table-head-fixed text-nowrap" id="purchaseTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Category</th>
                                                            <th>Product Name</th>
                                                            <th>PSC/KG</th>
                                                            <th>Unit Price</th>
                                                            <th style="width: 10%">Description</th>
                                                            <th>Price</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody @if (!old('purchase_items')) style="display:none;" @endif>
                                                        @php
                                                            $purchaseItems = old('purchase_items', []);
                                                        @endphp
                                                        @foreach ($purchaseItems as $i => $item)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden"
                                                                        name="purchase_items[{{ $i }}][supplier_id]"
                                                                        value="{{ $item['supplier_id'] ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="purchase_items[{{ $i }}][supplier]"
                                                                        value="{{ $item['supplier'] ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="purchase_items[{{ $i }}][category_id]"
                                                                        value="{{ $item['category_id'] ?? '' }}">
                                                                    <input type="text"
                                                                        name="purchase_items[{{ $i }}][category]"
                                                                        class="form-control-plaintext" readonly
                                                                        value="{{ $item['category'] ?? '' }}"
                                                                        style="width:120px;">
                                                                    @error("purchase_items.$i.category")
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="hidden"
                                                                        name="purchase_items[{{ $i }}][product_id]"
                                                                        value="{{ $item['product_id'] ?? '' }}">
                                                                    <input type="text"
                                                                        name="purchase_items[{{ $i }}][product]"
                                                                        class="form-control-plaintext" readonly
                                                                        value="{{ $item['product'] ?? '' }}"
                                                                        style="width:160px;">
                                                                    @error("purchase_items.$i.product")
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="number" min="0"
                                                                        name="purchase_items[{{ $i }}][unit]"
                                                                        class="form-control unit" style="width:100px;"
                                                                        value="{{ $item['unit'] ?? '' }}"
                                                                        placeholder="Unit">
                                                                    @error("purchase_items.$i.unit")
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="number" min="0"
                                                                        name="purchase_items[{{ $i }}][unit_price]"
                                                                        class="form-control unitPrice" style="width:80px;"
                                                                        value="{{ $item['unit_price'] ?? '' }}"
                                                                        placeholder="Unit Price">
                                                                    @error("purchase_items.$i.unit_price")
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        name="purchase_items[{{ $i }}][description]"
                                                                        class="form-control" style="width:180px;"
                                                                        value="{{ $item['description'] ?? '' }}"
                                                                        placeholder="Description">
                                                                    @error("purchase_items.$i.description")
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        name="purchase_items[{{ $i }}][price]"
                                                                        class="form-control rowPrice" style="width:120px;"
                                                                        readonly value="{{ $item['price'] ?? '0.00' }}">
                                                                    @error("purchase_items.$i.price")
                                                                        <div class="text-danger small">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                                <td class="text-right">
                                                                    <button class="btn btn-warning btnRemoveRow"
                                                                        type="button">x</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="6" class="text-right">
                                                                <span>
                                                                    Grand Total :
                                                                </span>
                                                                <input type="text" id="totalPrice" readonly
                                                                    style="width: 200px; padding-left: 5px;"
                                                                    placeholder="Total Price">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-info">
                                            Add Store
                                        </button>
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
            $('#supplier_id, #category_id, #product_id').select2({
                width: '100%'
            });

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

                            $('#category_id').trigger('change.select2');
                            if (typeof callback === "function") {
                                const firstCategoryId = $('#category_id option:first').val();
                                callback(firstCategoryId);
                            }
                        }
                    });
                } else {
                    $('#category_id').html('<option>No Option Added</option>').trigger('change.select2');
                    $('#product_id').html('<option>No Option Added</option>').trigger('change.select2');
                }
            }

            // Fetch products based on category
            function fetchProducts(categoryId) {
                $('#product_id').empty().append('<option value="">Loading...</option>').trigger('change.select2');

                if (categoryId) {
                    $.ajax({
                        url: "{{ route('admin.product.purchase.getProduct') }}",
                        type: "GET",
                        data: {
                            category_id: categoryId
                        },
                        success: function(data) {
                            $('#product_id').empty().append('<option value="">Select Product</option>');
                            if (data.length > 0) {
                                $.each(data, function(key, product) {
                                    $('#product_id').append('<option value="' + product.id +
                                        '">' + product.name + '</option>');
                                });
                            } else {
                                $('#product_id').append('<option>No Option Added</option>');
                            }

                            $('#product_id').trigger('change.select2');
                        }
                    });
                } else {
                    $('#product_id').html('<option>No Option Added</option>').trigger('change.select2');
                }
            }

            const defaultSupplierId = $('#supplier_id').val();
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
            let rowIndex = $('#purchaseTable tbody tr').length;

            function getSelectedSupplierId() {
                return $('#supplier_id').val();
            }

            function getSelectedCategoryName() {
                return $('#category_id option:selected').text();
            }

            function getSelectedProductName() {
                return $('#product_id option:selected').text();
            }

            function updateTotalPrice() {
                let total = 0;
                $('#purchaseTable tbody tr').each(function() {
                    let price = parseFloat($(this).find('.rowPrice').val()) || 0;
                    total += price;
                });
                $('#totalPrice').val(total.toFixed(2));
            }

            function calculateRowPrice($row) {
                let unit = parseFloat($row.find('.unit').val()) || 0;
                let unitPrice = parseFloat($row.find('.unitPrice').val()) || 0;
                let price = unit * unitPrice;
                $row.find('.rowPrice').val(price.toFixed(2));
                updateTotalPrice();
            }

            $('#addMoreRow').on('click', function() {
                $('#purchaseTable tbody').show();

                let supplierId = getSelectedSupplierId();
                let supplierName = $('#supplier_id option:selected').text();
                let categoryId = $('#category_id').val();
                let categoryName = getSelectedCategoryName();
                let productId = $('#product_id').val();
                let productName = getSelectedProductName();

                let row = `<tr>
                    <td>
                        <input type="hidden" name="purchase_items[${rowIndex}][supplier_id]" value="${supplierId}">
                        <input type="hidden" name="purchase_items[${rowIndex}][supplier]" value="${supplierName}">
                        <input type="hidden" name="purchase_items[${rowIndex}][category_id]" value="${categoryId}">
                        <input type="text" name="purchase_items[${rowIndex}][category]" class="form-control-plaintext" readonly value="${categoryName}" style="width:120px;">
                    </td>
                    <td>
                        <input type="hidden" name="purchase_items[${rowIndex}][product_id]" value="${productId}">
                        <input type="text" name="purchase_items[${rowIndex}][product]" class="form-control-plaintext" readonly value="${productName}" style="width:160px;">
                    </td>
                    <td>
                        <input type="number" min="0" name="purchase_items[${rowIndex}][unit]" class="form-control unit" style="width:100px;" placeholder="Unit">
                    </td>
                    <td>
                        <input type="number" min="0" name="purchase_items[${rowIndex}][unit_price]" class="form-control unitPrice" style="width:80px;" placeholder="Unit Price">
                    </td>
                    <td>
                        <input type="text" name="purchase_items[${rowIndex}][description]" class="form-control" style="width:180px;" placeholder="Description">
                    </td>
                    <td>
                        <input type="text" name="purchase_items[${rowIndex}][price]" class="form-control rowPrice" style="width:120px;" readonly value="0.00">
                    </td>
                    <td class="text-right">
                        <button class="btn btn-warning btnRemoveRow" type="button">x</button>
                    </td>
                </tr>`;
                $('#purchaseTable tbody').append(row);
                rowIndex++;
            });

            $('#purchaseTable').on('input', '.unit, .unitPrice', function() {
                let $row = $(this).closest('tr');
                calculateRowPrice($row);
            });

            $('#purchaseTable').on('click', '.btnRemoveRow', function() {
                $(this).closest('tr').remove();
                updateTotalPrice();
                if ($('#purchaseTable tbody tr').length === 0) {
                    $('#purchaseTable tbody').hide();
                }
            });

            updateTotalPrice();
        });
    </script>
@endpush
