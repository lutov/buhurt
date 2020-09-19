@php
    use App\Helpers\ElementsHelper;
    use App\Helpers\RolesHelper;
    use App\Models\Data\Section;
    use App\Models\User\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    /** @var Request $request */
    /** @var Section $section */
    /** @var $element */
    $auth = Auth::check();
    $user = (($auth) ? Auth::user() : new User());
    $isAdmin = RolesHelper::isAdmin($request);
    $isWanted = ElementsHelper::isWanted($element, $user);
    $isUnwanted = ElementsHelper::isUnwanted($element, $user);
    $square = false;
    if('albums' == $section->alt_name) {$square = true;}
    $img_class = ($square) ? 'card-img-box-square' : 'card-img-box';
@endphp
<div class="col-lg-3 col-md-4 col-sm-6 col-6">
    <div class="card @include('card.class') mb-4">
        <div class="card-header">
            <a href="/{{ $section->alt_name }}/{{ $element->id }}" class="one-liner" title="{!! $element->name !!}">
                {!! $element->name !!}
            </a>
        </div>
        <div class="{{ $img_class }}">
            <a href="/{{ $section->alt_name }}/{{ $element->id }}">
                <img class="card-img-top" src="{!! ElementsHelper::getCover($section->alt_name, $element->id) !!}" alt="{!! $element->name !!}" title="{!! $element->name !!}" loading="lazy"/>
            </a>
        </div>
        @if($auth)
            <div class="card-body text-center d-none d-xl-block p-2">
                {!! ElementsHelper::getFastRating($section, $element, $user) !!}
            </div>
            <div class="card-footer text-center d-none d-xl-block">
                @include('card.controls')
            </div>
        @endif
        @if($element->caption)
            <div class="card-footer text-muted">
                {!! $element->caption !!}
            </div>
        @endif
    </div>
</div>
