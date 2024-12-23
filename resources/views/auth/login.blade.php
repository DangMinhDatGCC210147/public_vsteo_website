@extends('auth.layouts.layout-login')

@section('content')
    <div class="form-body without-side">
        <div class="website-logo">
            <div class="logo">
                <img class="logo-size" src="{{ asset('students/assets/images/logo-white.png') }}" alt="Logo Greenwich">
            </div>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <img src="{{ asset('students/assets/images/graphic8.svg') }}" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <div class="form-icon">
                            <div class="icon-holder" style="color: white;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor"
                                    class="bi bi-door-open-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1" />
                                </svg>
                            </div>
                        </div>
                        {{-- <h3 class="form-title-center">Sign up and get access to the full guide right now</h3> --}}
                        <form method="POST" action="{{ route('loginAccount') }}">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif
                            <input class="form-control" type="email" name="email" placeholder="E-mail FPT Address"
                                required>
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn ibtn-full">Login</button>
                            </div>
                        </form>
                        <div class="change-pass d-flex justify-content-center p-1" ><a style="color:rgb(8, 29, 152)" href="{{ route("student.changePassword") }}">Forgot your password?</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                <script>
                    document.write(new Date().getFullYear())
                </script> © Copyright by Greenwich Vietnam Cantho Campus
            </div>
        </div>
    </footer>
    <style>
        html,
        body {
            overflow-y: hidden;
            font-family: "Roboto", sans-serif !important;
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
            /* Cho phép nội dung chiếm mọi không gian còn lại trừ footer */
        }

        footer {
            height: 50px;
            /* Hoặc chiều cao cụ thể cho footer của bạn */
        }
    </style>
@endsection
