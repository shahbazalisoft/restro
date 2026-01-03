@extends('layouts.vendor.app')

@section('title', translate('messages.qr_setup'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <img class="onerror-image" data-onerror-image="{{ asset('/public/assets/admin/img/grocery.svg') }}"
                            src="{{ asset('/public/assets/admin/img/grocery.svg') }}" width="38" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title mb-0">{{ translate('messages.QR_Manage_And_Generate') }}.</h1>
                            <p class="page-header-text m-0">
                                {{ translate('Hello, Here You Can Manage Your QR Scanner, After activate QR we will provide you activated QR Scanner') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-auto min--280">
                    <button type="button" class="btn btn--primary font-regular" data-bs-toggle="modal"
                            data-bs-target="#new_menu" onclick="openModal()"><i
                                class="tio-add-circle-outlined"></i>{{ translate('messages.preview_qr_scanner') }}</button>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row g-3 text-capitalize">
            <!-- Earnings (Monthly) Card Example -->
             @foreach ($row as $val)
            <div class="col-md-4">
                <div class="col-lg-12 col-md-9">
                    <a class="form-alert" href="javascript:" data-id="food-{{$val->id}}"
                        data-message="{{ translate('Want to active this qr-scanner ?') }}"
                        title="{{ translate('messages.Change_QR_Scanner') }}">
                        <img 
                        @if ($val->template)
                        src="{{asset('storage/app/public/qrcodes')}}/{{$val->template}}" 
                        @else
                        src="{{ asset('public/assets/admin/img/100x100/2.png') }}"
                        @endif
                         alt="img">
                    </a>
                    <form action="{{route('vendor.business-settings.menu_change_status',[$val->id])}}"
                            method="post" id="food-{{$val->id}}">
                        @csrf @method('patch')
                    </form>
                    <h5 class=" text-center cash--subtitle text-white">
                        @if ($val->store_menu)
                            {{ translate('messages.active') }}
                        @else
                            {{ translate('messages.inactive') }}
                        @endif
                    </h5>
                </div>
            </div>
            @endforeach
        </div>


    </div>


@endsection

@push('script_2')
@endpush
