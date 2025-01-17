@push('css')
    <link rel="stylesheet"
        href="{{ asset('admin/assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
@endpush
<div class="card border border-primary">
    <div class="card-body">
        <form action="{{ route('admin.appearance-setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-lg-12">
                <div class="form-group">
                    <label>{{ __('admin.Pick Your Color') }}</label>
                    <div class="input-group colorpickerinput">
                        <input value="{{ $settings['site_color'] }}" name="site_color" type="text"
                            class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-fill-drip"></i>
                            </div>
                        </div>
                        @error('site_color')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('admin.Save') }}</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('admin/assets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $(".colorpickerinput").colorpicker({
            format: 'hex',
            component: '.input-group-append',
        });
    </script>
@endpush
