@extends('admin.layouts.master')
<link rel="stylesheet" href="{{ asset('admin/assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('admin.Social Links') }}</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>{{ __('admin.All Links') }}</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.social-link.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('admin.Create') }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-sub">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    #
                                </th>
                                <th>{{ __('admin.Icon') }}</th>
                                <th>{{ __('admin.Url') }}</th>
                                <th>{{ __('admin.Status') }}</th>
                                <th>{{ __('admin.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($socialLinks as $link)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><i style="font-size:30px" class="{{ $link->icon }}"></i></td>
                                    <td>{{ $link->url }}</td>
                                    <td>
                                        @if ($link->status === 1)
                                            <span class="badge badge-success">{{ __('admin.Yes') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('admin.No') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.social-link.edit', $link->id) }}"
                                            class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin.social-link.destroy', $link->id) }}"
                                            class="btn btn-danger delete-item"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        $("#table-sub").dataTable({
            "columnDefs": [{
                "sortable": false,
                "targets": [1]
            }]
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
                                    swal(data.message, {
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
    </script>
@endpush
