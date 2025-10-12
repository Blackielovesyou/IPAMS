@include('partials.__header')

<main class="min-vh-100 d-flex flex-column justify-content-between position-relative overflow-hidden resetPass">

    <div class="position-absolute top-0 start-0 w-100 h-100" style="
            background: url('{{ asset('img/bg-campus-2.jpg') }}') no-repeat center center / cover;
            filter: blur(2px);
            z-index: -1;
        "></div>

    @include('partials.__nav')

    <div class="container d-flex justify-content-center align-items-center flex-grow-1">

        <div class="login-card text-center p-4 shadow rounded-4 pt-5"
            style="max-width: 400px; width: 100%; position: relative; background-color: rgba(196, 196, 196, 0.7);">
            <div class="logo-circle bg-white rounded-circle d-flex justify-content-center align-items-center me-2 mb- form-floating-logo"
                style="width: 80px; height: 80px;">
                <img src="{{ asset('img/lspu_logo.png') }}" alt="LSPU Logo" class="top-left-logo"
                    style="width: 75px; height: 75px;">
            </div>
            <h5 class="mb-3 text-white">Reset Your Password</h5>

            <form id="passwordResetSubmitForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3 text-start">
                    <label for="password" class="form-label text-white">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="New Password" required>
                    </div>
                </div>

                <div class="mb-3 text-start">
                    <label for="password_confirmation" class="form-label text-white">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password" required>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-warning text-white">Reset Password</button>
                </div>

            </form>

        </div>

    </div>

</main>

@include('partials.__footer')