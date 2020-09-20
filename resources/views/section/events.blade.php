@foreach ($elements as $element)
    @include('section.cards.event', array('section' => SectionsHelper::getSection(SectionsHelper::getSectionBy($element->element_type))))
@endforeach
