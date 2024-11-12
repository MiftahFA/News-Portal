@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/modules/select2/dist/css/select2.min.css') }}">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('admin.Pending News') }}</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>{{ __('admin.All Pending') }}</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                    @foreach ($languages as $language)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->index === 0 ? 'active' : '' }}" id="home-tab2" data-toggle="tab"
                                href="#home-{{ $language->lang }}" role="tab" aria-controls="home"
                                aria-selected="true">{{ $language->name }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="table-responsive">
                    <div class="tab-content tab-bordered" id="myTab3Content">
                        @foreach ($languages as $language)
                            @php
                                if (canAccess(['news all-access'])) {
                                    $news = \App\Models\News::with('category')
                                        ->where('language', $language->lang)
                                        ->where('is_approved', 0)
                                        ->orderBy('id', 'DESC')
                                        ->get();
                                } else {
                                    $news = \App\Models\News::with('category')
                                        ->where('language', $language->lang)
                                        ->where('is_approved', 0)
                                        ->where('auther_id', auth()->guard('admin')->user()->id)
                                        ->orderBy('id', 'DESC')
                                        ->get();
                                }
                            @endphp
                            <div class="tab-pane fade show {{ $loop->index === 0 ? 'active' : '' }}"
                                id="home-{{ $language->lang }}" role="tabpanel" aria-labelledby="home-tab2">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped"id="table-{{ $language->lang }}">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        #
                                                    </th>
                                                    <th>{{ __('admin.Image') }}</th>
                                                    <th>{{ __('admin.Title') }}</th>
                                                    <th>{{ __('admin.Category') }}</th>
                                                    <th>{{ __('admin.Approve') }}</th>
                                                    <th>{{ __('admin.Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($news as $item)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <img src="{{ asset($item->image) }}" width="100"
                                                                alt="">
                                                        </td>
                                                        <td>{{ $item->title }}</td>
                                                        <td>{{ $item->category->name }}</td>
                                                        <td>
                                                            <form action="" id="approve_form">
                                                                <input type="hidden" name="id"
                                                                    value="{{ $item->id }}">
                                                                <div class="form-group">
                                                                    <div class="col-lg-12 p-0">
                                                                        <select name="is_approve"
                                                                            class="form-control select2" style="width: 100%"
                                                                            id="approve-input" style="width: 100%">
                                                                            <option value="0">
                                                                                {{ __('admin.Pending') }}
                                                                            </option>
                                                                            <option value="1">
                                                                                {{ __('admin.Approved') }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.news.edit', $item->id) }}"
                                                                class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                            <a href="{{ route('admin.news.destroy', $item->id) }}"
                                                                class="btn btn-danger delete-item"><i
                                                                    class="fas fa-trash-alt"></i></a>
                                                            <a href="{{ route('admin.news-copy', $item->id) }}"
                                                                class="btn btn-primary"><i class="fas fa-copy"></i></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('admin/assets/modules/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('admin/assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        @foreach ($languages as $language)
            $("#table-{{ $language->lang }}").dataTable({
                "columnDefs": [{
                    "sortable": false,
                    "targets": [4, 5]
                }]
            });
        @endforeach

        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).css('width', '100%');
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
            });

            $('#approve-input').on('change', function() {
                $('#approve_form').submit();
            });

            $('#approve_form').on('submit', function(e) {
                e.preventDefault();
                let data = $(this).serialize();
                $.ajax({
                    method: 'PUT',
                    url: "{{ route('admin.approve.news') }}",
                    data: data,
                    success: function(data) {
                        if (data.status === 'success') {
                            swal({
                                title: data.message,
                                icon: 'success',
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            })
        })
    </script>
@endpush
