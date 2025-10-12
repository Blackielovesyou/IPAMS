@include('partials.__header')
<main class="custombg min-vh-100 loginpagebg">
    @include('partials.__nav')
    <div class="container py-3">
        <div class="card logincardbg">
            <div class="card-body">
                <div class="text-center">
                    <h3 class="fw-bold">PRE - REGISTRATION</h3>
                    <p>Already Registered? <a href="{{ url('/login') }}" style="text-decoration:none;color:#004aad">Login now</a></p>
                </div>
                <form id="registerform" class="container py-3 was-validated">
                  <div class="row">
                      <div class="col-lg-4 mb-3">
                          <div class="form-group mb-3">
                              <label class="form-label fw-bold" for="first_name">FIRST NAME</label>
                              <input type="text" name="first_name" class="form-control py-3" id="first_name"
                                  placeholder="Enter your first name" required>
                          </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold" for="middle_name">MIDDLE NAME <small>(Opotional)</small></label>
                            <input type="text" name="middle_name" class="form-control py-3" id="middle_name"
                                placeholder="Enter your last name" required>
                        </div>
                    </div>
                      <div class="col-lg-4">
                          <div class="form-group mb-3">
                              <label class="form-label fw-bold" for="lname">LAST NAME</label>
                              <input type="text" name="last_name" class="form-control py-3" id="lname"
                                  placeholder="Enter your last name" required>
                          </div>
                      </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-lg-4 mb-3">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold" for="gender">GENDER</label>
                            <select name="gender" id="gender" class="form-select py-3" required>
                                <option value="">--Choose--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold" for="contact">PHONE NUMBER</label>
                            <input type="text" name="contact" class="form-control py-3" id="contact"
                                placeholder="Enter your contact number" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold" for="email">EMAIL</label>
                            <input type="email" name="email" class="form-control py-3" id="email"
                                placeholder="Enter your last name address" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-3">
                          <label class="form-label fw-bold" for="address">ADDRESS</label>
                          <input type="text" name="address" class="form-control py-3" id="address"
                              placeholder="Enter your address" required>
                      </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold" for="valid_id">Valid ID: </label>
                        <input type="file" name="valid_id" class="form-control py-3" id="valid_id" accept="image/*" required>
                    </div>
                  </div>
                </div>
                <div class="mg-3">
                    <div class="mb-3">
                        <div class="form-check d-flex justify-content-center">
                            <input class="form-check-input me-2" type="checkbox" checked value="" id="terms-and-condition" required />
                            <label class="form-check-label" for="terms-and-condition">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                            </label>
                        </div>
                    </div>
                </div>
                @csrf
                <div class="text-center">
                  <button class="btn btn-primary px-5 py-2 fw-100 rounded rounded-0" type="submit">SUBMIT</button>
                </div>
              </form>
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
