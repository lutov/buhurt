@foreach ($elements as $element)
    {!! ElementsHelper::getElement($request, $element, $section->alt_name, $options) !!}
@endforeach