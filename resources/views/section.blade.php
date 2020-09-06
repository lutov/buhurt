@include('section.cards.header')
<div class="row">
    {!! ElementsHelper::getSection($request, $elements, $section->alt_name, $options); !!}
</div>
{!! ElementsHelper::getSectionFooter($request, $elements, $options); !!}
