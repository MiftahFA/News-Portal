@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/modules/select2/dist/css/select2.min.css') }}">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('admin.Home Section Setting') }}</h1>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>{{ __('admin.Home Section Setting') }}</h4>
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
                            $categories = \App\Models\Category::where('language', $language->lang)
                                ->orderByDesc('id')
                                ->get();
                            $homeSectionSetting = \App\Models\HomeSectionSetting::where(
                                'language',
                                $language->lang,
                            )->first();
                        @endphp
                        <div class="tab-pane fade show {{ $loop->index === 0 ? 'active' : '' }}"
                            id="home-{{ $language->lang }}" role="tabpanel" aria-labelledby="home-tab2">
                            <div class="card-body">
                                <form action="{{ route('admin.home-section-setting.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for=""
                                                class="col-lg-12 p-0">{{ __('admin.Category Section One') }}</label>
                                            <input type="hidden" name="language" value="{{ $language->lang }}">
                                            <select name="category_section_one" id="" class="form-control select2"
                                                style="width: 100%" style="width: 100%">
                                                <option value="">--{{ __('admin.Select') }}--</option>
                                                @foreach ($categories as $category)
                                                    <option
                                                        {{ @$homeSectionSetting->category_section_one == $category->id ? 'selected' : '' }}
                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for=""
                                                class="col-lg-12 p-0">{{ __('admin.Category Section Two') }}</label>
                                            <select name="category_section_two" id="" class="form-control select2"
                                                style="width: 100%" style="width: 100%">
                                                <option value="">--{{ __('admin.Select') }}--</option>
                                                @foreach ($categories as $category)
                                                    <option
                                                        {{ @$homeSectionSetting->category_section_two == $category->id ? 'selected' : '' }}
                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for=""
                                                class="col-lg-12 p-0">{{ __('admin.Category Section Three') }}</label>
                                            <select name="category_section_three" id=""
                                                class="form-control select2" style="width: 100%" style="width: 100%">
                                                <option value="">--{{ __('admin.Select') }}--</option>
                                                @foreach ($categories as $category)
                                                    <option
                                                        {{ @$homeSectionSetting->category_section_three == $category->id ? 'selected' : '' }}
                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for=""
                                                class="col-lg-12 p-0">{{ __('admin.Category Section Four') }}</label>
                                            <select name="category_section_four" id="" class="form-control select2"
                                                style="width: 100%" style="width: 100%">
                                                <option value="">--{{ __('admin.Select') }}--</option>
                                                @foreach ($categories as $category)
                                                    <option
                                                        {{ @$homeSectionSetting->category_section_four == $category->id ? 'selected' : '' }}
                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">{{ __('admin.Save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/assets/modules/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                swal({
                    title: "{{ $error }}",
                    icon: 'error',
                });
            @endforeach
        @endif
    </script>
@endpush
