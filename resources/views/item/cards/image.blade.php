<div class="card @include('card.class')">
    <img itemprop="image" src="{!! ElementsHelper::getCover($section->alt_name, $element->id) !!}" alt="{!! $element->name !!}" class="card-img-top buhurt-cover"/>
    @if(Auth::check())
        <div class="card-footer text-center d-none d-xl-block">
            @include('card.controls')
        </div>
    @endif
</div>
