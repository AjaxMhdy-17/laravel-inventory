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
                <div class="card">
                    <div class="card-header">
                        <form action="{{ route('admin.invoice.daily.invoice') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Start Date:</label>
                                        <div class="input-group date" id="startDatePicker" data-target-input="nearest">
                                            <input type="text" name="start_date"
                                                class="form-control datetimepicker-input" value="{{ old('start_date') }}"
                                                data-target="#startDatePicker" />
                                            <div class="input-group-append" data-target="#startDatePicker"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div>
                                            @error('start_date')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>End Date:</label>
                                        <div class="input-group date" id="endDatePicker" data-target-input="nearest">
                                            <input type="text" name="end_date" value="{{ old('end_date') }}"
                                                class="form-control datetimepicker-input" data-target="#endDatePicker" />
                                            <div class="input-group-append" data-target="#endDatePicker"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <div>
                                            @error('end_date')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 d-flex align-items-center" style="margin-top: 15px;">
                                    <button type="submit" class="btn btn-info">Search</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <style>
        @media print {
            #printButton {
                display: none;
            }
        }

        p {
            margin: 0;
        }

        th.sorting_disabled::before,
        th.sorting_disabled::after {
            content: "" !important;
        }

        .card__header {
            display: flex;
            justify-content: space-between;
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            padding: .75rem 1.25rem;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }

        .action .dropdown-menu {
            position: absolute;
            /* top: -20px !important;  */
            left: -50px !important;
        }
    </style>
@endpush

@push('js')
    <script type="text/javascript">
        $(function() {
            $('#startDatePicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#endDatePicker').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false
            });

            // Optional: link date pickers so end date can't be before start
            $("#startDatePicker").on("change.datetimepicker", function(e) {
                $('#endDatePicker').datetimepicker('minDate', e.date);
            });
            $("#endDatePicker").on("change.datetimepicker", function(e) {
                $('#startDatePicker').datetimepicker('maxDate', e.date);
            });
        });
    </script>
@endpush
