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
                                    action="{{ route('admin.service.update', ['service' => $service->id]) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="mb-3">
                                        <label>Service Icon</label>
                                        <input type="text" name="icon"
                                            class="form-control @error('icon') is-invalid @enderror"
                                            value="{{ optional($service)->icon }}" placeholder="Service Icon">
                                        @error('icon')
                                            <div class="error__msg">
                                                {!! $message !!}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Title</label>
                                        <input type="text" name="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ optional($service)->title }}" placeholder="Title">
                                        @error('title')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <input type="text" name="description"
                                            class="form-control @error('description') is-invalid @enderror"value="{{ optional($service)->description }}"
                                            placeholder="Description">
                                        @error('description')
                                            <div class="error__msg">
                                                {{ $message }}
                                            </div>
                                        @enderror
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
