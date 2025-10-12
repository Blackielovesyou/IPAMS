<div>
    <form class="row settingsform">
        <div class="col-lg-12 mb-3">
            <div class="form-group">
                <label for="">App Name</label>
                <input type="text" name="appname" value="{{isset($appInfo->app_name) ? $appInfo->app_name : '' }}"
                    class="form-control" id="webname">
            </div>
        </div>
        <div class="col-lg-12 mb-3 d-flex align-items-end">
            <img src="{{ isset($appInfo->logo) ? asset('storage/' . $appInfo->logo) : asset('img/no-image-icon-4.png') }}"
                id="logopreview" class="me-3" alt="logo" width="100" height="100">
            <div class="form-group">
                <label for="">Logo</label>
                <input type="file" name="logo" class="form-control" id="logoinput">
            </div>
        </div>
        <!-- <div class="col-lg-12 mb-3 d-flex align-items-end">
                <img src="{{ isset($appInfo->banner) ? asset('storage/' . $appInfo->banner) : asset('img/no-image-icon-4.png') }}" id="bannerpreview" style="object-fit: cover; object-position:center" class="me-3" alt="logo" width="100" height="100">
                <div class="form-group">
                    <label for="">Banner</label>
                    <input type="file" name="banner" class="form-control" id="banner">
                </div>
            </div> -->
        @csrf
        <div class="col-lg-12 text-end">
            <button type="submit" class="btn btn-success px-4">Save</button>
        </div>
    </form>
</div>