@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('admin.News') }}</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>{{ __('admin.All News') }}</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('admin.Create') }}
                    </a>
                </div>
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
                <div class="tab-content tab-bordered" id="myTab3Content">
                    @foreach ($languages as $language)
                        @php
                            if (canAccess(['news all-access'])) {
                                $news = \App\Models\News::with('category')
                                    ->where('language', $language->lang)
                                    ->where('is_approved', 1)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
                            } else {
                                $news = \App\Models\News::with('category')
                                    ->where('language', $language->lang)
                                    ->where('is_approved', 1)
                                    ->where('auther_id', auth()->guard('admin')->user()->id)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
                            }
                        @endphp
                        <div class="tab-pane fade show {{ $loop->index === 0 ? 'active' : '' }}"
                            id="home-{{ $language->lang }}" role="tabpanel" aria-labelledby="home-tab2">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-{{ $language->lang }}">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>{{ __('admin.Image') }}</th>
                                                <th>{{ __('admin.Title') }}</th>
                                                <th>{{ __('admin.Category') }}</th>
                                                @if (canAccess(['news status', 'news all-access']))
                                                    <th>{{ __('admin.In Breaking') }}</th>
                                                    <th>{{ __('admin.In Slider') }}</th>
                                                    <th>{{ __('admin.In Popular') }}</th>
                                                @endif
                                                <th>{{ __('admin.Status') }}</th>
                                                <th>{{ __('admin.Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($news as $item)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <img src="{{ asset($item->image) }}" width="100" alt="">
                                                    </td>

                                                    <td>{{ $item->title }}</td>
                                                    <td>{{ $item->category->name }}</td>
                                                    @if (canAccess(['news status', 'news all-access']))
                                                        <td>
                                                            <label class="custom-switch mt-2">
                                                                <input {{ $item->is_breaking_news === 1 ? 'checked' : '' }}
                                                                    data-id="{{ $item->id }}"
                                                                    data-name="is_breaking_news" value="1"
                                                                    type="checkbox"
                                                                    class="custom-switch-input toggle-status">
                                                                <span class="custom-switch-indicator"></span>
                                                            </label>
                                                        </td>

                                                        <td>
                                                            <label class="custom-switch mt-2">
                                                                <input {{ $item->show_at_slider === 1 ? 'checked' : '' }}
                                                                    data-id="{{ $item->id }}"
                                                                    data-name="show_at_slider" value="1"
                                                                    type="checkbox"
                                                                    class="custom-switch-input toggle-status">
                                                                <span class="custom-switch-indicator"></span>
                                                            </label>
                                                        </td>

                                                        <td>
                                                            <label class="custom-switch mt-2">
                                                                <input {{ $item->show_at_popular === 1 ? 'checked' : '' }}
                                                                    data-id="{{ $item->id }}"
                                                                    data-name="show_at_popular" value="1"
                                                                    type="checkbox"
                                                                    class="custom-switch-input toggle-status">
                                                                <span class="custom-switch-indicator"></span>
                                                            </label>
                                                        </td>
                                                    @endif

                                                    <td>
                                                        <label class="custom-switch mt-2">
                                                            <input {{ $item->status === 1 ? 'checked' : '' }}
                                                                data-id="{{ $item->id }}" data-name="status"
                                                                data-checked="{{ $item->status }}" value="1"
                                                                type="checkbox" class="custom-switch-input toggle-status">
                                                            <span class="custom-switch-indicator"></span>
                                                        </label>
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
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('admin/assets/modules/sweetalert/sweetalert.min.js') }}"></script>
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

            $('.toggle-status').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let initialChecked = $(this).data('checked');
                let status = $(this).prop('checked') ? 1 : 0;

                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.toggle-news-status') }}",
                    data: {
                        id: id,
                        name: name,
                        status: status
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            swal({
                                title: data.message,
                                icon: 'success',
                            })
                        } else if (data.status === 'error') {
                            swal({
                                title: data.message,
                                icon: 'error',
                            });

                            $('.toggle-status[data-id="' + id + '"]').prop('checked',
                                initialChecked);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            });

            $('.delete-item').on('click', function(e) {
                e.preventDefault();
                swal({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            let url = $(this).attr('href');
                            $.ajax({
                                method: 'DELETE',
                                url: url,
                                success: function(data) {
                                    if (data.status === 'success') {
                                        swal({
                                            title: data.message,
                                            icon: 'success',
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else if (data.status === 'error') {
                                        swal({
                                            title: data.message,
                                            icon: 'error',
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        }
                    });
            });
        })
    </script>
@endpush
