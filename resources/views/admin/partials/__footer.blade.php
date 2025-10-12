<!-- ======= Footer ======= -->
{{-- <footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
  </div>
  <div class="credits">
    <!-- All the links in the footer should remain intact. -->
    <!-- You can delete the links only if you purchased the pro version. -->
    <!-- Licensing information: https://bootstrapmade.com/license/ -->
    <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
  </div>
</footer><!-- End Footer --> --}}


<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
    class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ URL::asset('theme/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{ URL::asset('theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ URL::asset('theme/assets/vendor/chart.js/chart.umd.js')}}"></script>
<script src="{{ URL::asset('theme/assets/vendor/echarts/echarts.min.js')}}"></script>
<script src="{{ URL::asset('theme/assets/vendor/quill/quill.min.js')}}"></script>
<script src="{{ URL::asset('theme/assets/vendor/tinymce/tinymce.min.js')}}"></script>
<script src="{{ URL::asset('theme/assets/vendor/php-email-form/validate.js')}}"></script>


<!-- CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<!-- MAPBOX -->
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.2/mapbox-gl-geocoder.min.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

<!-- Template Main JS File -->
<script src="{{ asset('js/global.js') }}?v={{ filemtime(public_path('js/global.js')) }}"></script>

<script src="{{ URL::asset('theme/assets/js/main.js')}}"></script>
<script src="{{ URL::asset('js/lightbox.js') }}"></script>
<script src="{{ URL::asset('js/summernote.js')}}"></script>
<script src="{{ URL::asset('js/admin/settings.js')}}"></script>
<script src="{{ URL::asset('js/admin/calendar.js')}}"></script>
<script src="{{ URL::asset('js/admin/users.js')}}"></script>
<script src="{{ URL::asset('js/admin/audit.js')}}"></script>
<script src="{{ URL::asset('js/admin/app.js')}}"></script>
<script
  src="{{ asset('js/admin/users_management.js') }}?v={{ filemtime(public_path('js/admin/users_management.js')) }}"></script>
<script src="{{ asset('js/admin/test.js') }}?v={{ filemtime(public_path('js/admin/test.js')) }}"></script>
<script src="{{ asset('js/admin/map.js') }}?v={{ filemtime(public_path('js/admin/map.js')) }}"></script>


@if(Request::routeIs('videocall'))
  <script src="https://sdk.videosdk.live/js-sdk/0.0.82/videosdk.js"></script>
  <script src="{{ asset('js/index.js') }}"></script>
  <script src="{{ asset('js/config.js') }}"></script>
@endif

<script>
    var deleteModal = document.getElementById('deletePaymentModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var action = button.getAttribute('data-action'); // Extract info from data-* attributes
        var form = deleteModal.querySelector('#deletePaymentForm');
        form.action = action; // Set form action dynamically
    });
</script>
</body>

</html>