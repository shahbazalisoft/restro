@extends('layouts.vendor.app')

@section('title', translate('messages.menu'))

@push('css_or_js')
@endpush

@section('content')

@section('content')
    <div id="content-disable" class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/category.png') }}" class="w--20" alt="">
                </span>
                <span>
                    {{ translate('menu_list') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card mt-3">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{ translate('messages.menu_list') }}<span class="badge badge-soft-dark ml-2"
                            id="itemCount">{{ $categories->total() }}</span></h5>

                    <form class="search-form w-340-lg">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input type="search" name="search" value="{{ request()?->search ?? null }}"
                                class="form-control h-40" placeholder="{{ translate('messages.search_categories') }}"
                                aria-label="{{ translate('messages.ex_:_categories') }}">
                            <input type="hidden" name="position" value="0">
                            <button type="submit" class="btn btn--primary h-40"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    @if (request()->get('search'))
                        <button type="reset" class="btn btn--primary ml-2 location-reload-to-category"
                            data-url="{{ url()->full() }}">{{ translate('messages.reset') }}</button>
                    @endif
                    <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white text-title dropdown-toggle font-medium min-height-40"
                            href="javascript:;"
                            data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                            <i class="tio-download-to mr-1 text-title"></i> {{ translate('messages.export') }}
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                            <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                            <a id="export-excel" class="dropdown-item" href="">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item" href="">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn--primary font-regular" data-bs-toggle="modal"
                            data-bs-target="#new_menu" onclick="openModal()"><i
                                class="tio-add-circle-outlined"></i>{{ translate('messages.New_Category') }}</button>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-align-middle"
                        data-hs-datatables-options='{
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="bg-table-head">
                            <tr>
                                <th class=" text-title border-0">{{ translate('sl') }}</th>
                                <th class=" text-title border-0">{{ translate('messages.image') }}</th>
                                <th class=" text-title border-0 w--1">{{ translate('messages.name') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.status') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.featured') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.priority') }}</th>
                                <th class=" text-title border-0 text-center">{{ translate('messages.action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                            @foreach ($categories as $key => $category)
                                <tr>
                                    <td>{{ $key + $categories->firstItem() }}</td>
                                    {{-- <td>{{ $category->id }}</td> --}}
                                    <td>
                                        <img class="avatar avatar-lg mr-3 onerror-image" src="{{asset('storage/app/public/category')}}/{{ $category->image }}"
                                         data-onerror-image="{{asset('public/assets/admin/img/160x160/img2.jpg')}}" alt="{{$category->name}} image">
                                    </td>
                                    <td>
                                        <span class="d-block fs-14 d-block text-title max-w-250 min-w-160">
                                            {{ Str::limit($category['name'], 20, '...') }}
                                        </span>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm"
                                            for="stocksCheckbox{{ $category->id }}">
                                            <input type="checkbox"
                                                data-url="{{ route('vendor.category.status', [$category['id'], $category->status ? 0 : 1]) }}"
                                                class="toggle-switch-input redirect-url"
                                                id="stocksCheckbox{{ $category->id }}"
                                                {{ $category->status ? 'checked' : '' }}>
                                            <span class="toggle-switch-label mx-auto">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm"
                                            for="featuredCheckbox{{ $category->id }}">
                                            <input type="checkbox" data-id="featuredCheckbox{{ $category->id }}"
                                                data-type="status"
                                                data-image-on="{{ asset('/public/assets/admin/img/status-ons.png') }}"
                                                data-image-off="{{ asset('/public/assets/admin/img/off-danger.png') }}"
                                                data-title-on="{{ translate('Do you want to Featured this category ?') }}"
                                                data-title-off="{{ translate('Do you want to remove this category from featured ?') }}"
                                                data-text-on="<p>{{ translate('If you turn on this category as a featured category it will show in customer app landing page.') }}"
                                                data-text-off="<p>{{ translate('If you turn off this category from featured category it will not show in customer app landing page.') }}</p>"
                                                class="toggle-switch-input dynamic-checkbox"
                                                id="featuredCheckbox{{ $category->id }}"
                                                {{ $category->featured ? 'checked' : '' }}>
                                            <span class="toggle-switch-label mx-auto">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                        <form action="" method="get" id="featuredCheckbox{{ $category->id }}_form">
                                        </form>
                                    </td>



                                    <td>
                                        <form action="" class="priority-form">
                                            <select name="priority" id="priority"
                                                class="form-control form--control-select  priority-select  mx-auto {{ $category->priority == 0 ? 'text-title' : '' }} {{ $category->priority == 1 ? 'text-info' : '' }} {{ $category->priority == 2 ? 'text-success' : '' }}">
                                                <option value="0" class="text--title"
                                                    {{ $category->priority == 0 ? 'selected' : '' }}>
                                                    {{ translate('messages.normal') }}</option>
                                                <option value="1" class="text--title"
                                                    {{ $category->priority == 1 ? 'selected' : '' }}>
                                                    {{ translate('messages.medium') }}</option>
                                                <option value="2" class="text--title"
                                                    {{ $category->priority == 2 ? 'selected' : '' }}>
                                                    {{ translate('messages.high') }}</option>
                                            </select>
                                        </form>

                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">

                                            <a class="btn action-btn btn-outline-theme-dark offcanvas-trigger"
                                                href="{{route('vendor.category.edit',[$category['id']])}}" data-id="{{ $category['id'] }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger form-alert"
                                                href="javascript:" data-id="category-{{ $category['id'] }}"
                                                data-message="{{ translate('Want to delete this category') }}"
                                                title="{{ translate('messages.delete_category') }}"><i
                                                    class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{ route('vendor.category.delete', [$category['id']]) }}"
                                                method="post" id="category-{{ $category['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if (count($categories) !== 0)
                <hr>
            @endif

            @if (count($categories) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div>
                        {!! $categories->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="offcanvas__categoryBtn" class="custom-offcanvas d-flex flex-column justify-content-between">
        <div id="data-view" class="h-100">
        </div>
    </div>
    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>

    {{-- Model Popup Start --}}
    <div class="modal fade" id="new_menu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.add_new_menu') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addMenuForm">
                    <div class="modal-body">
                        <input name="position" value="0" class="initial-hidden">
                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_name">{{ translate('messages.menu') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="{{ translate('messages.new_menu') }}" >
                            <span class="text-danger errorMsg" id="name_error"></span>
                        </div>

                        <div class="form-group" id="new_category_group">
                            <label class="input-label" for="default_image">{{ translate('messages.menu_image') }} <small
                                    class="text-danger">* ( {{ translate('messages.ratio') }} 1:1)</small></label>
                            <label class="text-center my-auto position-relative d-inline-block">
                                <img class="img--176 border" id="viewer"
                                    src="{{ asset('public/assets/admin/img/upload-img.png') }}" alt="image" />
                                <div class="icon-file-group">
                                    <div class="icon-file">
                                        <input type="file" name="image" id="image"
                                            class="custom-file-input this-url  read-url"
                                            accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <i class="tio-edit"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <span class="text-danger errorMsg" id="image_error"></span>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ translate('messages.close') }}</button>
                        <div class="loaderBtn">
                            <button type="submit" class="btn btn-primary" >{{ translate('messages.save_changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Model Popup End --}}

@endsection

@push('script_2')
    {{-- <script src="{{ asset('public/assets/admin') }}/js/view-pages/category-index.js"></script> --}}
    <script>
        function openModal() {
            $(".errorMsg").html('');
            $('#new_menu').modal('show');
        }
        $('#addMenuForm').on('submit', function(e) {
            e.preventDefault();
        // function addNewMenu() {
            $(".errorMsg").html('');
            let name = $("#name").val().trim();
            let image = $("#image").val();
            let category_type = $('input[name="category_type"]:checked').val();
            let exist_category = $("#exist_category").val();

            if (name === "") {
                $('#name_error').html('{{ translate('messages.menu_field_is_required') }}');
                return false;
            }
            if (image === "") {
                $('#image_error').html('{{ translate('messages.image_field_is_required') }}');
                return false;
            }
            let formData = new FormData(this);
            let url = "{{ route('vendor.category.store') }}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: formData,
                contentType: false, // IMPORTANT
                processData: false, // IMPORTANT
                beforeSend: function() {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button> </div>');
                },
                success: function(response) {
                    // $(".loaderBtn").html('<button type="button" class="btn btn-primary" onClick="update_request_category()">Save changes</button>');

                    if (response.status) {
                        $("#addMenuForm")[0].reset();
                        $('#new_menu').modal('hide');
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 4000);
                    } else {
                        toastr.error(response.message);
                    }

                },
                error: function (xhr) {
                    handleValidationErrors(xhr);
                },
            });
        });
        function handleValidationErrors(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function (key, value) {
                    $('#' + key + '_error').html(value[0]);
                });
            } else {
                toastr.error('Something went wrong');
            }
        }

        "use strict";
        $('.location-reload-to-category').on('click', function() {
            const url = $(this).data('url');
            let nurl = new URL(url);
            nurl.searchParams.delete('search');
            location.href = nurl;
        });

        $("#customFileEg1").change(function() {
            readURL(this);
            $('#viewer').show(1000)
        });

        $('#reset_btn').click(function() {
            $('#exampleFormControlSelect1').val(null).trigger('change');
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload-img.png') }}");
        })

        function fetch_data(id, url) {
            $.ajax({
                url: url,
                type: "get",
                beforeSend: function() {
                    $('#data-view').empty();
                    $('#loading').show()
                },
                success: function(data) {
                    $("#data-view").append(data.view);
                    initLangTabs();
                    initSelect2Dropdowns();
                },
                complete: function() {
                    $('#loading').hide()
                }
            })
        }


        function initLangTabs() {
            const langLinks = document.querySelectorAll(".lang_link1");
            langLinks.forEach(function(langLink) {
                langLink.addEventListener("click", function(e) {
                    e.preventDefault();
                    langLinks.forEach(function(link) {
                        link.classList.remove("active");
                    });
                    this.classList.add("active");
                    document.querySelectorAll(".lang_form1").forEach(function(form) {
                        form.classList.add("d-none");
                    });
                    let form_id = this.id;
                    let lang = form_id.substring(0, form_id.length - 5);
                    $("#" + lang + "-form1").removeClass("d-none");
                    if (lang === "default") {
                        $(".default-form1").removeClass("d-none");
                    }
                });
            });
        }

        function initSelect2Dropdowns() {
            $('.js-select2-custom1').select2({
                placeholder: 'Select tax rate',
                allowClear: true
            });

            $('.offcanvas-close, #offcanvasOverlay').on('click', function() {
                $('.custom-offcanvas').removeClass('open');
                $('#offcanvasOverlay').removeClass('show');
                $('#content-disable').removeClass('disabled');
            });
        }
    </script>
@endpush
