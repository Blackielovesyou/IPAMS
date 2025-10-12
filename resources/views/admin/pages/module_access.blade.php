<div class="modal-content rounded-3 shadow">
  <div class="modal-header border-0 pb-0">
    <h5 class="modal-title fw-semibold">Module Access</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>

  <div class="modal-body">
    <form id="module_access_form">
      @csrf
      <input type="hidden" name="userID" value="{{ $userID }}">

      <div class="mb-4">
        <h6 class="fw-bold mb-3">Available Modules</h6>
        <div class="row">
          @foreach ($moduleAccessDetails as $moduleAccessDetails_data)
          @if (!in_array($moduleAccessDetails_data['code'], $access_code))
        <div class="col-12 col-md-6 mb-2">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="module_code[]"
          value="{{ $moduleAccessDetails_data['code'] }}" id="module_{{ $moduleAccessDetails_data['code'] }}">
          <label class="form-check-label" for="module_{{ $moduleAccessDetails_data['code'] }}">
          {{ $moduleAccessDetails_data['name'] }}
          </label>
        </div>
        </div>
        @endif
      @endforeach
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">Add Modules</button>
      </div>
    </form>

    <hr class="my-4">

    <h6 class="fw-bold mb-3">Current Access</h6>
    <div class="table-responsive">
      <table class="table align-middle table-bordered">
        <thead class="table-light">
          <tr>
            <th>Module</th>
            <th class="text-center" style="width: 120px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($moduleAccess as $item)
        <tr>
        <td>{{ $item['module_name'] }}</td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger delete_access" data-user-id="{{ $userID }}"
          data-access-id="{{ $item['id'] }}">
          <i class="fas fa-trash-alt me-1"></i> Delete
          </button>
        </td>
        </tr>
      @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>