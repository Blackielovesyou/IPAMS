@include('partials.__header')
  <main class="custombg min-vh-100 loginpagebg">
    @include('partials.__nav')
    <div class="container d-flex justify-content-center align-items-center py-3">
        <div class="card shadow-lg logincardbg" style="width: 100vh">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center mb-3">
                        <img src="{{ URL::asset('img/logo.png') }}" alt="" class="img-fluid">
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center">
                            <h3 class="fw-bold">LOGIN</h3>
                            <p>Sign in to continue</p>
                        </div>
                        <form id="loginform" class="was-validated">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold" for="email">EMAIL</label>
                                <input type="email" name="email" class="form-control py-3" id="email" placeholder="Enter your email address" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" for="password">PASSWORD</label>
                                <input type="password" name="password" class="form-control py-3" id="password" placeholder="Enter your password address" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-5 text-center"><hr style="width: 100%"></div>
                                <div class="col-2 text-center">OR</div>
                                <div class="col-5 text-center"><hr style="width: 100%"></div>
                            </div>
                            <div class="text-center fw-bold mb-3"><h5>Login using <a href="{{ url('codelogin') }}" style="text-decoration: none">Code</a></h5></div>
                            <div class="mb-3 text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input me-2" type="checkbox" value="" checked id="terms-and-condition" required />
                                    <label class="form-check-label fs-6" for="terms-and-condition">
                                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                    </label>
                                </div>
                            </div>
                            @csrf
                            <div class="text-center">
                                <button class="btn btn-primary px-5 py-2 fw-100 rounded rounded-0" type="submit">LOGIN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>        
        </div>
    </div>
    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! isset($appInfo->terms_and_condition) ? $appInfo->terms_and_condition : 'Default terms and conditions content.' !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
  </main>
@include('partials.__footer')
  