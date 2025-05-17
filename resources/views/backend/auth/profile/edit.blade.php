@extends('backend.main.layout')


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <!-- About Me Box -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Update Profile</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.profile.update', ['profile' => $user->id]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="mb-3">
                                    <label>Profile Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                        placeholder="Profile Name">
                                </div>
                                <div class="mb-3">
                                    <label>Profile Email</label>
                                    <input type="text" name="email" class="form-control" value="{{ $user->email }}"
                                        placeholder="Profile Name" readonly>
                                </div>


                                <div class="form-group">
                                    <label for="profileImageInput">Upload Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="photo" class="custom-file-input"
                                                id="profileImageInput">
                                            <label class="custom-file-label" for="profileImageInput">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3" id="oldImagePreviewWrapper"
                                    @if (empty($user->photo)) style="display: none;" @endif>
                                    <img id="profileImagePreview" class="profile-user-img img-fluid" style="object-fit: cover"
                                        src="{{ !empty($user->photo) ? asset('upload/admin/photo/' . $user->photo) : '' }}"
                                        alt="Image Preview">
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
@endsection



@push('js')
    <script>
        $('#profileImageInput').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Set new image
                    $('#profileImagePreview').attr('src', e.target.result);

                    // Show preview if hidden
                    $('#oldImagePreviewWrapper').show();
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
