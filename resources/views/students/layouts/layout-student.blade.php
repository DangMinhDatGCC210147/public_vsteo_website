<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Vstep Website')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="DangMinhDat" name="author" />
    {{-- <meta http-equiv="Content-Security-Policy" content="script-src 'self';"> --}}
    {{-- <meta http-equiv="refresh" content="2"> --}}
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/students\assets\images\logo-white.png') }}">

    <link href="{{ asset('storage/admin/assets/libs/morris.js/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('storage/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('storage/students/assets/css/test.css') }}" rel="stylesheet" type="text/css">
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
    <div class="page-content">
        @yield('content')
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
    <script src="{{ asset('storage/storage/admin/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <!-- third party js ends -->
    <!-- Sweet Alerts js -->
    <script src="{{ asset('storage/admin/assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('storage/admin/assets/libs/dropzone/min/dropzone.min.js') }}"></script>

    <!-- Demo js-->
    <script src="{{ asset('storage/admin/assets/js/pages/form-fileuploads.js') }}"></script>
    <!-- Sweet alert Demo js-->
    {{-- <script src="{{ asset('admin/assets/js/pages/sweet-alerts.js') }}"></script> --}}
    <!-- Datatables js -->
    <script src="{{ asset('storage/admin/assets/js/pages/datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('students/assets/js/checkSession.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there is a success message in the session
            @if (session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
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
        });
    </script>
</div>
