<div class="card">
    <div class="card-body pt-3">
        <form class="row settingsform">
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label for="">Website Link</label>
                    <input type="text" name="applink" value="{{ isset($appInfo->website) ? $appInfo->website : '' }}"
                        class="form-control" id="applink">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label for="">Facebook Link</label>
                    <input type="text" name="facebooklink"
                        value="{{ isset($appInfo->facebook) ? $appInfo->facebook : '' }}" class="form-control"
                        id="facebooklink">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label for="">Youtube Link</label>
                    <input type="text" name="youtubelink"
                        value="{{ isset($appInfo->youtube) ? $appInfo->youtube : '' }}" class="form-control"
                        id="youtubelink">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" name="email" value="{{ isset($appInfo->email) ? $appInfo->email : '' }}"
                        class="form-control" id="email">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label for="">Contact Number</label>
                    <input type="text" name="contact" value="{{ isset($appInfo->contact) ? $appInfo->contact : '' }}"
                        class="form-control" id="contact">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label for="">Address</label>
                    <input type="text" name="address" value="{{ isset($appInfo->address) ? $appInfo->address : '' }}"
                        class="form-control" id="address">
                </div>
            </div>
            @csrf
            <div class="col-lg-12 text-end">
                <button type="submit" class="btn btn-success px-4">Save</button>
            </div>
        </form>
    </div>
</div>
