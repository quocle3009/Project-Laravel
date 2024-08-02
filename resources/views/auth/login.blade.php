@extends('layouts.auth-app')

@section('content')
    <div class="container">
        <div class="left-section">
            <img src="../assets/images/Illustration.png" alt="Illustration">
        </div>
        <div class="right-section">
            <div class="right-title">Welcome to<br> <span>Design School</span></div>
            <button class="social-login google"><img src="../assets/images/gg-icon.png" class="btn-icon"> Login with
                Google</button>
            <button class="social-login facebook"><img src="../assets/images/fb-icon.png" class="btn-icon"> Login with
                Facebook</button>
            <div class="divider">
                <hr><span>OR</span>
                <hr>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="row input-group align-items-center">
                    <div class="col-md-2 input-icon">
                        <img src="../assets/images/mail-icon.png" alt="">
                    </div>
                    <div class="col-md-10 input-group-append">
                        <label for="email">Email</label>

                        <div class="input-wrapper">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="example@gmail.com" name="email" value="{{ old('email') }}" required
                                autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row input-group align-items-center">
                    <div class="col-md-2 input-icon">
                        <img src="../assets/images/key-icon.png" alt="">
                    </div>
                    <div class="col-md-10 input-group-append">
                        <label for="password">Password</label>
                        <div class="input-wrapper position-relative">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="********">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <img src="../assets/images/eye-icon.png" alt=""
                                class="toggle-password position-absolute" onclick="togglePasswordVisibility()">
                        </div>
                    </div>
                </div>
                <div class="actions">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="login-button btn btn-primary">
                    {{ __('Login') }}
                </button>


                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </form>



            <p class="actions-account">Don't have an account? <a href="{{ route('register') }}">Register</a></p>

        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.querySelector('.toggle-password');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.src = "../assets/images/eye-hide-icon.png";
            } else {
                passwordInput.type = "password";
                eyeIcon.src = "../assets/images/eye-icon.png";
            }
        }
    </script>
@endsection
