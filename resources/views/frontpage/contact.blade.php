@include('partials.__header')
  <main class="custombg min-vh-100 loginpagebg">
    @include('partials.__nav')
    <div class="container d-flex align-items-center py-3">
        <div class="card logincardbg" style="width: 100%">
            <div class="card-body">
                <div class="text-left">
                    <h1 class="fw-bold">CONTACT US</h1>
                </div>
                <div class="text-center">
                    <h5>{!! isset($appInfo->address) ? $appInfo->address : '' !!}</h5>
                    <a href="mailto:{!! isset($appInfo->email) ? $appInfo->email : '' !!}" style="text-decoration: none" class="text-dark">
                        <i class="fa-brands fa-facebook fs-3"></i>
                        {!! isset($appInfo->email) ? $appInfo->email : '' !!}
                    </a> <br>
                    <p>Message us in our Official Facebook Page</p>
                    <p>{!! isset($appInfo->facebook) ? $appInfo->facebook : '' !!}</p>
                </div>
            </div>
        </div>
    </div>
  </main>
@include('partials.__footer')
  