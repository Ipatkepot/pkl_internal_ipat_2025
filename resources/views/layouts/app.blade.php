<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard - Project Ujian</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('/assets/vendor/css/core.css')}}" />
    <link rel="stylesheet" href="{{asset('/assets/vendor/css/theme-default.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />

    <!-- ===========================
         PORNHUB THEME OVERRIDE
    =========================== -->
    <style>
      /* BASE DARK THEME */
      body,
      .layout-wrapper,
      .layout-page,
      .content-wrapper {
          background-color: #000 !important;
          color: #fff !important;
      }

      * {
          color: #fff;
      }

      /* Navbar */
      .navbar {
          background-color: #000 !important;
          border-bottom: 2px solid #ffa31a !important;
      }

      .navbar input {
          background: #1a1a1a !important;
          border: 1px solid #333 !important;
          color: #fff !important;
      }

      .navbar .dropdown-menu {
          background-color: #111 !important;
          border: 1px solid #333 !important;
      }

      .navbar .dropdown-item:hover {
          background-color: #ffa31a !important;
          color: #000 !important;
      }

      /* Sidebar */
      .layout-menu {
          background-color: #000 !important;
          border-right: 2px solid #ffa31a;
      }

      .menu-inner .menu-item .menu-link {
          color: #fff !important;
      }

      .menu-item.active .menu-link {
          background-color: #ffa31a !important;
          color: #000 !important;
      }

      .menu-item:hover .menu-link {
          background-color: #111 !important;
      }

      /* Icons */
      .menu-icon,
      .bx {
          color: #ffa31a !important;
      }

      /* Pornhub Logo */
      .ph-logo {
          display: flex;
          font-weight: 900;
          font-size: 20px;
          font-family: Arial, Helvetica, sans-serif;
      }
      .ph-white {
          color: #fff;
      }
      .ph-orange {
          background: #ffa31a;
          color: #000;
          padding: 2px 8px;
          border-radius: 5px;
          margin-left: 4px;
      }

      /* Cards */
      .card {
          background-color: #111 !important;
          border: 1px solid #222 !important;
      }

      /* Footer */
      .footer {
          background: #000 !important;
          border-top: 2px solid #ffa31a !important;
          color: #fff !important;
      }

      /* Buttons */
      .btn-primary {
          background-color: #ffa31a !important;
          border-color: #ffa31a !important;
          color: #000 !important;
      }

      .btn-primary:hover {
          background-color: #ffb84d !important;
      }

      /* Table */
      table {
          color: #fff !important;
      }

      .table thead {
          background: #ffa31a !important;
          color: #000 !important;
      }

      .table tbody tr {
          background: #111 !important;
      }

      .table tbody tr:hover {
          background: #1a1a1a !important;
      }
    </style>

  </head>

    <body>
        <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @include('layouts.components.sidebar')

            <div class="layout-page">
            @include('layouts.components.navbar')

            <!-- Content -->
            <div class="content-wrapper">
                <main class="py-4">
                    @yield('content')
                </main>

                @include('layouts.components.footer')
            </div>
            </div>

        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
        </div>

        <!-- JS -->
        <script src="{{asset('/assets/vendor/libs/jquery/jquery.js')}}"></script>
        <script src="{{asset('/assets/vendor/libs/popper/popper.js')}}"></script>
        <script src="{{asset('/assets/vendor/js/bootstrap.js')}}"></script>
        <script src="{{asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
        <script src="{{asset('/assets/vendor/js/menu.js')}}"></script>
        <script src="{{asset('/assets/js/main.js')}}"></script>

    </body>
</html>
