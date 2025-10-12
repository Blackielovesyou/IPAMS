@php
    $notifications = auth()->user()->notifications->toArray();
@endphp
<header class="fixed-top">
  <div class="d-flex justify-content-between align-items-center bg-white px-3 py-2 shadow-sm">

    <!-- LEFT SIDE (Logo for desktop, Toggle for mobile) -->
    <div class="d-flex align-items-center">
      <!-- Mobile Toggle Button -->
      <button class="btn d-lg-none border-0 fs-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
        aria-controls="sidebar">
        <i class="bi bi-list text-dark"></i>
      </button>

      <div class="d-none d-lg-block ms-2">
        <h5 class="mb-0 fw-bold text-dark">{{ $appInfo->app_name ?? '' }}</h5>
      </div>
    </div>

    <!-- RIGHT SIDE NAV -->
    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">

          <div class="d-flex align-items-center">
            <a href="#notification"
              data-bs-toggle="modal"
              data-bs-target="#notificationmodal" id="notification-bell" class="me-4 fs-2 position-relative">
              <i class="text-dark bi bi-bell"></i>
              <span class="notification-bell position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                <span class="visually-hidden">New alerts</span>
              </span>
            </a>
              
              
            <a class="nav-link bg-transparent border border-0 text-dark nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
              <img src="{{ Auth::user()->profile_img != '' ? asset('storage/'.Auth::user()->profile_img) : URL::asset('img/admin-profile.png') }}" alt="Profile" class="rounded-circle">
              <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->first_name }}</span>
            </a><!-- End Profile Iamge Icon -->
  
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              <li class="dropdown-header">
                <h6>{{ Auth::user()->name }}</h6>
                <span>Applicant</span>
              </li>
              <li>
                <hr class="dropdown-divider mb-2">
              </li>
              <li class="">
                <button form="logoutform" type="submit" class="dropdown-item d-flex align-items-center justify-content-center">
                  <i class="text-dark bi bi-box-arrow-right"></i>
                  <span>Sign Out</span>
                </button>
              </li>
            </ul><!-- End Profile Dropdown Items -->
          </div>
        </li>
      </ul>
    </nav>
  </div>
</header>
  
  <aside id="sidebar" class="sidebar" style="background-color:#ffffff;">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link bg-transparent border border-0 text-black " href="{{ url('/user/home') }}">
          <i class="text-black bi bi-grid"></i>
          <span>Home</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link bg-transparent border border-0 text-black " href="{{ url('/user/help') }}">
          <i class="text-black bi bi-check-circle"></i>
          <span>Help</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link bg-transparent border border-0 text-black " href="{{ url('/user/about') }}">
          <i class="text-black bi bi-info-circle"></i>
          <span>About Us</span>
        </a>
      </li>
      
    </ul>
  </aside>
  <form action="{{ route('logout') }}" id="logoutform" method="POST">
    @csrf
  </form>
  
  <div
    class="modal fade"
    id="notificationmodal"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    
    role="dialog"
    aria-labelledby="notificationmodalTitle"
    aria-hidden="true"
  >
    <div
      class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
      role="document"
    >
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="notificationmodalTitle">
            Notification
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="list-group list-group-flush">
            @if (!empty($notifications))
              @foreach ($notifications as $notification)
                <a
                  href="#"
                  class="list-group-item list-group-item-action flex-column align-items-start"
                  aria-current="true"
                >
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $notification['title'] }}</h5>
                    <small class="text-muted">{{ date('M/d/Y', strtotime($notification['created_at']))}}</small>
                  </div>
                  <p class="mb-1">{{ $notification['content'] }}</p>
                </a>
              @endforeach
            @else 
              <div class="text-center">No Notification</div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  <script>
    checkUnreadNotifications();
    
    function checkUnreadNotifications() {
        $.ajax({
            type: 'GET',
            url: '/notifications/unread', // Update this to the correct route
            success: function (response) {
                if (response.unread_count > 0) {
                    // Show the notification icon with the alert badge
                    $('.notification-bell').removeClass('d-none');
                } else {
                    // Hide the notification icon
                    $('.notification-bell').addClass('d-none');
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching notifications: " + error);
            }
        });
    }
    $(document).on('click', '#notification-bell', function () {
        $.ajax({
            type: 'POST',
            url: '/notifications/mark-read',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
            },
            success: function (response) {
                if (response.success) {
                    // Optionally hide the notification alert after marking as read
                    checkUnreadNotifications(); // Refresh the notification state
                }
            },
            error: function (xhr, status, error) {
                console.error("Error marking notifications as read: " + error);
            }
        });
    });
  </script>

  