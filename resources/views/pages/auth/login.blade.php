<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - Doctor Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="app-style">
</head>

<body>
    <div class="main-wrapper auth-bg position-relative overflow-hidden">
        <div class="container-fluid position-relative z-1">
            <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100 bg-white">
                <div class="row">
                    <!-- Left Side: Illustration -->
                    <div class="col-lg-6 p-0">
                        <div class="login-backgrounds d-lg-flex align-items-center justify-content-center d-none flex-wrap p-4 position-relative h-100 z-0">
                            <img src="{{ asset('assets/img/icons/log-illustration-img-01.png') }}" alt="Login Illustration" class="img-fluid img1">
                        </div>
                    </div>

                    <!-- Right Side: Login Form -->
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="row justify-content-center align-items-center overflow-auto flex-wrap vh-100 py-3">
                            <div class="col-md-8 mx-auto">
                                <form action="{{ route('login') }}" method="POST" class="d-flex justify-content-center align-items-center">
                                    @csrf
                                    <div class="d-flex flex-column justify-content-lg-center p-4 p-lg-0 pb-0 flex-fill">
                                        
                                        <!-- Logo -->
                                        <div class="mx-auto mb-4 text-center">
                                            <img src="{{ asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                                        </div>

                                        <!-- Card -->
                                        <div class="card border-1 p-lg-3 shadow-md rounded-3 mb-4">
                                            <div class="card-body">
                                                
                                                <!-- Header -->
                                                <div class="text-center mb-3">
                                                    <h5 class="mb-1 fs-20 fw-bold">Sign In</h5>
                                                    <p class="mb-0">Please enter below details to access the dashboard</p>
                                                </div>

                                                <!-- Alerts -->
                                                @if(session('success'))
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        {{ session('success') }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                    </div>
                                                @endif
                                                @if(session('error'))
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        {{ session('error') }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                    </div>
                                                @endif

                                                <!-- Email -->
                                                <div class="mb-3">
                                                    <label class="form-label">Email Address</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text border-end-0 bg-white">
                                                            <i class="ti ti-mail fs-14 text-dark"></i>
                                                        </span>
                                                       
                                                        <input type="text" name="email" value="{{ old('email') }}"  class="form-control border-start-0 ps-0" placeholder="Enter Email Address">
                                                    </div>
                                                    @error('email')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Password -->
                                                <div class="mb-3">
                                                    <label class="form-label">Password</label>
                                                    <div class="position-relative">
                                                        <div class="pass-group input-group position-relative border rounded @error('password') is-invalid @enderror">
                                                            <span class="input-group-text bg-white border-0">
                                                                <i class="ti ti-lock text-dark fs-14"></i>
                                                            </span>
                                                             <input type="password"  name="password"  class="pass-input form-control ps-0 border-0" placeholder="****************">
                                                            <span class="input-group-text bg-white border-0">
                                                                <i class="ti toggle-password ti-eye-off text-dark fs-14"></i>
                                                            </span>
                                                           
                                                        </div>
                                                    </div>
                                                    @error('password')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Remember & Forgot -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-check form-check-md mb-0">
                                                            <input class="form-check-input" id="remember_me" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                                            <label for="remember_me" class="form-check-label mt-0 text-dark">Remember Me</label>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <a href="" class="text-danger">Forgot Password?</a>
                                                    </div>
                                                </div>

                                                <!-- Submit -->
                                                <div class="mb-2">
                                                    <button type="submit" class="btn bg-primary text-white w-100">
                                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                        Login
                                                    </button>
                                                </div>

                                              
                                              
                                               

                                            </div>
                                        </div>

                                        <!-- Copyright -->
                                        <p class="text-dark text-center">Copyright &copy; {{ date('Y') }} - Doctor Portal.</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    
    
</body>
</html>