{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
@csrf

<!-- Email Address -->
<div>
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<!-- Password -->
<div class="mt-4">
    <x-input-label for="password" :value="__('Password')" />

    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<!-- Remember Me -->
<div class="block mt-4">
    <label for="remember_me" class="inline-flex items-center">
        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
    </label>
</div>

<div class="flex items-center justify-end mt-4">
    @if (Route::has('password.request'))
    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
        {{ __('Forgot your password?') }}
    </a>
    @endif

    <x-primary-button class="ml-3">
        {{ __('Log in') }}
    </x-primary-button>
</div>
</form>
</x-guest-layout> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>

<body>
    @include('dashboard::bases.css')
    @include('dashboard::bases.js')

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <div class="login-userset">
                        <div class="login-logo">
                            <a href="/">
                                <img src="{{ asset('assets/dashboard/img/logo.png') }}" alt="img">
                            </a>
                        </div>

                        <div class="login-userheading">
                            <h3>Sign In</h3>
                            <h4>Please login to your account</h4>
                        </div>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            {{-- email --}}
                            <div class="form-login">
                                <label>Email</label>
                                <div class="form-addons">
                                    <input type="email" placeholder="Enter your email address" name="email" required>
                                    <img src="{{ asset('assets/dashboard/img/icons/mail.svg') }}" alt="img">
                                </div>
                                @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- password --}}
                            <div class="form-login">
                                <label>Password</label>
                                <div class="pass-group">
                                    <input type="password" class="pass-input" placeholder="Enter your password" name="password" required>
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                                @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- forgot password --}}
                            <div class="form-login">
                                <div class="alreadyuser">
                                    <h4><a href="{{ route('password.request') }}" class="hover-a">Forgot Password?</a>
                                    </h4>
                                </div>
                            </div>

                            <div class="form-login mb-4">
                                <button type="submit" class="btn btn-login">Sign In</button>
                            </div>
                        </form>

                        <div class="form-setlogin">
                            <h4>Or sign up with</h4>
                        </div>

                        <div class="form-sociallink">
                            <ul class="d-flex justify-content-center">
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="{{ asset('assets/dashboard/img/icons/google.png') }}" class="me-2" alt="google">
                                        Sign Up using Google
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="login-img">
                    <img src="{{ asset('assets/dashboard/img/login.jpg') }}" alt="img">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
