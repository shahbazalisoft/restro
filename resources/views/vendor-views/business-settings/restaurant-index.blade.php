@extends('layouts.vendor.app')

@section('title', translate('messages.settings'))



@section('content')
    <div class="content container-fluid config-inline-remove-class">
        <!-- Page Heading -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/config.png') }}" class="w--30" alt="">
                </span>
                <span>
                    {{ translate('messages.store_setup') }}
                </span>
            </h1>
        </div>
        <!-- Page Heading -->
        <div class="card mb-3">
            <div class="card-body py-3">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <h4 class="card-title align-items-center d-flex">
                        <img src="{{ asset('public/assets/admin/img/store.png') }}" class="w--20 mr-1" alt="">
                        <span>{{ translate('messages.store_temporarily_closed_title') }}</span>
                    </h4>
                    <label class="switch toggle-switch-lg m-0" for="restaurant-open-status">
                        <input type="checkbox" id="restaurant-open-status"
                            class="toggle-switch-input restaurant-open-status" {{ $store->active ? '' : 'checked' }}>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon">
                        <img class="w--22" src="{{ asset('public/assets/admin/img/store.png') }}" alt="">
                    </span>
                    <span class="p-md-1"> {{ translate('messages.store_meta_data') }}</span>
                </h5>
            </div>
            @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
            @php($language = $language->value ?? null)
            @php($defaultLang = 'en')
            <div class="card-body">
                <form action="{{ route('vendor.business-settings.update-meta-data', [$store['id']]) }}" method="post"
                    enctype="multipart/form-data" class="col-12">
                    @csrf
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <div class="card shadow--card-2">
                                <div class="card-body">
                                    @if ($language)
                                        <ul class="nav nav-tabs mb-4">
                                            <li class="nav-item">
                                                <a class="nav-link lang_link active" href="#"
                                                    id="default-link">{{ translate('Default') }}</a>
                                            </li>
                                            @foreach (json_decode($language) as $lang)
                                                <li class="nav-item">
                                                    <a class="nav-link lang_link" href="#"
                                                        id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if ($language)
                                        <div class="lang_form" id="default-form">
                                            <div class=" ">
                                                <label class="input-label"
                                                    for="default_title">{{ translate('messages.meta_title') }}
                                                    ({{ translate('messages.Default') }})
                                                    <span class="form-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('This title appears in browser tabs, search results, and link previews.Use a short, clear, and keyword-focused title (recommended: 50–60 characters)') }}">
                                                        <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                            alt="">
                                                    </span>
                                                </label>
                                                <input type="text" name="meta_title[]" id="default_title"
                                                    class="form-control" maxlength="60"
                                                    placeholder="{{ translate('messages.meta_title') }}"
                                                    value="{{ $store->getRawOriginal('meta_title') }}">
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                            <div class="mt-2">
                                                <label class="input-label"
                                                    for="meta_description">{{ translate('messages.meta_description') }}
                                                    ({{ translate('messages.default') }})
                                                    <span class="form-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('A brief summary that appears under your page title in search results.Keep it compelling and relevant (recommended: 120–160 characters)') }}">
                                                        <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                            alt="">
                                                    </span>
                                                </label>
                                                <textarea type="text" maxlength="160" id="meta_description" name="meta_description[]"
                                                    placeholder="{{ translate('messages.meta_description') }}" class="form-control min-h-90px ckeditor">{{ $store->getRawOriginal('meta_description') }}</textarea>
                                            </div>
                                        </div>
                                        @foreach (json_decode($language) as $lang)
                                            <?php
                                            if (count($store['translations'])) {
                                                $translate = [];
                                                foreach ($store['translations'] as $t) {
                                                    if ($t->locale == $lang && $t->key == 'meta_title') {
                                                        $translate[$lang]['meta_title'] = $t->value;
                                                    }
                                                    if ($t->locale == $lang && $t->key == 'meta_description') {
                                                        $translate[$lang]['meta_description'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="d-none lang_form" id="{{ $lang }}-form">
                                                <div class=" ">
                                                    <label class="input-label"
                                                        for="{{ $lang }}_title">{{ translate('messages.meta_title') }}
                                                        ({{ strtoupper($lang) }})
                                                        <span class="form-label-secondary" data-toggle="tooltip"
                                                            data-placement="right"
                                                            data-original-title="{{ translate('This title appears in browser tabs, search results, and link previews.Use a short, clear, and keyword-focused title (recommended: 50–60 characters)') }}">
                                                            <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                                alt="">
                                                        </span>
                                                    </label>
                                                    <input type="text" name="meta_title[]" maxlength="60"
                                                        id="{{ $lang }}_title" class="form-control"
                                                        value="{{ $translate[$lang]['meta_title'] ?? '' }}"
                                                        placeholder="{{ translate('messages.meta_title') }}">
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{ $lang }}">
                                                <div class="mt-2">
                                                    <label class="input-label"
                                                        for="meta_description{{ $lang }}">{{ translate('messages.meta_description') }}
                                                        ({{ strtoupper($lang) }})
                                                        <span class="form-label-secondary" data-toggle="tooltip"
                                                            data-placement="right"
                                                            data-original-title="{{ translate('A brief summary that appears under your page title in search results.Keep it compelling and relevant (recommended: 120–160 characters)') }}">
                                                            <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                                alt="">
                                                        </span>
                                                    </label>
                                                    <textarea maxlength="160" id="meta_description{{ $lang }}" type="text" name="meta_description[]"
                                                        placeholder="{{ translate('messages.meta_description') }}" class="form-control min-h-90px ckeditor">{{ $translate[$lang]['meta_description'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div id="default-form">
                                            <div class=" ">
                                                <label class="input-label"
                                                    for="meta_title">{{ translate('messages.meta_title') }}
                                                    ({{ translate('messages.default') }})</label>
                                                <input type="text" id="meta_title" name="meta_title[]"
                                                    class="form-control"
                                                    placeholder="{{ translate('messages.meta_title') }}">
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                            <div class="">
                                                <label class="input-label"
                                                    for="meta_description">{{ translate('messages.meta_description') }}
                                                </label>
                                                <textarea type="text" id="meta_description" name="meta_description[]"
                                                    placeholder="{{ translate('messages.meta_description') }}" class="form-control min-h-90px ckeditor"></textarea>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card shadow--card-2">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <span class="card-header-icon mr-1"><i class="tio-dashboard"></i></span>
                                        <span>{{ translate('store_meta_image') }}</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-center flex-wrap flex-sm-nowrap __gap-12px">
                                        <label class="__custom-upload-img mr-lg-5">
                                            <label class="form-label">
                                                {{ translate('meta_image') }} <span
                                                    class="text--primary">({{ translate('2:1') }})</span>
                                                <span class="form-label-secondary" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="{{ translate('This image is used as a preview thumbnail when the page link is shared on social media or messaging platforms.') }}">
                                                    <img src="{{ asset('public/assets/admin/img/info-circle.svg') }}"
                                                        alt="">
                                                </span>
                                            </label>
                                            <div class="text-center">
                                                <img class="img--110 min-height-170px min-width-170px onerror-image"
                                                    id="viewer"
                                                    data-onerror-image="{{ asset('public/assets/admin/img/upload.png') }}"
                                                    src="{{ $store->meta_image_full_url }}"
                                                    alt="{{ translate('meta_image') }}" />
                                            </div>
                                            <input type="file" name="meta_image" id="customFileEg1"
                                                class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="text-center">
                                            <small>{{ translate('Upload a rectangular image (recommended size: 800×400 px, format: JPG or PNG)') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="justify-content-end btn--container">
                                <button type="submit" class="btn btn--primary">{{ translate('save_changes') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.Create Schedule For ') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="javascript:" method="post" id="add-schedule">
                            @csrf
                            <input type="hidden" name="day" id="day_id_input">
                            <div class=" ">
                                <label for="recipient-name"
                                    class="col-form-label">{{ translate('messages.Start time') }}:</label>
                                <input type="time" id="recipient-name" class="form-control" name="start_time"
                                    required>
                            </div>
                            <div class=" ">
                                <label for="message-text"
                                    class="col-form-label">{{ translate('messages.End time') }}:</label>
                                <input type="time" id="message-text" class="form-control" name="end_time" required>
                            </div>
                            <div class="btn--container mt-4 justify-content-end">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit"
                                    class="btn btn--primary">{{ translate('messages.Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create schedule modal -->

@endsection

@push('script_2')
    <script>
        "use strict";

        $(document).on('click', '.restaurant-open-status', function(event) {


            event.preventDefault();
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: '{{ $store->active ? translate('messages.you_want_to_temporarily_close_this_store') : translate('messages.you_want_to_open_this_store') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#00868F',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {

                    $.get({
                        url: '{{ route('vendor.business-settings.update-active-status') }}',
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        success: function(data) {
                            toastr.success(data.message);
                        },
                        complete: function() {
                            location.reload();
                            $('#loading').hide();
                        },
                    });
                } else {
                    event.checked = !event.checked;
                }
            })

        });



        $(document).on('click', '.delete-schedule', function() {
            let route = $(this).data('url');
            Swal.fire({
                title: '{{ translate('Want_to_delete_this_schedule?') }}',
                text: '{{ translate('If_you_select_Yes,_the_time_schedule_will_be_deleted.') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#00868F',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: route,
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        success: function(data) {
                            if (data.errors) {
                                for (let i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                $('#schedule').empty().html(data.view);
                                toastr.success(
                                    '{{ translate('messages.Schedule removed successfully') }}', {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                            }
                        },
                        error: function() {
                            toastr.error('{{ translate('messages.Schedule not found') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                    });
                }
            })
        });


        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#customFileEg1").change(function() {
            readURL(this);
        });

        $(document).on('ready', function() {
            $("#gst_status").on('change', function() {
                if ($("#gst_status").is(':checked')) {
                    $('#gst').removeAttr('readonly');
                } else {
                    $('#gst').attr('readonly', true);
                }
            });
        });

        $('#exampleModal').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let day_name = button.data('day');
            let day_id = button.data('dayid');
            let modal = $(this);
            modal.find('.modal-title').text('{{ translate('messages.Create Schedule For ') }} ' + day_name);
            modal.find('.modal-body input[name=day]').val(day_id);
        })

        $('#add-schedule').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('vendor.business-settings.add-schedule') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#schedule').empty().html(data.view);
                        $('#exampleModal').modal('hide');
                        toastr.success('{{ translate('messages.Schedule added successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function(XMLHttpRequest) {
                    toastr.error(XMLHttpRequest.responseText, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
