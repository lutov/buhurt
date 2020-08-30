<div class="col-lg-3 col-md-4 col-12 mb-4">
    <div class="card bg-dark text-white">
        <img itemprop="image" src="{!! $cover !!}" alt="{!! $element->name !!}" class="card-img-top buhurt-cover" />
        @if (Auth::check())
            <div class="card-footer text-center d-none d-xl-block">
                {!! view('widgets.card-controls', array(
                    'section' => $section,
                    'element' => $element,
                    'isAdmin' => $isAdmin,
                    'isWanted' => $isWanted,
                    'isUnwanted' => $isUnwanted,
                )); !!}
            </div>
        @endif
    </div>
</div>