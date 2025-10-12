<div class="card">
    <div class="card-body pt-3">
        <form class="row settingsform">
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <textarea name="guidelines" id="guidelines_input" class="summernote_input" cols="30" rows="10">{{ isset($appInfo->guidelines) ? $appInfo->guidelines : '' }}</textarea>
                    @csrf
                </div>
                <div class="text-end"><button type="submit" class="btn btn-success px-4">Save</button></div>
            </div>
        </form>
    </div>
</div>
