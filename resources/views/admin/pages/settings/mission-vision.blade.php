<div class="card">
    <div class="card-body pt-3">
        <form class="row settingsform">
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <textarea name="sum_mission_vision" id="sum_mission_vision" class="summernote_input" cols="30" rows="10">{{isset($appInfo->mission_vission) ? $appInfo->mission_vission : '' }}</textarea>
                    @csrf
                </div>
                <div class="text-end"><button type="submit" class="btn btn-success px-4">Save</button></div>
            </div>
        </form>
    </div>
</div>
