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
                            <li class="breadcrumb-item active">Site Setting</li>
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
                                <form class="card-body" action="{{ route('admin.site-setting.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label>Site Name</label>
                                        <input type="text" name="name"
                                            class="form-control  @error('name') is-invalid @enderror"
                                            value="{{ optional($setting)->name }}" placeholder="Name">
                                        @error('name')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="sliderImageInput">logo</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="logo" class="custom-file-input"
                                                    id="sliderImageInput">
                                                <label class="custom-file-label" for="sliderImageInput">Choose file</label>
                                            </div>
                                        </div>
                                    </div>

                                    

                                    @if (!empty($setting->logo))
                                        <div class="mb-3 w__180" id="existingImageWrapper">
                                            <img id="existingImage" class="img-fluid" src="{{ asset($setting->logo) }}"
                                                alt="Existing Image">
                                        </div>
                                    @endif

                                    <div class="mb-3 w__180" id="imagePreviewWrapper" style="display: none;">
                                        <img id="uploadedImage" class="img-fluid" src="" alt="New Image Preview">
                                    </div>



                                    <div class="mb-3">
                                        <label>Description</label>
                                        <input type="text" name="description"
                                            class="form-control  @error('description') is-invalid @enderror "value="{{ optional($setting)->description }}"
                                            placeholder="Description">
                                        @error('description')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="text" name="email"
                                            class="form-control  @error('email') is-invalid @enderror"value="{{ optional($setting)->email }}"
                                            placeholder="Email">
                                        @error('email')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Phone</label>
                                        <input type="text" name="phone"
                                            class="form-control  @error('phone') is-invalid @enderror"value="{{ optional($setting)->phone }}"
                                            placeholder="Phone">
                                        @error('phone')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Street Name</label>
                                        <input type="text" name="street"
                                            class="form-control"value="{{ optional($setting)->street }}"
                                            placeholder="Street Name">
                                    </div>

                                    <div class="mb-3">
                                        <label>City</label>
                                        <input type="text" name="city"
                                            class="form-control"value="{{ optional($setting)->city }}" placeholder="City">
                                    </div>

                                    <div class="mb-3">
                                        <label>Country</label>
                                        <input type="text" name="country"
                                            class="form-control"value="{{ optional($setting)->country }}"
                                            placeholder="Country">
                                    </div>

                                    <div class="mb-3">
                                        <label>X account url</label>
                                        <input type="text" name="x"
                                            class="form-control"value="{{ optional($setting)->x }}"
                                            placeholder="X account url">
                                    </div>

                                    <div class="mb-3">
                                        <label>Facebook</label>
                                        <input type="text" name="facebook"
                                            class="form-control"value="{{ optional($setting)->facebook }}"
                                            placeholder="Facebook">
                                    </div>

                                    <div class="mb-3">
                                        <label>Linkedin</label>
                                        <input type="text" name="linkedin"
                                            class="form-control"value="{{ optional($setting)->linkedin }}"
                                            placeholder="Linkedin">
                                    </div>

                                    <div class="mb-3">
                                        <label>You Tube</label>
                                        <input type="text" name="youtube"
                                            class="form-control"value="{{ optional($setting)->youtube }}"
                                            placeholder="You Tube">
                                    </div>

                                    <div class="mb-3">
                                        <label>Instagram</label>
                                        <input type="text" name="instagram"
                                            class="form-control"value="{{ optional($setting)->instagram }}"
                                            placeholder="Instagram">
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
    <style>
        .w__180 {
            width: 180px;
        }
    </style>
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
