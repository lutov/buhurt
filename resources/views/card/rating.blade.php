@php
    use App\Helpers\ElementsHelper;
    /** @var $element */
    /** @var $user */
    $rate = ElementsHelper::getRate($element, $user);
@endphp
<div class="fast_rating_block">
    <input name="val" value="{{$rate}}" class="fast_rating" id="rating_{!! $section->alt_name !!}_{!! $element->id !!}" data-section="{!! $section->alt_name !!}" data-element="{!! $element->id !!}" type="text" autocomplete="off">
</div>
