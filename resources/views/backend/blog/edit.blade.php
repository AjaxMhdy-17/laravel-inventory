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
                            <li class="breadcrumb-item active">Post Edit</li>
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
                                <form class="card-body"
                                    action="{{ route('admin.blog.post.update', ['post' => $post->id]) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        <label>Title</label>
                                        <input type="text" id="title" name="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ optional($post)->title }}" placeholder="Title">
                                        @error('title')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Slug</label>
                                        <input type="text" id="slug" name="slug"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            value="{{ optional($post)->slug }}" placeholder="Slug">
                                        @error('slug')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="sliderImageInput">Photo</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="photo" class="custom-file-input"
                                                    id="sliderImageInput">
                                                <label class="custom-file-label" for="sliderImageInput">Choose file</label>
                                            </div>
                                        </div>
                                    </div>


                                    @if (!empty($post->photo))
                                        <div class="mb-3 w__180" id="existingImageWrapper">
                                            <img id="existingImage" class="img-fluid" src="{{ asset($post->photo) }}"
                                                alt="Existing Image">
                                        </div>
                                    @endif

                                    <div class="mb-3 w__180" id="imagePreviewWrapper" style="display: none;">
                                        <img id="uploadedImage" class="img-fluid" src="" alt="New Image Preview">
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label>Select Category</label>
                                            <select name="category_id" class="custom-select">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ optional($post->category)->id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <div>
                                                @error('category_id')
                                                    <div class="error__msg">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label>Tags List</label>
                                            <select class="select2" name="tag[]" multiple="multiple"
                                                data-placeholder="Select a State" style="width: 100%;">
                                                @if (isset($tags))
                                                    @foreach ($tags as $tag)
                                                        <option value="{{ $tag->id }}"
                                                            {{ in_array($tag->id, old('tag', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                            {{ $tag->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div>
                                                @error('tag')
                                                    <div class="error__msg">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label>Content</label>
                                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" placeholder="Content"
                                            id="summernote" cols="30" rows="10">
                                            {{ old('content', $post->content) }}
                                        </textarea>

                                        @error('content')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label>Publish</label>
                                        <div>
                                            <input type="checkbox" name="status" {{ $post->status == 1 ? 'checked' : '' }}
                                                data-toggle="toggle" data-size="sm">
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
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/summernote/summernote-bs4.min.css') }}">
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

        .select2-selection__choice {
            background: #007bff !important;
        }

        .select2-selection__choice__remove {
            color: #fff !important;
        }


        .note-editable {
            min-height: 200px;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('backend/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endpush

@push('js')
    <script>
        $(function() {
            $('#summernote').summernote();

            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        })
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
        $('.select2').select2({});
    </script>

    <script>
        document.getElementById('title').addEventListener('input', function() {
            let title = this.value;
            let slug = title
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            document.getElementById('slug').value = slug;
        });
    </script>
@endpush
