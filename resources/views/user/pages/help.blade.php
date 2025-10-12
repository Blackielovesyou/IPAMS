@include('user.partials.__header')
@include('user.partials.__nav')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Help</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/user') }}">Home</a></li>
                <li class="breadcrumb-item active">Help</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section user_management">
      {!! isset($appInfo->guidelines) ? $appInfo->guidelines : 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Esse tenetur sunt repellendus quibusdam recusandae corrupti sint, voluptatibus eaque unde eius culpa pariatur illum doloribus non minus omnis vero molestiae temporibus.' !!}
    </section>
</main>

@include('user.partials.__footer')
