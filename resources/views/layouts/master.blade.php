<<<<<<< Updated upstream
@php
    $clinicRole = session('clinic_role', 'Admin');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ClinicCare — Appointment System')</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cliniccare.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }">
    <div class="app-shell">
        @include('layouts.sidebar')

        <div class="app-main">
            @include('layouts.navbar')

            <main class="content">
                @yield('content')
                @yield('contents')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
=======
<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" class="light" data-header-styles="light" data-menu-styles="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Valex - Tailwind Admin Template </title>
    <meta name="description" content="A Tailwind CSS admin template is a pre-designed web page for an admin dashboard. Optimizing it for SEO includes using meta descriptions and ensuring it's responsive and fast-loading.">
    <meta name="keywords" content="dashboard,admin dashboard,template dashboard,html,html dashboard,admin dashboard template,admin template,tailwind ui,admin panel,html and css,html admin template,tailwind framework,html css javascript,tailwind css dashboard,dashboard html css,admin,template admin panel,dashboard html template">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/brand-logos/favicon.ico">

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Style Css -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Simplebar Css -->
    <link rel="stylesheet" href="../assets/libs/simplebar/simplebar.min.css">

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="../assets/libs/@simonwep/pickr/themes/nano.min.css">

</head>

<body>

  <!-- ========== Switcher  ========== -->
  <div id="hs-overlay-switcher" class="hs-overlay hidden ti-offcanvas ti-offcanvas-right !border-0" tabindex="-1">
      <div class="relative z-10 ti-offcanvas-header">
        <h5 class="ti-offcanvas-title">
          Switcher
        </h5>
        <button type="button"
          class="flex-shrink-0 p-0 transition-none ti-btn text-defaulttextcolor dark:text-defaulttextcolor/70 hover:text-gray-700 focus:ring-gray-400 focus:ring-offset-white dark:hover:text-white/80 dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
          data-hs-overlay="#hs-overlay-switcher">
          <span class="sr-only">Close modal</span>
          <i class="text-lg leading-none ri-close-circle-line"></i>
        </button>
      </div>
      <div class="ti-offcanvas-body !p-0 !border-b dark:border-white/10 z-10 relative !h-auto">
        <div class="flex rtl:space-x-reverse" aria-label="Tabs" role="tablist" role="tablist">
          <button type="button"
            class="hs-tab-active:bg-success/20 w-full !py-2 !px-4 hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-success dark:hs-tab-active:bg-success/20 dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-success -mb-px bg-white font-semibold text-center  text-defaulttextcolor dark:text-defaulttextcolor/70 rounded-none hover:text-gray-700 dark:bg-bodybg dark:border-white/10  active"
            id="switcher-item-1" data-hs-tab="#switcher-1" aria-controls="switcher-1" role="tab">
            Theme Style
          </button>
          <button type="button"
            class="hs-tab-active:bg-success/20 w-full !py-2 !px-4 hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-success dark:hs-tab-active:bg-success/20 dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-success -mb-px  bg-white font-semibold text-center  text-defaulttextcolor dark:text-defaulttextcolor/70 rounded-none hover:text-gray-700 dark:bg-bodybg dark:border-white/10  dark:hover:text-gray-300"
            id="switcher-item-2" data-hs-tab="#switcher-2" aria-controls="switcher-2" role="tab">
            Theme Colors
          </button>
        </div>
      </div>
      <div class="ti-offcanvas-body !h-full overflow-auto" id="switcher-body">
        <div id="switcher-1" role="tabpanel" aria-labelledby="switcher-item-1" class="space-y-6">
          <div>
            <p class="switcher-style-head">Theme Color Mode:</p>
            <div class="grid grid-cols-3 switcher-style">
              <div class="flex items-center">
                <input type="radio" name="theme-style" class="ti-form-radio" id="switcher-light-theme" checked>
                <label for="switcher-light-theme"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Light</label>
              </div>
              <div class="flex items-center">
                <input type="radio" name="theme-style" class="ti-form-radio" id="switcher-dark-theme">
                <label for="switcher-dark-theme"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Dark</label>
              </div>
            </div>
          </div>
          <div class="!mt-0">
            <p class="switcher-style-head">Directions:</p>
            <div class="grid grid-cols-3 switcher-style">
              <div class="flex items-center">
                <input type="radio" name="direction" class="ti-form-radio" id="switcher-ltr" checked>
                <label for="switcher-ltr" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">LTR</label>
              </div>
              <div class="flex items-center">
                <input type="radio" name="direction" class="ti-form-radio" id="switcher-rtl">
                <label for="switcher-rtl" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">RTL</label>
              </div>
            </div>
          </div>
          <div class="!mt-0">
            <p class="switcher-style-head">Navigation Styles:</p>
            <div class="grid grid-cols-3 switcher-style">
              <div class="flex items-center">
                <input type="radio" name="navigation-style" class="ti-form-radio" id="switcher-vertical" checked>
                <label for="switcher-vertical"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Vertical</label>
              </div>
              <div class="flex items-center">
                <input type="radio" name="navigation-style" class="ti-form-radio" id="switcher-horizontal">
                <label for="switcher-horizontal"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Horizontal</label>
              </div>
            </div>
          </div>
          <div class="!mt-0 navigation-menu-styles">
            <p class="switcher-style-head">Navigation Menu Style:</p>
            <div class="grid grid-cols-2 gap-2 switcher-style">
              <div class="flex">
                <input type="radio" name="navigation-data-menu-styles" class="ti-form-radio" id="switcher-menu-click"
                  >
                <label for="switcher-menu-click" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Menu
                  Click</label>
              </div>
              <div class="flex">
                <input type="radio" name="navigation-data-menu-styles" class="ti-form-radio" id="switcher-menu-hover">
                <label for="switcher-menu-hover" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Menu
                  Hover</label>
              </div>
              <div class="flex">
                <input type="radio" name="navigation-data-menu-styles" class="ti-form-radio" id="switcher-icon-click">
                <label for="switcher-icon-click" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Icon
                  Click</label>
              </div>
              <div class="flex">
                <input type="radio" name="navigation-data-menu-styles" class="ti-form-radio" id="switcher-icon-hover">
                <label for="switcher-icon-hover" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Icon
                  Hover</label>
              </div>
            </div>
          </div>
          <div class="!mt-0 sidemenu-layout-styles">
            <p class="switcher-style-head">Sidemenu Layout Syles:</p>
            <div class="grid grid-cols-2 gap-2 switcher-style">
              <div class="flex">
                <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-default-menu" checked>
                <label for="switcher-default-menu"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2 ">Default
                  Menu</label>
              </div>
              <div class="flex">
                <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-closed-menu">
                <label for="switcher-closed-menu" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2 ">
                  Closed
                  Menu</label>
              </div>
              <div class="flex">
                <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-icontext-menu">
                <label for="switcher-icontext-menu" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2 ">Icon
                  Text</label>
              </div>
              <div class="flex">
                <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-icon-overlay">
                <label for="switcher-icon-overlay" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2 ">Icon
                  Overlay</label>
              </div>
              <div class="flex">
                <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-detached">
                <label for="switcher-detached"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2 ">Detached</label>
              </div>
              <div class="flex">
                <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio" id="switcher-double-menu">
                <label for="switcher-double-menu" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Double
                  Menu</label>
              </div>
            </div>
          </div>
          <div class="!mt-0">
            <p class="switcher-style-head">Page Styles:</p>
            <div class="grid grid-cols-3 switcher-style">
              <div class="flex">
                <input type="radio" name="data-page-styles" class="ti-form-radio" id="switcher-regular" checked>
                <label for="switcher-regular"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Regular</label>
              </div>
              <div class="flex">
                <input type="radio" name="data-page-styles" class="ti-form-radio" id="switcher-classic">
                <label for="switcher-classic"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Classic</label>
              </div>
              <div class="flex">
                <input type="radio" name="data-page-styles" class="ti-form-radio" id="switcher-modern">
                <label for="switcher-modern"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2"> Modern</label>
              </div>
            </div>
          </div>
          <div class="!mt-0">
            <p class="switcher-style-head">Layout Width Styles:</p>
            <div class="grid grid-cols-3 switcher-style">
              <div class="flex">
                <input type="radio" name="layout-width" class="ti-form-radio" id="switcher-full-width" checked>
                <label for="switcher-full-width"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">FullWidth</label>
              </div>
              <div class="flex">
                <input type="radio" name="layout-width" class="ti-form-radio" id="switcher-boxed">
                <label for="switcher-boxed" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Boxed</label>
              </div>
            </div>
          </div>
          <div class="!mt-0">
            <p class="switcher-style-head">Menu Positions:</p>
            <div class="grid grid-cols-3 switcher-style">
              <div class="flex">
                <input type="radio" name="data-menu-positions" class="ti-form-radio" id="switcher-menu-fixed" checked>
                <label for="switcher-menu-fixed"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Fixed</label>
              </div>
              <div class="flex">
                <input type="radio" name="data-menu-positions" class="ti-form-radio" id="switcher-menu-scroll">
                <label for="switcher-menu-scroll"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Scrollable </label>
              </div>
            </div>
          </div>
          <div class="!mt-0">
            <p class="switcher-style-head">Header Positions:</p>
            <div class="grid grid-cols-3 switcher-style">
              <div class="flex">
                <input type="radio" name="data-header-positions" class="ti-form-radio" id="switcher-header-fixed" checked>
                <label for="switcher-header-fixed" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">
                  Fixed</label>
              </div>
              <div class="flex">
                <input type="radio" name="data-header-positions" class="ti-form-radio" id="switcher-header-scroll">
                <label for="switcher-header-scroll"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Scrollable
                </label>
              </div>
            </div>
          </div>
          <div class="!mt-0">
            <p class="switcher-style-head">Loader:</p>
            <div class="grid grid-cols-3 switcher-style">
              <div class="flex">
                <input type="radio" name="page-loader" class="ti-form-radio" id="switcher-loader-enable" checked>
                <label for="switcher-loader-enable" class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">
                  Enable</label>
              </div>
              <div class="flex">
                <input type="radio" name="page-loader" class="ti-form-radio" id="switcher-loader-disable">
                <label for="switcher-loader-disable"
                  class="font-semibold text-defaultsize text-defaulttextcolor dark:text-defaulttextcolor/70 ms-2">Disable
                </label>
              </div>
            </div>
        </div>
        </div>
        <div id="switcher-2" class="hidden" role="tabpanel" aria-labelledby="switcher-item-2">
          <div class="theme-colors">
            <p class="switcher-style-head">Menu Colors:</p>
            <div class="flex space-x-3 switcher-style rtl:space-x-reverse">
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-white" type="radio" name="menu-colors"
                  id="switcher-menu-light" checked>
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Light Menu
                </span>
              </div>
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-dark" type="radio" name="menu-colors"
                  id="switcher-menu-dark">
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Dark Menu
                </span>
              </div>
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-primary" type="radio" name="menu-colors"
                  id="switcher-menu-primary">
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Color Menu
                </span>
              </div>
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-gradient" type="radio" name="menu-colors"
                  id="switcher-menu-gradient">
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Gradient Menu
                </span>
              </div>
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-transparent" type="radio" name="menu-colors"
                  id="switcher-menu-transparent">
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Transparent Menu
                </span>
              </div>
            </div>
            <div class="px-4 text-textmuted text-[.6875rem] !mb-3"><b class="me-2">Note:</b>If you want to change color Menu
              dynamically
              change from below Theme Primary color picker.</div>
          </div>
          <div class="theme-colors">
            <p class="switcher-style-head">Header Colors:</p>
            <div class="flex space-x-3 switcher-style rtl:space-x-reverse">
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-white !border" type="radio" name="header-colors"
                  id="switcher-header-light" checked>
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Light Header
                </span>
              </div>
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-dark" type="radio" name="header-colors"
                  id="switcher-header-dark">
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Dark Header
                </span>
              </div>
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-primary" type="radio" name="header-colors"
                  id="switcher-header-primary">
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Color Header
                </span>
              </div>
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-gradient" type="radio" name="header-colors"
                  id="switcher-header-gradient">
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Gradient Header
                </span>
              </div>
              <div class="hs-tooltip ti-main-tooltip ti-form-radio switch-select ">
                <input class="hs-tooltip-toggle ti-form-radio color-input color-transparent" type="radio"
                  name="header-colors" id="switcher-header-transparent">
                <span
                  class="hs-tooltip-content ti-main-tooltip-content !py-1 !px-2 !bg-black text-xs font-medium !text-white shadow-sm dark:!bg-black"
                  role="tooltip">
                  Transparent Header
                </span>
              </div>
            </div>
            <div class="px-4 text-textmuted text-[.6875rem] !mb-3"><b class="me-2">Note:</b>If you want to change color
              Header dynamically
              change from below Theme Primary color picker.</div>
          </div>
          <div class="theme-colors">
            <p class="switcher-style-head">Theme Primary:</p>
            <div class="flex space-x-3 switcher-style rtl:space-x-reverse">
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-primary-1" type="radio" name="theme-primary"
                  id="switcher-primary" checked>
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-primary-2" type="radio" name="theme-primary"
                  id="switcher-primary1">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-primary-3" type="radio" name="theme-primary"
                  id="switcher-primary2">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-primary-4" type="radio" name="theme-primary"
                  id="switcher-primary3">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-primary-5" type="radio" name="theme-primary"
                  id="switcher-primary4">
              </div>
              <div class="mt-1 ti-form-radio switch-select ps-0 color-primary-light">
                <div class="theme-container-primary"></div>
                <div class="pickr-container-primary"></div>
              </div>
            </div>
          </div>
          <div class="theme-colors">
            <p class="switcher-style-head">Theme Background:</p>
            <div class="flex space-x-3 switcher-style rtl:space-x-reverse">
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-bg-1" type="radio" name="theme-background"
                  id="switcher-background" checked>
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-bg-2" type="radio" name="theme-background"
                  id="switcher-background1">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-bg-3" type="radio" name="theme-background"
                  id="switcher-background2">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-bg-4" type="radio" name="theme-background"
                  id="switcher-background3">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio color-input color-bg-5" type="radio" name="theme-background"
                  id="switcher-background4">
              </div>
              <div class="mt-1 ti-form-radio switch-select ps-0 color-bg-transparent">
                <div class="hidden theme-container-background"></div>
                <div class="pickr-container-background"></div>
              </div>
            </div>
          </div>
          <div class="menu-image theme-colors">
            <p class="switcher-style-head">Menu With Background Image:</p>
            <div class="flex flex-wrap gap-3 space-x-3 switcher-style rtl:space-x-reverse">
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio bgimage-input bg-img1" type="radio" name="theme-images" id="switcher-bg-img">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio bgimage-input bg-img2" type="radio" name="theme-images" id="switcher-bg-img1">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio bgimage-input bg-img3" type="radio" name="theme-images" id="switcher-bg-img2">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio bgimage-input bg-img4" type="radio" name="theme-images" id="switcher-bg-img3">
              </div>
              <div class="ti-form-radio switch-select">
                <input class="ti-form-radio bgimage-input bg-img5" type="radio" name="theme-images" id="switcher-bg-img4">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="justify-between ti-offcanvas-footer sm:flex">
        <a href="javascript:void(0);" id="reset-all" class="w-full m-1 ti-btn ti-btn-danger-full">Reset</a>
      </div>
    </div>
    <!-- ========== END Switcher  ========== -->
  
      <!-- Loader -->
      <div id="loader" >
          <img src="../assets/images/media/loader.svg" alt="">
      </div>
      <!-- Loader -->

  <div class="page">

    <!-- Start::Header -->
    <header class="app-header">
      @include('layouts.navbar')
    </header>
    <!-- End::Header -->

    <!-- Start::Off-canvas sidebar-->
    <div id="hs-overlay-chat" class="hidden overflow-auto hs-overlay ti-offcanvas ti-offcanvas-right" tabindex="-1">
      <div class="ti-offcanvas-header !py-2 rounded-none">
        <h5 class="text-[.875rem] uppercase mb-0 text-defaulttextcolor font-semibold" id="sidebarLabel">Notifications</h5>
        <button type="button"
          class="flex-shrink-0 p-0 transition-none ti-btn text-defaulttextcolor dark:text-defaulttextcolor/70 hover:text-gray-700 focus:ring-gray-400 focus:ring-offset-white dark:hover:text-white/80 dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
          data-hs-overlay="#hs-overlay-chat">
          <span class="sr-only">Close modal</span>
          <i class="text-lg leading-none ri-close-fill"></i>
        </button>
      </div>
      <div class="p-0 rounded-none ti-offcanvas-body">
        <ul class="p-4 nav nav-tabs" role="tablist">
          <div class=" rtl:space-x-reverse" aria-label="Tabs" role="tablist" role="tablist">
            <button type="button"
              class="hs-tab-active:bg-primary w-full mb-2 rounded-[4px] !py-[10px] !px-[16px] text-start hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-white dark:hs-tab-active:bg-primary dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-white  bg-light  font-semibold  text-defaulttextcolor dark:text-defaulttextcolor/70  hover:text-gray-700 dark:bg-bodybg2 dark:border-white/10  active"
              id="chat-item" data-hs-tab="#chat" aria-controls="chat" role="tab">
              <i class="fe fe-message-circle text-[.9375rem] me-2 inline-flex"></i>Chat
            </button>
            <button type="button"
              class="hs-tab-active:bg-primary w-full mb-2  rounded-[4px] !py-[10px] !px-[16px] text-start hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-white dark:hs-tab-active:bg-primary dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-white   bg-light font-semibold  text-defaulttextcolor dark:text-defaulttextcolor/70  hover:text-gray-700 dark:bg-bodybg2 dark:border-white/10  dark:hover:text-gray-300"
              id="notification-item" data-hs-tab="#notification" aria-controls="notification" role="tab">
              <i class="fe fe-bell text-[.9375rem] me-2 inline-flex"></i> Notifications
            </button>
            <button type="button"
              class="hs-tab-active:bg-primary w-full mb-0 rounded-[4px] !py-[10px] !px-[16px] text-start hs-tab-active:border-b-transparent text-defaultsize border-0 hs-tab-active:text-white dark:hs-tab-active:bg-primary dark:hs-tab-active:border-b-white/10 dark:hs-tab-active:text-white   bg-light font-semibold  text-defaulttextcolor dark:text-defaulttextcolor/70  hover:text-gray-700 dark:bg-bodybg2 dark:border-white/10  dark:hover:text-gray-300"
              id="friends-item" data-hs-tab="#friends" aria-controls="friends" role="tab">
              <i class="fe fe-users text-[.9375rem] me-2 inline-flex"></i>Friends
            </button>
          </div>
        </ul>
        <div class="tab-content !border-0 ">
          <div
            class="tab-pane !text-defaulttextcolor dark:text-defaulttextcolor/70 !border-s-0 !border-e-0 !rounded-none !p-0 show border-defaultborder dark:border-defaultborder/10 "
            id="chat" role="tabpanel" aria-labelledby="chat-item">
            <div class="flex items-center p-3 border-b list border-defaultborder dark:border-defaultborder/10">
              <div class="">
                <span class="avatar bg-primary avatar-rounded avatar-md">CH</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="flex mb-0 ">
                  <b>New Websites is Created</b>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">30 mins ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="flex items-center p-3 border-b list border-defaultborder dark:border-defaultborder/10">
              <div class="">
                <span class="avatar bg-danger avatar-rounded avatar-md">N</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="flex mb-0 ">
                  <b>Prepare For the Next Project</b>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">2 hours ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="flex items-center p-3 border-b list border-defaultborder dark:border-defaultborder/10">
              <div class="">
                <span class="avatar bg-info avatar-rounded avatar-md">S</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="flex mb-0 ">
                  <b>Decide the live Discussion</b>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">3 hours ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="flex items-center p-3 border-b list border-defaultborder dark:border-defaultborder/10">
              <div class="">
                <span class="avatar bg-warning avatar-rounded avatar-md">K</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="flex mb-0 ">
                  <b>Meeting at 3:00 pm</b>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">4 hours ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="flex items-center p-3 border-b list border-defaultborder dark:border-defaultborder/10">
              <div class="">
                <span class="avatar bg-success avatar-rounded avatar-md">R</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="flex mb-0 ">
                  <b>Prepare for Presentation</b>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">1 day ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="flex items-center p-3 border-b list border-defaultborder dark:border-defaultborder/10">
              <div class="">
                <span class="avatar bg-pinkmain avatar-rounded avatar-md">MS</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="flex mb-0 ">
                  <b>Prepare for Presentation</b>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">1 day ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="flex items-center p-3 border-b list border-defaultborder dark:border-defaultborder/10">
              <div class="">
                <span class="avatar bg-purplemain avatar-rounded avatar-md">L</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="flex mb-0 ">
                  <b>Prepare for Presentation</b>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">45 minutes ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="flex items-center p-3 border-b list border-defaultborder dark:border-defaultborder/10">
              <div class="">
                <span class="avatar bg-indigomain avatar-rounded avatar-md">U</span>
              </div>
              <a class="w-full ms-3" href="javascript:void(0);">
                <p class="flex mb-0 ">
                  <b>Prepare for Presentation</b>
                </p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <i class="fa-regular fa-clock text-textmuted me-1 text-[.6875rem]"></i>
                    <small class="text-textmuted ms-auto">2 days ago</small>
                    <p class="mb-0"></p>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div
            class="tab-pane !text-defaulttextcolor dark:text-defaulttextcolor/70 !border-s-0 !border-e-0 !rounded-none !p-0 border-defaultborder dark:border-defaultborder/10  hidden"
            id="notification" role="tabpanel" aria-labelledby="notification-item">
            <div class="ti-list-group ti-list-group-flush ">
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="flex-shrink-0 avatar avatar-lg online avatar-rounded">
                  <img src="../assets/images/faces/1.jpg" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Madeleine</strong> Hey! there I' am available....
                  <div class="small text-textmuted">
                    3 hours ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="flex-shrink-0 avatar avatar-lg online avatar-rounded">
                  <img src="../assets/images/faces/2.jpg" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Anthony</strong> New product Launching...
                  <div class="small text-textmuted">
                    5 hour ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="flex-shrink-0 avatar avatar-lg avatar-rounded">
                  <img src="../assets/images/faces/3.jpg" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Olivia</strong> New Schedule Realease......
                  <div class="small text-textmuted">
                    45 minutes ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="flex-shrink-0 avatar avatar-lg avatar-rounded">
                  <img src="../assets/images/faces/4.jpg" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Madeleine</strong> Hey! there I' am available....
                  <div class="small text-textmuted">
                    3 hours ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="flex-shrink-0 avatar avatar-lg avatar-rounded">
                  <img src="../assets/images/faces/5.jpg" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Anthony</strong> New product Launching...
                  <div class="small text-textmuted">
                    5 hour ago
                  </div>
                </div>
              </div>
              <div class="ti-list-group-item !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="flex-shrink-0 avatar avatar-lg avatar-rounded">
                  <img src="../assets/images/faces/6.jpg" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Olivia</strong> New Schedule Realease......
                  <div class="small text-textmuted">
                    45 minutes ago
                  </div>
                </div>
              </div>
              <div
                class="ti-list-group-item  !border-b border-defaultborder dark:border-defaultborder/10 !border-s-0 !border-e-0 !border-t-0 flex  items-center">
                <span class="flex-shrink-0 avatar avatar-lg avatar-rounded">
                  <img src="../assets/images/faces/7.jpg" alt="img">
                </span>
                <div class="ms-3">
                  <strong>Olivia</strong> Hey! there I' am available....
                  <div class="small text-textmuted">
                    12 minutes ago
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            class="tab-pane !text-defaulttextcolor dark:text-defaulttextcolor/70 !border-s-0 !border-e-0 !rounded-none !p-0 border-defaultborder dark:border-defaultborder/10  active hidden"
            id="friends" role="tabpanel" aria-labelledby="friends-item">
            <div class="ti-list-group ti-list-group-flush ">
              <div class="ti-list-group-item flex !border-t-0 items-center">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/1.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Mozelle Belt</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md avatar-rounded">
                  <img src="../assets/images/faces/2.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Florinda Carasco</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md avatar-rounded">
                  <img src="../assets/images/faces/5.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Alina Bernier</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/6.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Zula Mclaughin</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/8.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Isidro Heide</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/8.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Mozelle Belt</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/9.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Florinda Carasco</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/10.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Alina Bernier</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md avatar-rounded">
                  <img src="../assets/images/faces/11.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Zula Mclaughin</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/12.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Isidro Heide</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/2.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Florinda Carasco</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/2.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Alina Bernier</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="flex items-center ti-list-group-item">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/3.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Zula Mclaughin</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
              <div class="ti-list-group-item flex !border-b border-defaultborder dark:border-defaultborder/10 items-center">
                <span class="flex-shrink-0 avatar avatar-md online avatar-rounded">
                  <img src="../assets/images/faces/4.jpg" alt="img">
                </span>
                <div class="ms-2">
                  <div class="font-semibold" data-hs-overlay="#chatmodel">Isidro Heide</div>
                </div>
                <div class="ms-auto">
                  <a href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-light" data-hs-overlay="#chatmodel"><i
                      class="fab fa-facebook-messenger"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End::Off-canvas sidebar-->

    <!--chat-modal-->
    <div class="hidden hs-overlay ti-modal" id="chatmodel">
      <div class="mt-0 ease-out hs-overlay-open:mt-7 ti-modal-box">
        <div class="ti-modal-content chat !border-0">
          <div class="box overflow-hidden !mb-0 !border-0 !shadow-none">
            <div class="flex items-center clearfix action-header">
              <div class="flex float-start xs:hidden">
                <div class="avatar avatar-lg rounded-circle me-3"> <img src="../assets/images/faces/6.jpg"
                    class="rounded-circle user_img" alt="img"> </div>
                <div class="items-center">
                  <h5 class="mb-0 text-fixed-white">Daneil Scott</h5> <span class="dot-label bg-success"></span><span
                    class="me-3 text-fixed-white">online</span>
                </div>
              </div>
              <ul class="items-center ah-actions actions ms-auto float-end">
                <li class="call-icon"> <a href="#" class="hidden md:block phone-button" data-hs-overlay="#audiomodal"> <i
                      class="fe fe-phone"></i> </a> </li>
                <li class="video-icon"> <a href="#" class="hidden md:block phone-button" data-hs-overlay="#videomodal"> <i
                      class="fe fe-video"></i> </a> </li>
                <li class="hs-dropdown ti-dropdown">
                  <a href="#" data-bs-toggle="dropdown" aria-expanded="true"> <i class="fe fe-more-vertical"></i> </a>
                  <ul class="hidden ti-dropdown-menu hs-dropdown-menu dropdown-menu-end">
                    <li class="ti-dropdown-item"><i class="fa fa-user-circle"></i> View profile</li>
                    <li class="ti-dropdown-item"><i class="fa fa-users"></i>Add friends</li>
                    <li class="ti-dropdown-item"><i class="fa fa-plus"></i> Add to group</li>
                    <li class="ti-dropdown-item"><i class="fa fa-ban"></i> Block</li>
                  </ul>
                </li>
                <li> <a href="" class="" data-bs-dismiss="modal" aria-label="Close"> <i
                      class="fe fe-x-circle text-fixed-white"></i> </a> </li>
              </ul>
            </div>
            <div class="box-body msg_card_body">
              <div class="chat-box-single-line"> <abbr
                  class="timestamp !text-defaulttextcolor dark:!text-defaulttextcolor/70">February 1st, 2019</abbr> </div>
              <div class="flex justify-start">
                <div class="img_cont_msg"> <img src="../assets/images/faces/6.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
                <div class="msg_cotainer"> Hi, how are you Jenna Side? <span class="msg_time">8:40 AM, Today</span> </div>
              </div>
              <div class="flex justify-end ">
                <div class="msg_cotainer_send"> Hi Connor Paige i am good tnx how about you? <span
                    class="msg_time_send">8:55 AM, Today</span> </div>
                <div class="img_cont_msg"> <img src="../assets/images/faces/9.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
              </div>
              <div class="flex justify-start ">
                <div class="img_cont_msg"> <img src="../assets/images/faces/6.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
                <div class="msg_cotainer"> I am good too, thank you for your chat template <span class="msg_time">9:00 AM,
                    Today</span> </div>
              </div>
              <div class="flex justify-end ">
                <div class="msg_cotainer_send"> You welcome Connor Paige <span class="msg_time_send">9:05 AM, Today</span>
                </div>
                <div class="img_cont_msg"> <img src="../assets/images/faces/9.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
              </div>
              <div class="flex justify-start ">
                <div class="img_cont_msg"> <img src="../assets/images/faces/6.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
                <div class="msg_cotainer"> Yo, Can you update Views? <span class="msg_time">9:07 AM, Today</span> </div>
              </div>
              <div class="flex justify-end mb-4">
                <div class="msg_cotainer_send"> But I must explain to you how all this mistaken born and I will give <span
                    class="msg_time_send">9:10 AM, Today</span> </div>
                <div class="img_cont_msg"> <img src="../assets/images/faces/9.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
              </div>
              <div class="flex justify-start ">
                <div class="img_cont_msg"> <img src="../assets/images/faces/6.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
                <div class="msg_cotainer"> Yo, Can you update Views? <span class="msg_time">9:07 AM, Today</span> </div>
              </div>
              <div class="flex justify-end mb-4">
                <div class="msg_cotainer_send"> But I must explain to you how all this mistaken born and I will give <span
                    class="msg_time_send">9:10 AM, Today</span> </div>
                <div class="img_cont_msg"> <img src="../assets/images/faces/9.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
              </div>
              <div class="flex justify-start ">
                <div class="img_cont_msg"> <img src="../assets/images/faces/6.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
                <div class="msg_cotainer"> Yo, Can you update Views? <span class="msg_time">9:07 AM, Today</span> </div>
              </div>
              <div class="flex justify-end mb-4">
                <div class="msg_cotainer_send"> But I must explain to you how all this mistaken born and I will give <span
                    class="msg_time_send">9:10 AM, Today</span> </div>
                <div class="img_cont_msg"> <img src="../assets/images/faces/9.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
              </div>
              <div class="flex justify-start">
                <div class="img_cont_msg"> <img src="../assets/images/faces/6.jpg" class="rounded-circle user_img_msg"
                    alt="img"> </div>
                <div class="msg_cotainer"> Okay Bye, text you later.. <span class="msg_time">9:12 AM, Today</span> </div>
              </div>
            </div>
            <div class="border-t box-footer">
              <div class="flex msb-reply">
                <div class="input-group"> <input type="text" class="form-control " placeholder="Typing...."> <button
                    type="button" class="ti-btn ti-btn-primary-full !mb-0"> <i class="far fa-paper-plane"
                      aria-hidden="true"></i> </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--chat-modal-->


    <!--Video Modal -->
    <div id="videomodal" class="hidden hs-overlay ti-modal">
      <div class="mt-0 ease-out hs-overlay-open:mt-7 ti-modal-box">
        <div class="ti-modal-content !bg-[#3b4863] !border-0">
          <div class="mx-auto text-center p-[3rem]">
            <button type="button"
              class="hs-dropdown-toggle relative -end-[226px] -top-[29px] !text-[1.5rem] !font-medium text-white"
              data-hs-overlay="#videomodal">
              <span class="sr-only">Close</span>
              <i class="bi bi-x"></i>
            </button>
            <h5 class="text-white">Valex Video call</h5>
            <img src="../assets/images/faces/6.jpg" class="rounded-full !h-[90px]  mt-4 mb-3 inline-flex" alt="img">
            <h4 class="mb-1 font-semibold text-white">Daneil Scott</h4>
            <h6 class="text-white loading animate-loadingtext">Calling...</h6>
            <div class="mt-[3rem] mb-[2rem]">
              <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-4">
                  <a class="mb-0 rounded-full icon icon-shape" href="javascript:void(0);">
                    <i class="fas fa-video-slash"></i>
                  </a>
                </div>
                <div class="col-span-4">
                  <a class="mb-0 text-white rounded-full icon icon-shape" href="javascript:void(0);"
                    data-hs-overlay="#videomodal">
                    <i class="fas fa-phone !bg-danger !text-white"></i>
                  </a>
                </div>
                <div class="col-span-4">
                  <a class="mb-0 rounded-full icon icon-shape" href="javascript:void(0);">
                    <i class="fas fa-microphone-slash"></i>
                  </a>
                </div>
              </div>
            </div>
          </div><!-- modal-body -->
        </div>
      </div><!-- modal-dialog -->
    </div>
    <!--End modal -->

    <!-- Audio Modal -->
    <div id="audiomodal" class="hidden hs-overlay ti-modal">
      <div class="mt-0 ease-out hs-overlay-open:mt-7 ti-modal-box">
        <div class="border-0 ti-modal-content">
          <div class="mx-auto text-center p-[3rem]">
            <button type="button"
              class="hs-dropdown-toggle relative -end-[226px] -top-[29px] !text-[1.5rem] !font-medium text-[#8c9097]"
              data-hs-overlay="#audiomodal">
              <span class="sr-only">Close</span>
              <i class="bi bi-x"></i>
            </button>
            <h6 class="text-defaulttextcolor dark:text-defaulttextcolor/70">Valex Voice call</h6>
            <img src="../assets/images/faces/6.jpg" class="rounded-full !h-[90px] mt-6 mb-4 inline-flex" alt="img">
            <h5 class="mb-1 font-medium text-defaulttextcolor dark:text-defaulttextcolor/70">Daneil Scott</h5>
            <h6 class="loading animate-loadingtext text-defaulttextcolor dark:text-defaulttextcolor/70">Calling...</h6>
            <div class="mt-[2rem] mb-[2rem]">
              <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-4">
                  <a class="mb-0 icon icon-shape rounded-circle" href="javascript:void(0);">
                    <i class="fas fa-volume-up !bg-light !text-defaulttextcolor"></i>
                  </a>
                </div>
                <div class="col-span-4">
                  <a class="mb-0 text-white icon icon-shape rounded-circle" href="javascript:void(0);"
                    data-hs-overlay="#audiomodal">
                    <i class="fas fa-phone text-white !bg-success"></i>
                  </a>
                </div>
                <div class="col-span-4">
                  <a class="mb-0 icon icon-shape rounded-circle" href="javascript:void(0);">
                    <i class="fas fa-microphone-slash !bg-light !text-defaulttextcolor"></i>
                  </a>
                </div>
              </div>
            </div>
          </div><!-- modal-body -->
        </div>
      </div><!-- modal-dialog -->
    </div>
    <!--End modal -->

    <!-- Start::app-sidebar -->
    <aside class="app-sidebar" id="sidebar">
    @include('layouts.sidebar')
    </aside>
    <!-- End::app-sidebar -->

    <div class="content">

      <!-- Start::main-content -->
      <div class="main-content">

        <!-- Start::page-header -->
        <!-- Page Header -->
        <div class="md:flex block items-center justify-between mb-6 mt-[2rem]  page-header-breadcrumb">
          <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">Personal</h5>
            <nav>
              <ol class="flex items-center min-w-0 whitespace-nowrap">
                <li class="text-[12px]"> <a class="flex items-center text-primary hover:text-primary"
                    href="javascript:void(0);"> Dashboard <i
                      class="flex-shrink-0 mx-3 overflow-visible ti ti-chevrons-right text-textmuted rtl:rotate-180"></i>
                  </a> </li>
                <li class="text-[12px]"> <a class="flex items-center text-textmuted"
                    href="javascript:void(0);">Personal 
                  </a> </li>
              </ol>
            </nav>
          </div>

          <div class="flex xl:my-auto right-content align-items-center">
            <div class="pe-1 xl:mb-0">
              <button type="button" class="ti-btn ti-btn-info-full text-white ti-btn-icon me-2 btn-b !mb-0">
                <i class="mdi mdi-filter-variant"></i>
              </button>
            </div>
            <div class="pe-1 xl:mb-0">
              <button type="button" class="ti-btn ti-btn-danger-full text-white ti-btn-icon me-2 !mb-0">
                <i class="mdi mdi-star"></i>
              </button>
            </div>
            <div class="pe-1 xl:mb-0">
              <button type="button" class="ti-btn ti-btn-warning-full text-white  ti-btn-icon me-2 !mb-0">
                <i class="mdi mdi-refresh"></i>
              </button>
            </div>
            <div class="xl:mb-0">
              <div class="hs-dropdown ti-dropdown">
                <button class="ti-btn ti-btn-primary-full text-white dropdown-toggle !mb-0" type="button" id="dropdownMenuDate"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  14 Aug 2019 <i class="bi bi-chevron-down text-[.6rem] font-semibold"></i>
                </button>
                <ul class="hs-dropdown-menu ti-dropdown-menu hidden !z-[100]" aria-labelledby="dropdownMenuDate">
                  <li><a class="ti-dropdown-item" href="javascript:void(0);">2015</a></li>
                  <li><a class="ti-dropdown-item" href="javascript:void(0);">2016</a></li>
                  <li><a class="ti-dropdown-item" href="javascript:void(0);">2017</a></li>
                  <li><a class="ti-dropdown-item" href="javascript:void(0);">2018</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- Page Header Close -->
        <!-- End::page-header -->
        <div class="grid grid-cols-12 gap-x-6">
          <div class="col-span-12 xxl:col-span-9">
            <div class="grid grid-cols-12 gap-x-6">
              <div class="col-span-12 xl:col-span-6 xxxl:col-span-3">
                <div class="box">
                  <div class="space-y-4 box-body">
                    <div class="flex items-center justify-between space-x-4 rtl:space-x-reverse">
                      <div>
                        <p class="text-textmuted text-[0.925rem] mb-2">Total Candidates</p>
                        <div class="flex items-center">
                          <h5 class="text-2xl font-medium ">125</h5> <span
                            class="text-sm text-success ltr:ml-2 rtl:mr-2"><i
                              class="ti ti-arrow-up-right"></i>+12.86%</span>
                        </div>
                      </div>
                      <div> <span class="p-3 text-white rounded-sm avatar bg-primary"> <i
                            class="text-xl leading-none ti ti-users"></i> </span> </div>
                    </div>
                    <div class="flex items-center !mt-1">
                      <div class="h-2 bg-gray-200 ti-main-progress dark:bg-black/20">
                        <div class="text-xs text-center text-white ti-main-progress-bar bg-primary" role="progressbar"
                          style="width:50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> <span class="text-sm font-medium ltr:ml-2 rtl:mr-2">50%</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-span-12 xl:col-span-6 xxxl:col-span-3">
                <div class="box">
                  <div class="space-y-4 box-body">
                    <div class="flex items-center justify-between space-x-4 rtl:space-x-reverse">
                      <div>
                        <p class="text-textmuted text-[0.925rem] mb-2">Recruiters</p>
                        <div class="flex items-center">
                          <h5 class="text-2xl font-medium ">968</h5> <span
                            class="text-sm text-success ltr:ml-2 rtl:mr-2"><i
                              class="ti ti-arrow-up-right"></i>+5.46%</span>
                        </div>
                      </div>
                      <div> <span class="p-3 text-white rounded-sm avatar bg-danger"> <i
                            class="text-xl leading-none ti ti-user-circle"></i> </span> </div>
                    </div>
                    <div class="flex items-center !mt-1">
                      <div class="h-2 bg-gray-200 ti-main-progress dark:bg-black/20">
                        <div class="ti-main-progress-bar !bg-danger text-xs text-white text-center" role="progressbar"
                          style="width:80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> <span class="text-sm font-medium ltr:ml-2 rtl:mr-2">80%</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-span-12 xl:col-span-6 xxxl:col-span-3">
                <div class="box">
                  <div class="space-y-4 box-body">
                    <div class="flex items-center justify-between space-x-4 rtl:space-x-reverse">
                      <div>
                        <p class="text-textmuted text-[0.925rem] mb-2">Total Subscriptions</p>
                        <div class="flex items-center">
                          <h5 class="text-2xl font-medium ">28</h5> <span
                            class="text-sm text-success ltr:ml-2 rtl:mr-2"><i
                              class="ti ti-arrow-up-right"></i>+3.20%</span>
                        </div>
                      </div>
                      <div> <span class="p-3 text-white rounded-sm avatar bg-success"> <i
                            class="text-xl leading-none ti ti-browser-check"></i> </span> </div>
                    </div>
                    <div class="flex items-center !mt-1">
                      <div class="h-2 bg-gray-200 ti-main-progress dark:bg-black/20">
                        <div class="ti-main-progress-bar !bg-success text-xs text-white text-center" role="progressbar"
                          style="width:65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> <span class="text-sm font-medium ltr:ml-2 rtl:mr-2">65%</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-span-12 xl:col-span-6 xxxl:col-span-3">
                <div class="box">
                  <div class="space-y-4 box-body">
                    <div class="flex items-center justify-between space-x-4 rtl:space-x-reverse">
                      <div>
                        <p class="text-textmuted text-[0.925rem] mb-2">Page Views</p>
                        <div class="flex items-center">
                          <h5 class="text-2xl font-medium ">645</h5> <span
                            class="text-sm text-success ltr:ml-2 rtl:mr-2"><i
                              class="ti ti-arrow-up-right"></i>+1.20%</span>
                        </div>
                      </div>
                      <div> <span class="p-3 text-white rounded-sm avatar bg-info"> <i
                            class="text-xl leading-none ti ti-view-360"></i> </span> </div>
                    </div>
                    <div class="flex items-center !mt-1">
                      <div class="h-2 bg-gray-200 ti-main-progress dark:bg-black/20">
                        <div class="ti-main-progress-bar !bg-info text-xs text-white text-center" role="progressbar"
                          style="width:65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> <span class="text-sm font-medium ltr:ml-2 rtl:mr-2">65%</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-span-12 xxl:col-span-4 xl:col-span-4">
                <div class="box">
                  <div class="box-header ">
                    <div class="flex justify-between">
                      <h4 class="mb-2 box-title">Recent Contacts</h4>
                      <div class="hs-dropdown ti-dropdown">
                        <a aria-label="anchor" href="javascript:void(0);"
                          class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                          data-bs-toggle="dropdown" aria-expanded="false"><i
                            class="fe fe-more-horizontal text-[0.8rem]"></i>
                        </a>
                        <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Another action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Something else here</a></li>
                        </ul>
                      </div>
                      
                    </div>
                    <div>
                      <p class="text-[.75rem] text-textmuted font-normal mb-0">Keeping Track of Recent Communications.
                      </p>
                    </div>
                  </div>
                  <div class="box-body !pt-2">
                    <div class="flex -space-x-2 rtl:space-x-reverse">
                      <img class="avatar avatar-sm avatar-rounded" src="../assets/images/faces/1.jpg" alt="Image Description">
                      <img class="avatar avatar-sm avatar-rounded" src="../assets/images/faces/10.jpg" alt="Image Description">
                      <img class="avatar avatar-sm avatar-rounded" src="../assets/images/faces/11.jpg" alt="Image Description">
                      <img class="avatar avatar-sm avatar-rounded" src="../assets/images/faces/12.jpg" alt="Image Description">
                      <img class="avatar avatar-sm avatar-rounded" src="../assets/images/faces/13.jpg" alt="Image Description">
                      <a aria-label="anchor" class="avatar avatar-sm avatar-rounded bg-primary/10 !text-primary" href="javascript:void(0);"> <i class="ri-arrow-right-s-line text-[1.125rem]"></i> </a>
                  </div>
                    <div class="flex justify-between mb-3"> <span class="text-textmuted">Messages</span> <a
                        href="javascript:void(0);" class="tx-inverse text-textmuted "><i
                          class="bi bi-check2-all !text-primary me-1"></i>Mark as read</a> </div>
                    <ul class="flex flex-col">
                      <li class="px-0 pt-0 ti-list-group !mb-5 !border-0  w-full flex justify-between">
                        <div class=""> <a href="profile.html" class="flex items-center space-x-3 rtl:space-x-reverse">
                            <img class="avatar avatar-md !rounded-full" src="../assets/images/faces/1.jpg"
                              alt="Image Description">
                            <div class="flex">
                              <div class="block my-auto">
                                <p class="block text-[0.875rem] font-medium my-auto ">
                                  Socrates Itumay</p>
                                <span class="text-[0.75rem] text-textmuted block text-truncate message">Hello there! How
                                  are you doing?</span>
                              </div>
                            </div>
                          </a> </div>
                        <spn class="text-textmuted text-[0.75rem]"><i
                            class="align-middle ri-time-line ltr:mr-1 rtl:ml-1"></i>9:45 PM</spn>
                      </li>
                      <li class="px-0 ti-list-group !mb-5 !border-0  w-full flex justify-between">
                        <div class=""> <a href="profile.html" class="flex items-center space-x-3 rtl:space-x-reverse">
                            <img class="avatar avatar-md !rounded-full" src="../assets/images/faces/16.jpg"
                              alt="Image Description">
                            <div class="flex">
                              <div class="block my-auto">
                                <p class="block text-[0.875rem] font-medium my-auto ">
                                  Ryan Gercia</p>
                                <span class="text-[0.75rem] text-textmuted block text-truncate message">Hey are you
                                  completing...</span>
                              </div>
                            </div>
                          </a> </div>
                        <spn class="text-textmuted text-[0.75rem]"><i
                            class="align-middle ri-time-line ltr:mr-1 rtl:ml-1"></i>6:30 PM</spn>
                      </li>
                      <li class="px-0 ti-list-group !mb-5 !border-0  w-full flex justify-between">
                        <div class=""> <a href="profile.html" class="flex items-center space-x-3 rtl:space-x-reverse">
                            <img class="avatar avatar-md !rounded-full" src="../assets/images/faces/6.jpg"
                              alt="Image Description">
                            <div class="flex">
                              <div class="block my-auto">
                                <p class="block text-[0.875rem] font-medium my-auto ">
                                  Prax Bhav</p>
                                <span class="text-[0.75rem] text-textmuted block text-truncate message">I would like to
                                  discuss about...</span>
                              </div>
                            </div>
                          </a> </div>
                        <spn class="text-textmuted text-[0.75rem]"><i
                            class="align-middle ri-time-line ltr:mr-1 rtl:ml-1"></i>11:15 AM</spn>
                      </li>
                      <li class="px-0 ti-list-group !mb-5 !border-0  w-full flex justify-between">
                        <div class=""> <a href="profile.html" class="flex items-center space-x-3 rtl:space-x-reverse">
                            <img class="avatar avatar-md !rounded-full" src="../assets/images/faces/6.jpg"
                              alt="Image Description">
                            <div class="flex">
                              <div class="block my-auto">
                                <p class="block text-[0.875rem] font-medium my-auto ">
                                  Jackie Chen</p>
                                <span class="text-[0.75rem] text-textmuted block text-truncate message">Shall we meet
                                  tomorrow?</span>
                              </div>
                            </div>
                          </a> </div>
                        <spn class="text-textmuted text-[0.75rem]"><i
                            class="align-middle ri-time-line ltr:mr-1 rtl:ml-1"></i>12:01 PM</spn>
                      </li>
                      <li class="px-0 ti-list-group !border-0  w-full flex justify-between">
                        <div class=""> <a href="profile.html" class="flex items-center space-x-3 rtl:space-x-reverse">
                            <img class="avatar avatar-md !rounded-full" src="../assets/images/faces/8.jpg"
                              alt="Image Description">
                            <div class="flex">
                              <div class="block my-auto">
                                <p class="block text-[0.875rem] font-medium my-auto ">
                                  Samantha Sam</p>
                                <span class="text-[0.75rem] text-textmuted block text-truncate message">Shall we go to
                                  the cafe at downtown...</span>
                              </div>
                            </div>
                          </a> </div>
                        <spn class="text-textmuted text-[0.75rem]"><i
                            class="align-middle ri-time-line ltr:mr-1 rtl:ml-1"></i>5:45 PM</spn>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-span-12 xxl:col-span-8 xl:col-span-8">
                <div class="box">
                  <div class="box-header ">
                    <div class="flex justify-between">
                      <h4 class="mb-2 box-title">Statistics</h4>
                      <div class="hs-dropdown ti-dropdown">
                        <a aria-label="anchor" href="javascript:void(0);"
                          class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                          data-bs-toggle="dropdown" aria-expanded="false"><i
                            class="fe fe-more-horizontal text-[0.8rem]"></i>
                        </a>
                        <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Another action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Something else here</a></li>
                        </ul>
                      </div>
                      
                    </div>
                    <div>
                      <p class="text-[.75rem] text-textmuted font-normal mb-0">Statistics of your income with respect
                        to the time.</p>
                    </div>
                  </div>
                  <div class="box-body !py-0">
                    <div id="statistics"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-span-12 xxl:col-span-3">
            <div class="overflow-hidden text-white box">
              <div class="absolute block w-full h-full before:bg-primary before:absolute before:w-full before:h-full">
              </div>
              <div class="relative box-body">
                <div class="flex justify-between mb-2">
                  <div class="ltr:mr-1 rtl:ml-1">
                    <h6 class="text-base font-medium !text-white">Recent Registrations</h6> <a
                      href="javascript:void(0);" class="text-xs text-white/70">Number of applications received
                      recently.</a>
                  </div>
                  <div class="min-w-fit">
                    <div class="p-3 text-white rounded-sm avatar bg-white/20 ring-0"> <i
                        class="text-2xl leading-none ti ti-browser-check"></i> </div>
                  </div>
                </div>
                <h4 class="mb-2 font-medium text-white">12,456</h4>
                <div class="flex justify-between text-white/70">
                  <div class="min-w-fit-content">
                    <div class="flex -space-x-2 rtl:space-x-reverse"> <img
                        class="inline-block avatar avatar-sm !rounded-full" src="../assets/images/faces/13.jpg"
                        alt="Image Description"> <img class="inline-block avatar avatar-sm !rounded-full"
                        src="../assets/images/faces/17.jpg" alt="Image Description"> <img
                        class="inline-block avatar avatar-sm !rounded-full" src="../assets/images/faces/9.jpg"
                        alt="Image Description"><img class="inline-block avatar avatar-sm !rounded-full"
                        src="../assets/images/faces/21.jpg" alt="Image Description"> </div>
                  </div> <span class="underline">View All</span>
                </div>
              </div>
            </div>
            <div class="box">
              <div class="flex justify-between box-header">
                <div>
                  <h4 class="mb-2 box-title">Subscriptions Analysis</h4>
                  <div>
                    <p class="text-[.75rem] text-textmuted font-normal mb-0">Evaluating Subscription Trends and
                      Patterns.</p>
                  </div>
                </div>
                <div class="hs-dropdown ti-dropdown">
                  <a aria-label="anchor" href="javascript:void(0);"
                    class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                    data-bs-toggle="dropdown" aria-expanded="false"><i class="fe fe-more-horizontal text-[0.8rem]"></i>
                  </a>
                  <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                        href="javascript:void(0);">Action</a></li>
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                        href="javascript:void(0);">Another action</a></li>
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                        href="javascript:void(0);">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="box-body">
                <ul class="mb-0 list-unstyled">
                  <li class="mb-5 ti-list-item">
                    <div class="relative flex items-start"> <a aria-label="anchor" href="javascript:void(0);"
                        class="masked-link z-1"></a>
                      <div class="me-2"> <span class="avatar bg-primary/10 !text-primary"><i
                            class="ri-fire-line !text-[1.2rem]"></i></span> </div>
                      <div class="flex-1">
                        <div class="flex items-center justify-between mb-2"> <span
                            class="flex-1 font-medium">starter</span> <span
                            class="min-w-fit-content ms-1 text-primary">80%</span> </div>
                        <div class="progress progress-xs progress-custom progress-animate" role="progressbar"
                          aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar" style="width: 80%"> </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="mb-5 ti-list-item">
                    <div class="relative flex items-start"> <a aria-label="anchor" href="javascript:void(0);"
                        class="masked-link z-1"></a>
                      <div class="me-2"> <span class="avatar bg-danger/10 !text-danger"><i
                            class="ri-global-line text-[1.2rem]"></i></span> </div>
                      <div class="flex-1">
                        <div class="flex items-center justify-between mb-2"> <span
                            class="flex-1 font-medium">Pro</span> <span
                            class="min-w-fit-content ms-1 text-danger">70%</span> </div>
                        <div class="progress progress-xs progress-custom progress-animate" role="progressbar"
                          aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar !bg-danger after:!border-danger" style="width: 70%"> </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="mb-5 ti-list-item">
                    <div class="relative flex items-start"> <a aria-label="anchor" href="javascript:void(0);"
                        class="masked-link z-1"></a>
                      <div class="me-2"> <span class="avatar bg-info/10 !text-info"><i
                            class="ri-flashlight-line text-[1.2rem]"></i></span> </div>
                      <div class="flex-1">
                        <div class="flex items-center justify-between mb-2"> <span
                            class="flex-1 font-medium">Premium</span> <span
                            class="min-w-fit-content ms-1 text-info">40%</span> </div>
                        <div class="progress progress-xs progress-custom progress-animate" role="progressbar"
                          aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar !bg-info after:!border-info" style="width: 40%"> </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="mb-5 ti-list-item">
                    <div class="relative flex items-start"> <a aria-label="anchor" href="javascript:void(0);"
                        class="masked-link z-1"></a>
                      <div class="me-2"> <span class="avatar bg-success/10 !text-success"><i
                            class="ri-shake-hands-line text-[1.2rem]"></i></span> </div>
                      <div class="flex-1">
                        <div class="flex items-center justify-between mb-2"> <span
                            class="flex-1 font-medium">Hybrid</span> <span
                            class="min-w-fit-content ms-1 text-success">60%</span> </div>
                        <div class="progress progress-xs progress-custom progress-animate" role="progressbar"
                          aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar !bg-success after:!border-success" style="width: 60%"> </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="ti-list-item">
                    <div class="relative flex items-start"> <a aria-label="anchor" href="javascript:void(0);"
                        class="masked-link z-1"></a>
                      <div class="me-2"> <span class="avatar bg-warning/10 !text-warning"><i
                            class="ri-store-line text-[1.2rem]"></i></span> </div>
                      <div class="flex-1">
                        <div class="flex items-center justify-between mb-2"> <span
                            class="flex-1 font-medium">Tiered</span> <span
                            class="min-w-fit-content ms-1 text-warning">55%</span> </div>
                        <div class="progress progress-xs progress-custom progress-animate" role="progressbar"
                          aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                          <div class="progress-bar !bg-warning after:!border-warning" style="width: 55%"> </div>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-12 gap-x-6">
          <div class="col-span-12 xxl:col-span-4 xl:col-span-12">
            <div class="overflow-hidden box">
              <div class="box-header !pb-3 !border-b border-defaultborder dark:border-defaultborder/10">
                <div class="flex justify-between">
                  <h4 class="mb-2 box-title">Posts Overview</h4>
                  <div class="hs-dropdown ti-dropdown">
                    <a aria-label="anchor" href="javascript:void(0);"
                      class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                      data-bs-toggle="dropdown" aria-expanded="false"><i class="fe fe-more-horizontal text-[0.8rem]"></i>
                    </a>
                    <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                          href="javascript:void(0);">Action</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                          href="javascript:void(0);">Another action</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                          href="javascript:void(0);">Something else here</a></li>
                    </ul>
                  </div>
                </div>
                <div>
                  <p class="text-[.75rem] text-textmuted font-normal mb-0">A Comprehensive Look at Recent
                    Posts,Reviewing Recent Activity and Engagemen.</p>
                </div>
              </div>
              <div class="box-body !p-3">
                <div class="grid grid-cols-12 gap-x-6">
                  <div class="col-span-12 sm:col-span-4">
                    <div class="flex items-center mb-3 sm:mb-0">
                      <div class="me-2"> <span
                          class="avatar avatar-md !rounded-full !text-[1.2rem] bg-primary/10 !text-primary"><i
                            class="ti ti-clock"></i></span> </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 font-medium">252</h6>
                        <p class="mb-0 text-[0.875rem] text-textmuted">Total</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-span-12 sm:col-span-4">
                    <div class="flex items-center mb-3 sm:mb-0">
                      <div class="me-2"> <span
                          class="avatar avatar-md !rounded-full !text-[1.2rem] bg-danger/10 !text-danger"><i
                            class="ti ti-eye"></i></span> </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 font-medium">1,116</h6>
                        <p class="mb-0 text-[0.875rem] text-textmuted">View</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-span-12 sm:col-span-4">
                    <div class="flex items-center mb-3 sm:mb-0">
                      <div class="me-2"> <span
                          class="avatar avatar-md !rounded-full !text-[1.2rem] bg-success/10 !text-success"><i
                            class="ti ti-activity"></i></span> </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-0 font-medium">620</h6>
                        <p class="mb-0 text-[0.875rem] text-textmuted">View Count</p>
                      </div>
                    </div>
                  </div>
                </div> <!-- End::Row-3.1 -->
              </div>
              <div class="box-footer !p-0">
                <div class="flex items-center justify-between px-4 py-3">
                  <h6 class="mb-0 box-title">Recent Posts</h6> <a href="javascript:void(0);"
                    class="underline text-primary !text-[0.875rem]">View All</a>
                </div> <!-- Start::list-group -->
                <ul class="list-unstyled list-group list-group-flush">
                  <li
                    class="ti-list-group-item list-group-item-action !border-t border-defaultborder dark:border-defaultborder/10 px-4 py-4">
                    <div class="flex items-start justify-between">
                      <div class="flex flex-wrap">
                        <div class="me-2"><span><img src="../assets/images/faces/12.jpg"
                              class="avatar avatar-md !rounded-full" alt="img"></span> </div>
                        <div class="flex-grow-1"> <a href="javascript:void(0);" class="text-[0.875rem] font-medium"
                            title="post1_link">Amet sanctus
                            dolore amet, sed dolor et duo gubergren eos..</a>
                          <p class="mb-0 mt-1 text-textmuted text-[0.75rem]"><i class="ti ti-clock me-1"></i>3 minutes
                          </p>
                        </div>
                      </div>
                      <div class="hs-dropdown ti-dropdown">
                        <a aria-label="anchor" href="javascript:void(0);"
                          class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                          data-bs-toggle="dropdown" aria-expanded="false"><i
                            class="fe fe-more-horizontal text-[0.8rem]"></i>
                        </a>
                        <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Another action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Something else here</a></li>
                        </ul>
                      </div>
                    </div>
                  </li>
                  <li
                    class="ti-list-group-item list-group-item-action !border-t border-defaultborder dark:border-defaultborder/10 px-4 py-4">
                    <div class="flex items-start justify-between">
                      <div class="flex flex-wrap">
                        <div class="me-2"><img src="../assets/images/faces/13.jpg"
                            class="avatar avatar-md !rounded-full" alt="img"> </div>
                        <div class="flex-grow-1"> <a href="javascript:void(0);" class="text-[0.875rem] font-medium"
                            title="post2_link">Elitr ipsum magna
                            takimata sit eirmod sea est dolor..</a>
                          <p class="mb-0 mt-1 text-textmuted text-[0.75rem]"><i class="ti ti-clock me-1"></i>2.5 hours
                          </p>
                        </div>
                      </div>
                      <div class="hs-dropdown ti-dropdown">
                        <a aria-label="anchor" href="javascript:void(0);"
                          class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                          data-bs-toggle="dropdown" aria-expanded="false"><i
                            class="fe fe-more-horizontal text-[0.8rem]"></i>
                        </a>
                        <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Another action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Something else here</a></li>
                        </ul>
                      </div>
                    </div>
                  </li>
                  <li
                    class="ti-list-group-item list-group-item-action !border-t border-defaultborder dark:border-defaultborder/10 px-4 py-4">
                    <div class="flex items-start justify-between">
                      <div class="flex flex-wrap">
                        <div class="me-2"> <img src="../assets/images/faces/2.jpg"
                            class="avatar avatar-md !rounded-full" alt="img"> </div>
                        <div class="flex-grow-1"> <a href="javascript:void(0);" class="text-[0.875rem] font-medium"
                            title="post3_link">Amet vero sit no at
                            sit, lorem labore sed takimata diam vero.</a>
                          <p class="mb-0 mt-1 text-textmuted text-[0.75rem]"><i class="ti ti-clock me-1"></i>13-10-22
                          </p>
                        </div>
                      </div>
                      <div class="hs-dropdown ti-dropdown">
                        <a aria-label="anchor" href="javascript:void(0);"
                          class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                          data-bs-toggle="dropdown" aria-expanded="false"><i
                            class="fe fe-more-horizontal text-[0.8rem]"></i>
                        </a>
                        <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Another action</a></li>
                          <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                              href="javascript:void(0);">Something else here</a></li>
                        </ul>
                      </div>
                    </div>
                  </li>
                  <li
                  class="ti-list-group-item list-group-item-action !border-t border-defaultborder dark:border-defaultborder/10 px-4 py-4">
                  <div class="flex items-start justify-between">
                    <div class="flex flex-wrap">
                      <div class="me-2"><img src="../assets/images/faces/17.jpg"
                          class="avatar avatar-md !rounded-full" alt="img"> </div>
                      <div class="flex-grow-1"> <a href="javascript:void(0);" class="text-[0.875rem] font-medium"
                          title="post2_link">Elitr ipsum magna
                          takimata sit eirmod sea est dolor..</a>
                        <p class="mb-0 mt-1 text-textmuted text-[0.75rem]"><i class="ti ti-clock me-1"></i>5 hours
                        </p>
                      </div>
                    </div>
                    <div class="hs-dropdown ti-dropdown">
                      <a aria-label="anchor" href="javascript:void(0);"
                        class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                        data-bs-toggle="dropdown" aria-expanded="false"><i
                          class="fe fe-more-horizontal text-[0.8rem]"></i>
                      </a>
                      <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                        <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                            href="javascript:void(0);">Action</a></li>
                        <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                            href="javascript:void(0);">Another action</a></li>
                        <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                            href="javascript:void(0);">Something else here</a></li>
                      </ul>
                    </div>
                  </div>
                </li>
                </ul> <!-- End::list-group -->
              </div>
            </div> <!-- End::card -->
          </div>
          <div class="col-span-12 xxl:col-span-8 xl:col-span-12">
            <div class="box">
              <div class="box-header ">
                <div class="flex justify-between">
                  <h4 class="mb-2 box-title">Recent Trasaction history</h4>
                  <div class="hs-dropdown ti-dropdown">
                    <a aria-label="anchor" href="javascript:void(0);"
                      class="flex items-center justify-center w-[1.75rem] h-[1.75rem] !text-defaulttextcolor !text-[0.8rem] !pt-1 !pb-[0.1rem] !px-2 avatar-rounded border border-light shadow-none !font-[500]"
                      data-bs-toggle="dropdown" aria-expanded="false"><i class="fe fe-more-horizontal text-[0.8rem]"></i>
                    </a>
                    <ul class="hidden hs-dropdown-menu ti-dropdown-menu">
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                          href="javascript:void(0);">Action</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                          href="javascript:void(0);">Another action</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-[500] block"
                          href="javascript:void(0);">Something else here</a></li>
                    </ul>
                  </div>
                </div>
                <div>
                  <p class="text-[.75rem] text-textmuted font-normal mb-0">Tracking Your Recent Financial
                    Activity,Reviewing Recent Financial Transactions.</p>
                </div>
              </div>
              <div class="box-body">
                <div class="overflow-auto table-responsive">
                  <table
                    class="table w-full ti-custom-table-head whitespace-nowrap table-bordered">
                    <thead class="bg-light dark:bg-black/20">
                      <tr>
                        <th scope="col" class="text-center">S.No</th>
                        <th scope="col">Recipient</th>
                        <th scope="col">Transaction Id</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                        <td class="text-center"> 01 </td>
                        <td>
                          <div class="flex items-center"> 
                            <div class="flex justify-between flex-1">
                              <div class="flex-1 pos-relative"> <a aria-label="anchor" href="contacts.html"
                                  class="masked-link"></a>
                                <p class="mb-0 font-medium text-[0.925rem]">Arzil Merob</p><span
                                  class="text-[0.75rem] text-textmuted">Merob0198@.com</span>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td> <span class="text-primary">#abc0012311</span> </td>
                        <td>
                          <div class=""> $12,098 </div>
                        </td>
                        <td>
                          <div class="flex items-center"> Debited </div>
                        </td>
                        <td> <span class="badge bg-success/10 !text-success">Success</span> </td>
                        <td>
                          <div class="g-2"> <a aria-label="anchor" class="ti-btn  ti-btn-primary !border !border-primary/20 ti-btn-sm"
                              data-bs-toggle="tooltip" data-bs-original-title="Edit"> <span
                                class="ri-pencil-line fs-14"></span> </a> <a aria-label="anchor"
                              class="ti-btn  ti-btn-danger !border !border-danger/20 ti-btn-sm ms-2" data-bs-toggle="tooltip"
                              data-bs-original-title="Delete"> <span class="ri-delete-bin-7-line fs-14"></span> </a>
                          </div>
                        </td>
                      </tr>
                      <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                        <td class="text-center"> 02 </td>
                        <td>
                          <div class="flex items-center">
                            <div class="flex justify-between flex-1">
                              <div class="flex-1 pos-relative"> <a aria-label="anchor" href="contacts.html"
                                  class="masked-link"></a>
                                <p class="mb-0 font-medium text-[0.925rem]">Vrsiore Merob</p><span
                                  class="text-[0.75rem] text-textmuted">Vrsiore987@.com</span>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td> <span class="text-primary">#abc0012312</span> </td>
                        <td>
                          <div class=""> $62,098 </div>
                        </td>
                        <td>
                          <div class="flex items-center"> Debited </div>
                        </td>
                        <td> <span class="badge bg-success/10 !text-success">Success</span> </td>
                        <td>
                          <div class="g-2"> <a aria-label="anchor" class="ti-btn  ti-btn-primary !border !border-primary/20 ti-btn-sm"
                              data-bs-toggle="tooltip" data-bs-original-title="Edit"> <span
                                class="ri-pencil-line fs-14"></span> </a> <a aria-label="anchor"
                              class="ti-btn  ti-btn-danger !border !border-danger/20 ti-btn-sm ms-2" data-bs-toggle="tooltip"
                              data-bs-original-title="Delete"> <span class="ri-delete-bin-7-line fs-14"></span> </a>
                          </div>
                        </td>
                      </tr>
                      <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                        <td class="text-center"> 03 </td>
                        <td>
                          <div class="flex items-center"> 
                            <div class="flex justify-between flex-1">
                              <div class="flex-1 pos-relative"> <a aria-label="anchor" href="contacts.html"
                                  class="masked-link"></a>
                                <p class="mb-0 font-medium text-[0.925rem]">Johber Akber</p><span
                                  class="text-[0.75rem] text-textmuted">Johber1123@.com</span>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td> <span class="text-primary">#abc0012313</span> </td>
                        <td>
                          <div class=""> $122,098 </div>
                        </td>
                        <td>
                          <div class="flex items-center"> Credited </div>
                        </td>
                        <td> <span class="badge bg-info/10 !text-info">Pending</span> </td>
                        <td>
                          <div class="g-2"> <a aria-label="anchor" class="ti-btn  ti-btn-primary !border !border-primary/20 ti-btn-sm"
                              data-bs-toggle="tooltip" data-bs-original-title="Edit"> <span
                                class="ri-pencil-line fs-14"></span> </a> <a aria-label="anchor"
                              class="ti-btn  ti-btn-danger !border !border-danger/20 ti-btn-sm ms-2" data-bs-toggle="tooltip"
                              data-bs-original-title="Delete"> <span class="ri-delete-bin-7-line fs-14"></span> </a>
                          </div>
                        </td>
                      </tr>
                      <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                        <td class="text-center"> 04 </td>
                        <td>
                          <div class="flex items-center"> 
                            <div class="flex justify-between flex-1">
                              <div class="flex-1 pos-relative"> <a aria-label="anchor" href="contacts.html"
                                  class="masked-link"></a>
                                <p class="mb-0 font-medium text-[0.925rem]">Bobber John</p><span
                                  class="text-[0.75rem] text-textmuted">Bobber123@.com</span>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td> <span class="text-primary">#abc0012314</span> </td>
                        <td>
                          <div class=""> $502,098 </div>
                        </td>
                        <td>
                          <div class="flex items-center"> Credited </div>
                        </td>
                        <td> <span class="badge bg-danger/10 !text-danger">cancel</span> </td>
                        <td>
                          <div class="g-2"> <a aria-label="anchor" class="ti-btn  ti-btn-primary !border !border-primary/20 ti-btn-sm"
                              data-bs-toggle="tooltip" data-bs-original-title="Edit"> <span
                                class="ri-pencil-line fs-14"></span> </a> <a aria-label="anchor"
                              class="ti-btn  ti-btn-danger !border !border-danger/20 ti-btn-sm ms-2" data-bs-toggle="tooltip"
                              data-bs-original-title="Delete"> <span class="ri-delete-bin-7-line fs-14"></span> </a>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="text-center"> 05 </td>
                        <td>
                          <div class="flex items-center"> 
                            <div class="flex justify-between flex-1">
                              <div class="flex-1 pos-relative"> <a aria-label="anchor" href="contacts.html"
                                  class="masked-link"></a>
                                <p class="mb-0 font-medium text-[0.925rem]">Allian Aksa</p><span
                                  class="text-[0.75rem] text-textmuted">Allian123@.com</span>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td> <span class="text-primary">#abc0012315</span> </td>
                        <td>
                          <div class=""> $95,098 </div>
                        </td>
                        <td>
                          <div class="flex items-center"> Debited </div>
                        </td>
                        <td> <span class="badge bg-success/10 !text-success">success</span> </td>
                        <td>
                          <div class="g-2"> <a aria-label="anchor" class="ti-btn  ti-btn-primary !border !border-primary/20 ti-btn-sm"
                              data-bs-toggle="tooltip" data-bs-original-title="Edit"> <span
                                class="ri-pencil-line fs-14"></span> </a> <a aria-label="anchor"
                              class="ti-btn  ti-btn-danger !border !border-danger/20 ti-btn-sm ms-2" data-bs-toggle="tooltip"
                              data-bs-original-title="Delete"> <span class="ri-delete-bin-7-line fs-14"></span> </a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end::main-content -->

    <!-- ========== Search Modal ========== -->
    <div id="search-modal" class="hs-overlay ti-modal hidden mt-[1.75rem]">
      <div class="ti-modal-box">
        <div class="ti-modal-content !border !border-defaultborder dark:!border-defaultborder/10 !rounded-[0.5rem]">
          <div class="ti-modal-body">

            <div class="input-group border-[2px] border-primary rounded-[0.25rem] w-full flex">
              <a href="javascript:void(0);"
                class="input-group-text flex items-center bg-light border-e-[#dee2e6] !py-[0.375rem] !px-[0.75rem] !rounded-none !text-[0.875rem]"
                id="Search-Grid"><i class="fe fe-search header-link-icon text-[0.875rem]"></i></a>

              <input type="search" class="form-control border-0 px-2 !text-[0.8rem] w-full focus:ring-transparent"
                placeholder="Search" aria-label="Username">

              <a href="javascript:void(0);" class="flex items-center input-group-text bg-light !py-[0.375rem] !px-[0.75rem]"
                id="voice-search"><i class="fe fe-mic header-link-icon"></i></a>
              <div class="hs-dropdown ti-dropdown">
                <a href="javascript:void(0);"
                  class="flex items-center hs-dropdown-toggle ti-dropdown-toggle btn btn-light btn-icon !bg-light !py-[0.375rem] !rounded-none !px-[0.75rem] text-[0.95rem] h-[2.413rem] w-[2.313rem]">
                  <i class="fe fe-more-vertical"></i>
                </a>

                <ul class="absolute hs-dropdown-menu ti-dropdown-menu !-mt-2 !p-0 hidden">
                  <li><a
                      class="ti-dropdown-item flex text-defaulttextcolor dark:text-defaulttextcolor/70 !py-[0.5rem] !px-[0.9375rem] !text-[0.8125rem] font-[500]"
                      href="#">Action</a></li>
                  <li><a
                      class="ti-dropdown-item flex text-defaulttextcolor dark:text-defaulttextcolor/70 !py-[0.5rem] !px-[0.9375rem] !text-[0.8125rem] font-[500]"
                      href="#">Another action</a></li>
                  <li><a
                      class="ti-dropdown-item flex text-defaulttextcolor dark:text-defaulttextcolor/70 !py-[0.5rem] !px-[0.9375rem] !text-[0.8125rem] font-[500]"
                      href="#">Something else here</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a
                      class="ti-dropdown-item flex text-defaulttextcolor dark:text-defaulttextcolor/70 !py-[0.5rem] !px-[0.9375rem] !text-[0.8125rem] font-[500]"
                      href="#">Separated link</a></li>
                </ul>
              </div>
            </div>
            <div class="mt-5">
              <p class="font-normal  text-[#8c9097] text-[0.813rem] dark:text-gray-200 mb-2">Are You Looking For...</p>

              <span class="search-tags text-[0.75rem] !py-[0rem] !px-[0.55rem] dark:border-defaultborder/10"><i class="fe fe-user me-2"></i>People<a
                  href="javascript:void(0)" class="tag-addon header-remove-btn"><span class="sr-only">Remove badge</span><i class="fe fe-x"></i></a></span>
              <span class="search-tags text-[0.75rem] !py-[0rem] !px-[0.55rem] dark:border-defaultborder/10"><i class="fe fe-file-text me-2"></i>Pages<a
                  href="javascript:void(0)" class="tag-addon header-remove-btn"><span class="sr-only">Remove badge</span><i class="fe fe-x"></i></a></span>
              <span class="search-tags text-[0.75rem] !py-[0rem] !px-[0.55rem] dark:border-defaultborder/10"><i
                  class="fe fe-align-left me-2"></i>Articles<a href="javascript:void(0)" class="tag-addon header-remove-btn"><span class="sr-only">Remove badge</span><i
                    class="fe fe-x"></i></a></span>
              <span class="search-tags text-[0.75rem] !py-[0rem] !px-[0.55rem] dark:border-defaultborder/10"><i class="fe fe-server me-2"></i>Tags<a
                  href="javascript:void(0)" class="tag-addon header-remove-btn"><span class="sr-only">Remove badge</span><i class="fe fe-x"></i></a></span>

            </div>


            <div class="my-[1.5rem]">
              <p class="font-normal  text-[#8c9097] text-[0.813rem] mb-2">Recent Search :</p>

              <div id="dismiss-alert" role="alert"
                class="!p-2 border dark:border-defaultborder/10 rounded-[0.3125rem] flex items-center text-defaulttextcolor dark:text-defaulttextcolor/70 !mb-2 !text-[0.8125rem] alert">
                <a href="notifications.html"><span>Notifications</span></a>
                <a class="leading-none ms-auto" href="javascript:void(0);" data-hs-remove-element="#dismiss-alert"><i
                    class="fe fe-x !text-[0.8125rem] text-[#8c9097]"></i></a>
              </div>

              <div id="dismiss-alert" role="alert"
                class="!p-2 border dark:border-defaultborder/10 rounded-[0.3125rem] flex items-center text-defaulttextcolor dark:text-defaulttextcolor/70 !mb-2 !text-[0.8125rem] alert">
                <a href="alerts.html"><span>Alerts</span></a>
                <a class="leading-none ms-auto" href="javascript:void(0);" data-hs-remove-element="#dismiss-alert"><i
                    class="fe fe-x !text-[0.8125rem] text-[#8c9097]"></i></a>
              </div>

              <div id="dismiss-alert" role="alert"
                class="!p-2 border dark:border-defaultborder/10 rounded-[0.3125rem] flex items-center text-defaulttextcolor dark:text-defaulttextcolor/70 !mb-0 !text-[0.8125rem] alert">
                <a href="mail.html"><span>Mail</span></a>
                <a class="ms-auto lh-1" href="javascript:void(0);" data-hs-remove-element="#dismiss-alert"><i
                    class="fe fe-x !text-[0.8125rem] text-[#8c9097]"></i></a>
              </div>
            </div>
          </div>

          <div class="ti-modal-footer !py-[1rem] !px-[1.25rem]">
            <div class="inline-flex rounded-md shadow-sm">
              <button type="button"
                class="ti-btn-group !px-[0.75rem] !py-[0.45rem]  rounded-s-[0.25rem] !rounded-tr-none !rounded-br-none ti-btn-primary !text-[0.75rem] dark:border-white/10">
                Search
              </button>
              <button type="button"
                class="ti-btn-group  ti-btn-primary-full rounded-e-[0.25rem] dark:border-white/10 !text-[0.75rem] !rounded-tl-none !rounded-bl-none !px-[0.75rem] !py-[0.45rem]">
                Clear Recents
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ========== END Search Modal ========== -->

    
    <!-- Footer Start -->
    <footer class="footer mt-auto xl:ps-[15rem]  font-normal font-inter bg-white  leading-normal !text-[0.875rem] shadow-[0_0_0.4rem_rgba(0,0,0,0.1)] dark:bg-bodybg py-4 text-center">
      @include('layouts.footer')
    </footer>
    <!-- Footer End -->

  </div>

  <!-- Back To Top -->
  <div class="scrollToTop">
      <span class="arrow"><i class="text-xl ri-arrow-up-s-fill"></i></span>
  </div>

  <div id="responsive-overlay"></div>


  <!-- popperjs -->
  <script src="../assets/libs/@popperjs/core/umd/popper.min.js"></script>

  <!-- Color Picker JS -->
  <script src="../assets/libs/@simonwep/pickr/pickr.es5.min.js"></script>

  <!-- sidebar JS -->
  <script src="../assets/js/defaultmenu.js"></script>

  <!-- Switch JS -->
  <script src="../assets/js/switch.js"></script>

  <!-- sticky JS -->
  <script src="../assets/js/sticky.js"></script>


  <!-- Simplebar JS -->
  <script src="../assets/libs/simplebar/simplebar.min.js"></script>

  <!-- Preline JS -->
  <script src="../assets/libs/preline/preline.js"></script>

  <!-- Apex Charts JS -->
  <script src="../assets/libs/apexcharts/apexcharts.min.js"></script>

  <!-- CRM-Dashboard -->
  <script src="../assets/js/personaldashboard.js"></script>

  
  <!-- Custom-Switcher JS -->
  <script src="../assets/js/custom-switcher.js"></script>

  <!-- Custom JS -->
  <script src="../assets/js/custom.js"></script>

</body>

</html>
>>>>>>> Stashed changes
