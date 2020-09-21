@php
    use App\Helpers\ElementsHelper;
    /** @var $element */
    /** @var $user */
    $rate = ElementsHelper::getRate($element, $user);
@endphp
@if($element->rates)
    <div class="fast_rating_block">
        <input name="val" value="{{$rate}}" class="rating_input fast_rating" id="rating_{!! $section->alt_name !!}_{!! $element->id !!}"include type="text" autocomplete="off">
    </div>
@endif
