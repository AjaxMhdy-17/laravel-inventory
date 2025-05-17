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
                            <li class="breadcrumb-item active">Tag</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <form class="card-body" action="{{ route('admin.blog.tag.update',['tag' => 88]) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="mb-3">
                                            <div class="form-group">
                                                <label>Tags List</label>
                                                <select class="select2" name="tag[]" multiple="multiple"
                                                    data-placeholder="Select a State" style="width: 100%;">
                                                    @if (isset($tags))
                                                        @foreach ($tags as $tag)
                                                            <option value="{{ $tag->name }}"
                                                                {{ isset($selectedTags) && in_array($tag->name, $selectedTags) ? 'selected' : '' }}>
                                                                {{ $tag->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary">Save</button>
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
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('css')
    <style>
        .select2-selection__choice {
            background: #007bff !important;
        }

        .select2-selection__choice__remove {
            color: #fff !important;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('backend/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('js')
    <script>
        $('.select2').select2({
            tags: true,
            tokenSeparators: [',']
        });
    </script>
@endpush
