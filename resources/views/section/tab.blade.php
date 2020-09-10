<div class="tab-pane fade @if(array_key_first($tabs) === $tab['slug']) show active @endif" id="{{$tab['slug']}}" role="tabpanel" aria-labelledby="{{$tab['slug']}}-tab">
    <div class="row">
        {!! ElementsHelper::getSection($request, $tab['elements'], $tab['section'], $options) !!}
    </div>
    @include('section.cards.footer', array('elements' => $tab['elements']))
</div>
