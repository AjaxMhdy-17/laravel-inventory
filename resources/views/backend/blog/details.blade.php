@extends('backend.main.layout')

@section('title', $title)

@section('content')
    <div>
        <!-- Content Header (Page header) -->
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
                    <div class="col-md-3">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-muted text-center mb-3">
                                    {{$post->user->name}}
                                </div>
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid"
                                        src="{{ isset($post->photo) ? asset($post->photo) : asset('backend/assets/dist/img/avatar5.png') }}"
                                        alt="User profile picture">
                                </div>
                                <h3 class="profile-username text-center">{{ $post->category->name }}</h3>
                                <div style="display: flex ; gap : 5px ; justify-content : center">
                                    @foreach ($post->tags as $tag)
                                        <p class="text-muted badge badge-info text-white">{{ $tag->name }}</p>
                                    @endforeach
                                </div>
                                <div class="text-muted text-center">
                                    {{ $post->status == 1 ? 'Published' : 'Draft' }}
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>

                    <div class="col-md-9">
                        <!-- About Me Box -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Post Details</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <strong>Title</strong>
                                <p class="text-muted">
                                    {{ $post->title }}
                                </p>
                                <hr>
                                <strong>Content</strong>
                                <p class="text-muted">{!! $post->content !!}</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
