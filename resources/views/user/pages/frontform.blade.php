<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
@if (auth()->user()->first_open == 0)
  <script>
    $(document).ready(function () {
      $('#first_open_modal').modal('show')
    });
  </script> 
@endif
<div
  class="modal fade"
  id="first_open_modal"
  tabindex="-1"
  data-bs-backdrop="static"
  data-bs-keyboard="false"
  
  role="dialog"
  aria-labelledby="updateProfileTitle"
  aria-hidden="true"
>
  <div
    class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
    role="document"
  >
    <div class="modal-content">
      <div class="modal-header text-center p-2">
        <h5 class="modal-title" id="updateProfileTitle">
          Update Profile
        </h5>
      </div>
      <form id="update_profile" class="modal-body">
        @csrf
        <div class="mb-3">
          <label for="profile_img" class="form-label">Upload Profile Image</label>
          <input
            type="file"
            class="form-control"
            name="profile_img"
            accept="img/*"
          />
        </div>
        <div class="mb-3">
          <label for="" class="form-label">Emall Address</label>
          <input
              type="email"
              name="email"
              id="email"
              value="{{ auth()->user()->email }}"
              class="form-control rounded rounded-0"
              placeholder="type here..."
              required
          />
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="mb-3">
              <label for="" class="form-label">First Name</label>
              <input
                  type="text"
                  name="first_name"
                  id="first_name"
                  value="{{ auth()->user()->first_name }}"
                  class="form-control rounded rounded-0"
                  placeholder="type here..."
                  required
              />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="mb-3">
              <label for="" class="form-label">Middle Name <small>( optional )</small></label>
              <input
                  type="text"
                  name="middle_name"
                  id="middle_name"
                  value="{{ auth()->user()->middle_name }}"
                  class="form-control rounded rounded-0"
                  placeholder="type here..."
              />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="mb-3">
              <label for="" class="form-label">Last Name</label>
              <input
                  type="text"
                  name="last_name"
                  id="last_name"
                  value="{{ auth()->user()->last_name }}"
                  class="form-control rounded rounded-0"
                  placeholder="type here..."
                  required
              />
            </div>
          </div>
        </div>
        <div class="mb-3">
          <label for="" class="form-label">Gender</label>
          <select name="gender" class="form-select rounded rounded-0" id="" required>
              <option value="">Choose</option>
              <option value="Male" {{ strtolower(auth()->user()->gender) == "male" ? "selected" : "" }}>Male</option>
              <option value="Female" {{ strtolower(auth()->user()->gender) == "female" ? "selected" : "" }}>Female</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="" class="form-label">Contact</label>
          <input
              type="text"
              name="contact"
              id="contact"
              value="{{ auth()->user()->contact }}"
              class="form-control rounded rounded-0"
              placeholder="type here..."
              required
          />
        </div>
        <div class="mb-3">
          <label for="" class="form-label">Full Address</label>
          <input
              type="text"
              name="address"
              id="address"
              value="{{ auth()->user()->address }}"
              class="form-control rounded rounded-0"
              placeholder="type here..."
              required
          />
        </div>
        <div class="col-lg-12 mb-3">
          <div class="form-group">
              <label for="validIDUpdate">Select a valid ID or document for verification:</label>
              <select id="validIDUpdate" name="validID" class="form-control" required>
                  <option value="" disabled selected>Select your option</option>
                  <option value="Passport">Passport</option>
                  <option value="Driver's License">Driver's License</option>
                  <option value="SSS ID">SSS ID</option>
                  <option value="GSIS ID">GSIS ID</option>
                  <option value="PhilHealth ID">PhilHealth ID</option>
                  <option value="Voter's ID">Voter's ID</option>
                  <option value="Postal ID">Postal ID</option>
                  <option value="PRC ID">PRC ID</option>
                  <option value="OFW ID">OFW ID</option>
                  <option value="UMID">UMID</option>
                  <option value="Senior Citizen ID">Senior Citizen ID</option>
                  <option value="PWD ID">PWD ID</option>
                  <option value="School ID">School ID</option>
                  <option value="Company ID">Company ID</option>
                  <option value="NBI Clearance">NBI Clearance</option>
                  <option value="Police Clearance">Police Clearance</option>
                  <option value="Barangay Clearance">Barangay Clearance</option>
                  <option value="Birth Certificate">Birth Certificate</option>
                  <option value="Marriage Certificate">Marriage Certificate</option>
                  <option value="Tax Identification Number (TIN) ID">Tax Identification Number (TIN) ID</option>
              </select>
          </div>
        </div>
        <div class="col-lg-12 mb-3">
            Valid ID/Document for verification
            <input type="file" name="verification_docs" class="form-control" required>
        </div>
        <hr>
        <div class="row">
          <div class="col-lg-6">
            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <input type="password" name="password" class="form-control">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="mb-3">
              <label for="password_confirmation" id="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
          </div>
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-dark px-5 rounded-0 directtologout">Cancel</button>
          <button type="submit" class="btn btn-primary px-5 rounded-0">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>