<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('storage\students\assets\images\logo-white.png') }}">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('storage/students/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('storage/students/assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('storage/students/assets/css/iofrm-style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('storage/students/assets/css/iofrm-theme27.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    @yield('content')
    <script src="{{ asset('storage/students/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('storage/students/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('storage/students/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('storage/students/assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</body>

</html>
