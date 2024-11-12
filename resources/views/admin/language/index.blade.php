@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('admin/assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('admin.Language') }}</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>{{ __('admin.All Languages') }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.language.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ _('Create') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            #
                                        </th>
                                        <th>{{ __('admin.Language Name') }}</th>
                                        <th>{{ __('admin.Language Code') }}</th>
                                        <th>{{ __('admin.Default') }}</th>
                                        <th>{{ __('admin.Status') }}</th>
                                        <th>{{ __('admin.Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($languages as $language)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $language->name }}</td>
                                            <td>{{ $language->lang }}</td>

                                            <td>
                                                @if ($language->default == 1)
                                                    <span class="badge badge-primary">{{ __('admin.Default') }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ __('admin.No') }}</span>
                                                @endif

                                            </td>
                                            <td>
                                                @if ($language->status == 1)
                                                    <span class="badge badge-success">{{ __('admin.Active') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __('admin.Inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.language.edit', $language->id) }}"
                                                    class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.language.destroy', $language->id) }}"
                                                    class="btn btn-danger delete-item"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
    <script>
        $(document).ready(function() {
            $("#table-1").DataTable({
                "columnDefs": [{
                    "sortable": false,
                    "targets": [3, 4, 5]
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
        });
    </script>
@endpush
