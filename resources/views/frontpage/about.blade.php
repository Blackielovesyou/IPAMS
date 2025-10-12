@include('partials.__header')
<main class="custombg min-vh-100 loginpagebg">
    @include('partials.__nav')
    <div class="container d-flex align-items-center py-3">
        <div class="card logincardbg" style="width: 100%">
            <div class="card-body">
                <div class="text-left">
                    <h1 class="fw-bold">ABOUT US</h1>
                </div>
                {!! isset($appInfo->about_us) ? $appInfo->about_us : '' !!}
            </div>
        </div>
    </div>
</main>
@include('partials.__footer')
