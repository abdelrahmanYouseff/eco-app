<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->
<head>
    @include('layouts.head-page-meta', ['title' => 'Login'])
    @include('layouts.head-css')
</head>
<!-- [Head] end -->
<!-- [Body] Start -->
<body>
    @include('layouts.loader')
    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    <a href="#">
                        <img src="{{ asset('owner/assets/images/eco-logo.png') }}" alt="img" class="img-fluid" style="max-width: 50px;">
                    </a>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Login</b></h3>
                        </div>

                        <!-- ✅ بداية الفورم -->
                        <form action="{{ url('/login') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" name="remember" id="customCheckc1">
                                    <label class="form-check-label text-muted" for="customCheckc1">Keep me signed in</label>
                                </div>
                                <h5 class="text-secondary f-w-400">Forgot Password?</h5>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>

                            <!-- ✅ عرض الأخطاء -->
                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </form>
                        <!-- ✅ نهاية الفورم -->

                    </div>
                </div>
                <div class="auth-footer row">
                    <div class="col my-1">
                        <p class="m-0">Copyright © <a href="#">ECO Property</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer-js')
</body>
<!-- [Body] end -->
</html>
