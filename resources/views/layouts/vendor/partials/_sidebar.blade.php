<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->

                @php($store_data = \App\CentralLogics\Helpers::get_store_data())
                <a class="navbar-brand" href="{{ route('vendor.dashboard') }}" aria-label="Front">
                    <img class="navbar-brand-logo initial--36  onerror-image"
                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                        src="{{ $store_data->logo_full_url }}" alt="Logo">
                    <img class="navbar-brand-logo-mini initial--36 onerror-image"
                        data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                        src="{{ $store_data->logo_full_url }}" alt="Logo">
                </a>
                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button"
                    class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                    <i class="tio-clear tio-lg"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->

                <div class="navbar-nav-wrap-content-left">
                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                            data-placement="right" title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                            data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

            </div>

            <!-- Content -->
            <div class="navbar-vertical-content text-capitalize bg--005555" id="navbar-vertical-content">
                <form class="sidebar--search-form">
                    <div class="search--form-group">
                        <button type="button" class="btn"><i class="tio-search"></i></button>
                        <input type="text" class="form-control form--control"
                            placeholder="{{ translate('messages.Search Menu...') }}" id="search-sidebar-menu">
                    </div>
                </form>
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor-panel') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('vendor.dashboard') }}"
                            title="{{ translate('messages.dashboard') }}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.item_section') }}">{{ translate('messages.product_management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <!-- Menu -->
                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('item'))
                        <li
                            class="navbar-vertical-aside-has-menu  {{ Request::is('vendor-panel/item/list') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('vendor.item.list') }}"
                                title="{{ translate('messages.category') }}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{ translate('messages.items') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('category'))
                        
                        <li
                            class="navbar-vertical-aside-has-menu  {{ request()->input('position') == 0 && Request::is('vendor/menu') || Request::is('vendor/menu/edit*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('vendor.category.index') }}"
                                title="{{ translate('messages.category') }}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{ translate('messages.menu') }}</span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('vendor/menu/sub-menu-list') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('vendor.category.sub-index') }}"
                                title="{{ translate('messages.sub_category') }}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{ translate('messages.sub_menu') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Menu -->

                    <!-- Store Setting -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.business_section') }}">{{ translate('messages.business_section') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('store_setup'))
                        <li
                            class="nav-item {{ Request::is('vendor-panel/business-settings/store-setup') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('vendor.business-settings.store-setup') }}"
                                title="{{ translate('messages.storeConfig') }}">
                                <span class="tio-settings nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.storeConfig') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('my_shop'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('vendor-panel/store/*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('vendor.shop.view') }}" title="{{ translate('messages.my_shop') }}">
                                <i class="tio-home nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.my_shop') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    <!-- Employee-->
                    @if (
                        \App\CentralLogics\Helpers::employee_module_permission_check('role') ||
                            \App\CentralLogics\Helpers::employee_module_permission_check('employee'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.employee_section') }}">{{ translate('messages.employee_section') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                    @endif

                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('role'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('vendor-panel/custom-role*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link" href=""
                                title="{{ translate('messages.employee_Role') }}">
                                <i class="tio-incognito nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.employee_Role') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('employee'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('vendor-panel/employee*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.employees') }}">
                                <i class="tio-user nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.employees') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('vendor-panel/employee*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('vendor-panel/employee/add-new') ? 'active' : '' }}">
                                    <a class="nav-link " href=""
                                        title="{{ translate('messages.add_new_Employee') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.add_new') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('vendor-panel/employee/list') ? 'active' : '' }}">
                                    <a class="nav-link " href=""
                                        title="{{ translate('messages.Employee_list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    <!-- End Employee -->
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>

@push('script_2')
    <script>
        $(window).on('load', function() {
            if ($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });

        var $rows = $('#navbar-vertical-content li');
        $('#search-sidebar-menu').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush
