<div>
    <form class="row" id="changepassword">
        <div class="col-lg-12 mb-3">
            @csrf <!-- Add CSRF token field -->

            <div class="col-lg-12 mb-3">
                <label for="old_password" class="form-label">Old Password</label>
                <input type="password" class="form-control" id="old_password" name="old_password" required>
            </div>

            <div class="col-lg-12 mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="password" required>
            </div>

            <div class="col-lg-12 mb-3">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    required>
            </div>
        </div>
        <div class="text-end"><button type="submit" class="btn btn-success px-4">Change password</button></div>
    </form>
</div>