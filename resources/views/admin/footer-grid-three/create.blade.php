@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/modules/select2/dist/css/select2.min.css') }}">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('admin.Footer') }}</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>{{ __('admin.Footer Grid Three') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.footer-grid-three.store') }}" method="POST">
                    @csrf
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="">{{ __('admin.Language') }}</label>
                            <select name="language" id="language-select" class="form-control select2" style="width: 100%">
                                <option value="">--{{ __('admin.Select') }}--</option>
                                @foreach ($languages as $lang)
                                    <option value="{{ $lang->lang }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                            @error('language')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('admin.Name') }}</label>
                            <input name="name" type="text" class="form-control" id="name">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('admin.Url') }}</label>
                            <input name="url" type="text" class="form-control">
                            @error('url')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('admin.Status') }}</label>
                            <select name="status" id="" class="form-control">
                                <option value="1">{{ __('admin.Active') }}</option>
                                <option value="0">{{ __('admin.Inactive') }}</option>
                            </select>
                            @error('status')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('admin.Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('admin/assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
@endpush
