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
                            <li class="breadcrumb-item active">Team</li>
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
                                <form class="card-body" action="{{ route('admin.team.update', ['team' => $team->id]) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ optional($team)->name }}" placeholder="Name">
                                        @error('name')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Designation</label>
                                        <input type="text" name="designation"
                                            class="form-control @error('designation') is-invalid @enderror"
                                            value="{{ optional($team)->designation }}" placeholder="Designation">
                                        @error('designation')
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

                                    @if (!empty($team->photo))
                                        <div class="mb-3 w__180" id="existingImageWrapper">
                                            <img id="existingImage" class="img-fluid" src="{{ asset($team->photo) }}"
                                                alt="Existing Image">
                                        </div>
                                    @endif

                                    <div class="mb-3 w__180" id="imagePreviewWrapper" style="display: none;">
                                        <img id="uploadedImage" class="img-fluid" src="" alt="New Image Preview">
                                    </div>

                                    <div class="mb-3">
                                        <label>Team Member Status</label>
                                        <div>
                                            <input type="checkbox" name="active" {{$team->active == 1 ? 'checked' : ''}} data-toggle="toggle" data-size="sm">
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label>Facebook url</label>
                                        <input type="text" name="facebook" class="form-control"
                                            value="{{ optional($team)->facebook }}" placeholder="Facebook url">
                                    </div>

                                    <div class="mb-3">
                                        <label>Linkedin url</label>
                                        <input type="text" name="linkedin" class="form-control"
                                            value="{{ optional($team)->linkedin }}" placeholder="Linkedin url">
                                    </div>

                                    <div class="mb-3">
                                        <label>Github url</label>
                                        <input type="text" name="github" class="form-control"
                                            value="{{ optional($team)->github }}" placeholder="Github url">
                                    </div>

                                    <div class="mb-3">
                                        <label>Tweeter url</label>
                                        <input type="text" name="tweeter" class="form-control"
                                            value="{{ optional($team)->tweeter }}" placeholder="X url">
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
@endpush
