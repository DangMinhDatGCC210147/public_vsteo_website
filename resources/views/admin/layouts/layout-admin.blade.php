<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Vstep Website')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="DangMinhDat" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <meta http-equiv="refresh" content="2"> --}}
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('storage\students\assets\images\logo-white.png') }}">

    <link href="{{ asset('storage/admin/assets/libs/morris.js/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('storage/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('storage/admin/assets/css/test.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('storage/admin/assets/css/style.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('storage/admin/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('storage/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('storage/admin/assets/js/config.js') }}"></script>

    <link href="{{ asset('storage/admin/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('storage/admin/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('storage/admin/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('storage/admin/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('storage/admin/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet') }}" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<!-- Begin page -->
<div class="layout-wrapper">
    <!-- ========== Left Sidebar ========== -->
    <div class="main-menu">
        <!-- Brand Logo -->
        <div class="logo-box">
            <!-- Brand Logo Light -->
            <a href="{{ route('admin.index') }}" class="logo-light">
                <img src="{{ asset('storage\students\assets\images\big-logo.png') }}" alt="logo" class="logo-lg"
                    height="70">
                <img src="{{ asset('storage\students\assets\images\logo-white.png') }}" alt="small logo" class="logo-sm"
                    height="70">
            </a>

            {{-- <!-- Brand Logo Dark -->
            <a href="index.html" class="logo-dark">
                <img src="{{ asset('admin/assets/images/logo-dark.png') }}" alt="dark logo" class="logo-lg"
                    height="28">
                <img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo" class="logo-sm"
                    height="28">
            </a> --}}
        </div>

        <!--- Menu -->
        <div data-simplebar>
            <ul class="app-menu">

                <li class="menu-title">Menu</li>

                <li class="menu-item">
                    <a href="{{ route('admin.index') }}" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-home-smile"></i></span>
                        <span class="menu-text"> Dashboards </span>
                        {{-- <span class="badge bg-primary rounded ms-auto">01</span> --}}
                    </a>
                </li>

                <li class="menu-item">
                    <a href="#menuForms" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bxs-eraser"></i></span>
                        <span class="menu-text"> Forms </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuForms">
                        <ul class="sub-menu">
                            @if(auth()->user()->role == 0)
                                <li class="menu-item">
                                    <a href="{{ route('createInstructor.create') }}" class="menu-link">
                                        <span class="menu-text">Manage Lecturers</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('createStudent.create') }}" class="menu-link">
                                        <span class="menu-text">Manage Students</span>
                                    </a>
                                </li>
                            @endif
                            {{-- <li class="menu-item">
                                <a href="{{ route('test.create') }}" class="menu-link">
                                    <span class="menu-text">Manage Test</span>
                                </a>
                            </li> --}}
                            <li class="menu-item">
                                <a href="{{ route('create.skill.part') }}" class="menu-link">
                                    <span class="menu-text">Manage Skill Part</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item">
                    <a href="#menuTables" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-table"></i></span>
                        <span class="menu-text"> Tables </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuTables">
                        <ul class="sub-menu">
                            <li class="menu-item">
                                <a href="{{ route('tableAdmin.index') }}" class="menu-link">
                                    <span class="menu-text">Admin List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('tableLecturer.index') }}" class="menu-link">
                                    <span class="menu-text">Lecturer List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('tableStudent.index') }}" class="menu-link">
                                    <span class="menu-text">Student List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('room.index') }}" class="menu-link">
                                    <span class="menu-text">Room List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('tableAssignment.index') }}" class="menu-link">
                                    <span class="menu-text">Assignment List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('tableTest.index') }}" class="menu-link">
                                    <span class="menu-text">Test List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('questionBank.index') }}" class="menu-link">
                                    <span class="menu-text">Question Bank</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('resultList.index') }}" class="menu-link">
                                    <span class="menu-text">List Result</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>



    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="page-content">

        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar">
                <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">

                    <!-- Brand Logo -->
                    <div class="logo-box">
                        <!-- Brand Logo Light -->
                        <a href="index.html" class="logo-light">
                            <img src="{{ asset('storage\students\assets\images\big-logo.png') }}" alt="logo"
                                class="logo-lg" height="70">
                            <img src="{{ asset('storage\students\assets\images\logo-white.png') }}" alt="small logo"
                                class="logo-sm" height="70">
                        </a>
                    </div>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="button-toggle-menu">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-4">

                    <li class="d-none d-md-inline-block">
                        <a class="nav-link" href="" data-bs-toggle="fullscreen">
                            <i class="mdi mdi-fullscreen font-size-24"></i>
                        </a>
                    </li>

                    <li class="nav-link" id="theme-mode">
                        <i class="bx bx-moon font-size-24"></i>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            {{-- <img src="{{ asset('admin/assets/images/users/avatar-4.jpg') }}" alt="user-image"
                                class="rounded-circle"> --}}
                            <span class="ms-1 d-none d-md-inline-block">
                                {{ session('user_name') }} <i class="mdi mdi-chevron-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <div class="dropdown-divider"></div>

                            <span class="dropdown-item notify-item">Role:
                                @switch(session('role'))
                                    @case(0)
                                        {{ $roleName = 'Admin' }}
                                        @break
                                    @case(1)
                                        {{ $roleName = 'Lecturer' }}
                                        @break
                                    @case(2)
                                        {{ $roleName = 'Student' }}
                                        @break
                                    @default
                                @endswitch
                            </span>
                            <span class="dropdown-item notify-item">ID:
                                {{ session('user_id') }}
                            </span>
                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{-- <i class="fe-log-out"></i> --}}
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->
        <div class="px-3">
            <!-- Start Content-->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Copyright by Greenwich Vietnam Cantho Campus
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end">
                            <p class="mb-0">Developed by <a href="mailto:dangminhdat03.forwork@gmail.com"
                                    target="_blank">Dang Minh Dat</a> </p>
                            <p class="mb-0">Supported by <a href="mailto:khoahnvithuy@gmail.com" target="_blank">Ho
                                    Nhat Khoa</a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>

    <!-- App js -->
    <script src="{{ asset('storage/admin/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/js/app.js') }}"></script>

    <!-- Knob charts js -->
    <script src="{{ asset('storage/admin/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>

    <!-- Sparkline Js-->
    <script src="{{ asset('storage/admin/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

    <script src="{{ asset('storage/admin/assets/libs/morris.js/morris.min.js') }}"></script>

    <script src="{{ asset('storage/admin/assets/libs/raphael/raphael.min.js') }}"></script>

    <!-- Dashboard init-->
    <script src="{{ asset('storage/admin/assets/js/pages/dashboard.js') }}"></script>

    <!-- third party js -->
    <script src="{{ asset('storage/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <!-- third party js ends -->
    <!-- Sweet Alerts js -->
    <script src="{{ asset('storage/admin/assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/dropzone/min/dropzone.min.js') }}"></script>
    <!-- Demo js-->
    <script src="{{ asset('storage/admin/assets/js/pages/form-fileuploads.js') }}"></script>
    <!-- Chart JS -->
    <script src="{{ asset('storage/admin/assets/libs/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Demo js -->
    <script src="{{ asset('storage/admin/assets/js/pages/chartjs.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/js/statistic.js') }}"></script>
    <!-- Datatables js -->
    <script src="{{ asset('storage/admin/assets/js/pages/datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there is a success message in the session
            @if (session('success'))
            const successMessage = @json(session('success'));
                Swal.fire({
                    html: `
                        <h3>Success!</h3>
                        <h4>${successMessage}</h4>
                    `,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: `
                    animate__animated
                    animate__fadeInUp
                    animate__faster
                    `
                    },
                    hideClass: {
                        popup: `
                    animate__animated
                    animate__fadeOutDown
                    animate__faster
                    `
                    }
                });
            @endif
            @if (session('error'))
            const errorMessage = @json(session('error'));
                Swal.fire({
                    html: `
                        <h3>Error!</h3>
                        <h4>${errorMessage}</h4>
                    `,
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: `
                    animate__animated
                    animate__fadeInUp
                    animate__faster
                    `
                    },
                    hideClass: {
                        popup: `
                    animate__animated
                    animate__fadeOutDown
                    animate__faster
                    `
                    }
                });
            @endif
        });
    </script>
</div>
