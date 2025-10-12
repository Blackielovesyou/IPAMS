<!-- <footer id="footer" class="footer">

    <div class="container">
      <div class="copyright text-center ">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">{{ isset($appInfo->app_name) ? $appInfo->app_name : 'SmartSched' }}</strong> <span>All Rights Reserved</span></p>
      </div>
      <div class="social-links d-flex justify-content-center">
        <a href="mailto:{{ isset($appInfo->email) ? $appInfo->email : '' }}"><i class="bi bi-envelope"></i></a>
        <a href="{{ isset($appInfo->facebook) ? $appInfo->facebook : '' }}"><i class="bi bi-facebook"></i></a>
        <a href="{{ isset($appInfo->youtube) ? $appInfo->youtube : '' }}"><i class="bi bi-youtube"></i></a>
        <a href="tel:{{ isset($appInfo->contact) ? $appInfo->youtube : '' }}"><i class="bi bi-telephone"></i></a>
      </div>
      <div class="credits">
    
      </div>
    </div>

  </footer> -->

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ URL::asset('Vesperr/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ URL::asset('Vesperr/assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ URL::asset('Vesperr/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ URL::asset('Vesperr/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ URL::asset('Vesperr/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ URL::asset('Vesperr/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ URL::asset('Vesperr/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ URL::asset('Vesperr/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ URL::asset('Vesperr/assets/js/main.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  
  <script src="{{ URL::asset('js/global.js') }}"></script>
  <script src="{{ URL::asset('js/welcome.js') }}"></script>
</body>

</html>