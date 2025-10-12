@include('partials.__header')
<main class="custombg min-vh-100 loginpagebg">
    @include('partials.__nav')
    <div class="container d-flex align-items-center py-3">
        <div class="card logincardbg" style="width: 100%">
            <div class="card-body">
                <div class="text-left">
                    <h1 class="fw-bold">MISSION AND VISSION</h1>
                </div>
                {!! isset($appInfo->mission_vission) ? $appInfo->mission_vission : 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odio neque fuga quos blanditiis labore ea soluta nulla incidunt quod hic! A culpa dolorum est. Repellendus amet maxime libero delectus officiis.Doloremque minus cum, reiciendis placeat id necessitatibus aliquid. Explicabo veniam ipsam repudiandae optio, maxime quis ratione numquam repellat molestiae sit placeat corrupti rem praesentium magni quasi ipsum architecto, voluptates quod.' !!}
            </div>
        </div>
    </div>
</main>
@include('partials.__footer')
