<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Delivery Management') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @yield('style')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- href="{{ asset('') }}" -->
    <!-- test  -->
    <link href="{{ asset('user/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('user/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('user/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('user/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('user/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('user/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('user/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('user/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('user/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Template Main CSS File -->
    <link href="{{ asset('user/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('user/css/style-user.css') }}" rel="stylesheet">


    <!-- Scripts -->
    <!-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) -->

    {{--    <!-- Scripts -->--}}
{{--    @vite(['resources/sass/app.scss', 'resources/js/app.js'])--}}

</head>

<body>
    <div id="app">
        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ url('/home') }}" class="logo d-flex align-items-center">

                    <span class="d-none d-lg-block">Task Management</span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div>

 
            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">

                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link nav-icon search-bar-toggle " href="#">
                            <i class="bi bi-search"></i>
                        </a>
                    </li><!-- End Search Icon-->




                    <nav class="header-nav ms-auto">
                        <ul class="d-flex align-items-center">

                            <!-- ... Các mục khác của navigation ... -->

                            <li class="nav-item dropdown pe-3">
                                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                                    data-bs-toggle="dropdown">
                                    <img src="{{ asset('user/img/profile-img.jpg') }}" alt="Profile"
                                        class="rounded-circle">
                                    &nbsp; <span class="fs-5">{{ Auth::user()->role }}</span>
                                    {{ Auth::user()->name }}
                                </a><!-- End Profile Iamge Icon -->

                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                    <li class="dropdown-header">
                                        <h5><a class="nav-link " href="#" role="button" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" v-pre>
                                                <span class="fs-5">{{ Auth::user()->role }}</span>
                                                {{ Auth::user()->name }}
                                            </a></h5>
                                        {{-- <span>Web Designer</span> --}}
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                   

                                    <li>
                                        <div class="dropdown-item align-items-center" aria-labelledby="navbarDropdown">

                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();"><i
                                                    class="bi bi-box-arrow-right"></i>
                                                {{ __('Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>

                                </ul><!-- End Profile Dropdown Items -->
                            </li><!-- End Profile Nav -->

                        </ul>
                    </nav><!-- End Icons Navigation -->

        </header><!-- End Header -->

        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">

            <ul class="sidebar-nav" id="sidebar-nav">

                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ url('/home') }}" ``>
                        <i class="bi bi-grid"></i>
                        <span>Trang chủ</span>
                    </a>
                </li><!-- End Dashboard Nav -->




                <!-- End Tables Nav -->

                
                <!-- End Blank Page Nav -->

            </ul>



        </aside><!-- End Sidebar-->
        <main class="py-4">
            @yield('content')
        </main>
    </div>


    <script src="{{ asset('user/js/main.js') }}"></script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('user/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('user/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('user/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('user/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('user/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('user/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('user/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('user/vendor/php-email-form/validate.js') }}"></script>

</body>

</html>
