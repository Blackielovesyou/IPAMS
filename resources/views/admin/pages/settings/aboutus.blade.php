<div class="card">
    <div class="card-body pt-3">
        <form class="row settingsform">
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <textarea name="about_us" id="about_us" class="summernote_input" cols="30" rows="10">{{isset($appInfo->about_us) ? $appInfo->about_us : '' }}</textarea>
                    @csrf
                </div>
            </div>
            <div class="text-end"><button type="submit" class="btn btn-success px-4">Save</button></div>
        </form>
    </div>
</div>
