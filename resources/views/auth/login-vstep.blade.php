<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <meta name="description"
        content="Tailwind CSS Saas HTML Template Is A Multi Purpose Landing Page Template, Corporate, Authentication, Launching Web, Agency or Business Startup, Clean, Modern, Creative, Multipurpose and Tailwind CSS Tailwind v3 etc." />
    <meta name="author" content="Zoyothemes" />

    <!-- favicon -->
    <link href="{{ asset('storage/students/assets/images/logo-white.png') }}" rel="shortcut icon">

    <!-- Main Css -->
    <link href="{{ asset('storage/students/assets2/css/style.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- =========== Main Section Start =========== -->
    <section
        class="relative h-screen w-full flex items-center justify-center bg-[conic-gradient(at_top_right,_var(--tw-gradient-stops))] from-[#ccf9df] to-[#d1d6ff]">
        <div class="relative max-w-lg md:mx-auto mx-6 w-full flex flex-col justify-center bg-white rounded-lg p-6">
            <div class="text-start mb-7">
                <a href="" class="grow block mb-8">
                    <img class="h-28 mx-auto" src="{{ asset('storage/students/assets/images/main-logo.png') }}" alt="images">
                </a>

                <div class="text-center">
                    <h3 class="text-2xl font-semibold text-dark mb-3">Welcome To Greenwich Vietnam</h3>
                    <p class="text-base font-medium text-light">Can Tho Campus</p>
                </div>
            </div>

            <form class="text-start w-full" method="POST" action="{{ route('loginAccount') }}">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
                <div class="mb-4">
                    <label for="email-addon" class="block text-base font-semibold text-dark mb-2">Email address</label>
                    <input id="email-addon"
                        class="block w-full rounded-md py-2.5 px-4 text-dark text-base font-medium border-gray-300 focus:gray-300 focus:border-primary focus:outline-0 focus:ring-0 placeholder:text-light placeholder:text-base"
                        type="email" name="email" placeholder="Enter your email">
                </div>

                <div class="mb-4">
                    <label for="password-addon" class="block text-base font-semibold text-dark mb-2">Password</label>
                    <div class="flex">
                        <input type="password" id="password-addon" name="password"
                            class="form-password text-dark text-base font-medium block w-full rounded-s-md py-2.5 px-4 border border-gray-300 focus:gray-300 focus:border-primary focus:outline-0 focus:ring-0 placeholder:text-light placeholder:text-base"
                            placeholder="Enter your password">
                        <button type="button" data-hs-toggle-password='{"target": "#password-addon"}'
                            class="inline-flex items-center justify-center py-2.5 px-4 border rounded-e-md -ms-px border-gray-300">
                            <i class="hs-password-active:hidden h-5 w-5 text-dark" data-lucide="eye"></i>
                            <i data-lucide="eye-off" class="hidden hs-password-active:block h-5 w-5 text-dark"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center flex-wrap gap-x-1 gap-y-2 mb-6 mt-3">
                    <a href="{{ route("student.changePassword") }}" class="text-base text-dark"><small>Do you want to change password?</small></a>
                </div>

                <div class="text-center mb-7">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-6 py-2.5 bg-primary font-bold text-base  text-white rounded-md transition-all duration-500"
                        type="submit">Log In </button>
                </div>
            </form>
        </div>
    </section>
    <!-- =========== Main Section End =========== -->

    <!-- Preline Js -->
    <script src="{{ asset('storage/students/assets2/libs/preline/preline.js') }}"></script>

    <!-- Lucide Js -->
    <script src="{{ asset('storage/students/assets2/libs/lucide/umd/lucide.min.js') }}"></script>

    <!-- Main App Js -->
    <script src="{{ asset('storage/students/assets2/js/app.js') }}"></script>
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
