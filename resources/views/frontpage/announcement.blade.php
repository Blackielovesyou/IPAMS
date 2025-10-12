@include('partials.__header')
<main class="custombg min-vh-100">
    @include('partials.__nav')
    @if (!empty($announcement))
      <div class="text-center">
          <h1 class="fw-bold">ANNOUNCEMENT</h1>
      </div>
    @endif
    <section class="container">
        @if (!empty($announcement))
            <div class="row">
                <div class="col-lg-8 mb-3">
                    <img src="{{ isset($announcement->image) ? asset('storage/' . $announcement->image) : asset('img/banner.jpg') }}"
                        class="img-fluid mb-3 w-100" style="object-fit: cover; onject-possition:center;height:400px"
                        alt="banner">
                    <h3>{{ $announcement->title }}</h3>
                    <small>Posted on: {{ date('F j, Y', strtotime($announcement->created_at)) }}</small>
                    <div class="">
                        {!! $announcement->content !!}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="list-group">
                        @foreach ($otherAnnouncements as $item)
                            <a href="{{ url('/announcement/' . $item->id) }}"
                                class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $item->title }}</h5>
                                </div>
                                <small class="text-muted">Posted on:
                                    {{ date('F j, Y', strtotime($item->created_at)) }}</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1 class="fw-bold">No Announcement</h1>
                    No announcement yet. Please check back later.
                </div>
            </div>
        @endif
    </section>
</main>
@include('partials.__footer')
