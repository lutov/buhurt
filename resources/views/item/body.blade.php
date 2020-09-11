@php
    use App\Helpers\ElementsHelper;
    use App\Helpers\RolesHelper;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    /** @var Request $request */
    /** @var $section */
    /** @var $element */
    $isAdmin = RolesHelper::isAdmin($request);
    $cover = ElementsHelper::getCover($section->alt_name, $element->id);
    $isWanted = ((Auth::check()) ? (ElementsHelper::isWanted($element, Auth::user())) : false);
    $isUnwanted = ((Auth::check()) ? (ElementsHelper::isUnwanted($element, Auth::user())) : false);
@endphp
<div class="row">
    @include('item.cards.image')
    @include('item.cards.description')
</div>
