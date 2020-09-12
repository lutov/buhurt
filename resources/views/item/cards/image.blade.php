<div class="col-lg-3 col-md-4 col-12 mb-4">
    <div class="card @include('card.class')">
        <img itemprop="image" src="{!! $cover !!}" alt="{!! $element->name !!}" class="card-img-top buhurt-cover"/>
        @if(Auth::check())
            <div class="card-footer text-center d-none d-xl-block">
                @include('card.controls')
            </div>
        @endif
    </div>
</div>