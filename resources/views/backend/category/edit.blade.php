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
                                    action="{{ route('admin.product.category.update', ['category' => $category->id]) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <select name="supplier_id" class="form-control select2" style="width: 100%;">
                                                @forelse ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $category->suppliers->pluck('id')->first() == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @empty
                                                    <option>No Option Added</option>
                                                @endforelse
                                            </select>

                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ optional($category)->name }}" placeholder="Name">
                                        @error('name')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Team Member Status</label>
                                        <div>
                                            <input type="checkbox" name="status"
                                                {{ $category->status == 1 ? 'checked' : '' }} data-toggle="toggle"
                                                data-size="sm">
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
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">
    <style>
        .w__180 {
            width: 180px;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endpush
