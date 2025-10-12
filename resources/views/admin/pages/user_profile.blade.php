<div class="modal-content">
  <div class="modal-header border-0">
    <h5 class="modal-title fw-bold">User Data</h5>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <div class="d-flex">
      <div>
        <b>Name:</b> <span id="user_name">{{ $user_data->name }}</span> <br>
        <b>Email:</b> <span id="user_email">{{ $user_data->email }}</span> <br>
        <b>Account Type:</b>
        @if ($user_data->is_dean == 1)
          <span id="user_acc_type">Dean</span>
        @elseif ($user_data->is_registrar == 1)
          <span id="user_acc_type">Registrar</span>
        @elseif ($user_data->is_gec == 1)
          <span id="user_acc_type">Unit Head</span>
        @elseif ($user_data->is_instructor == 1)
          <span id="user_acc_type">Instructor</span>
        @endif
        <br>
      </div>
      <div class="connected_pdl"></div>
    </div>
  </div>
</div>