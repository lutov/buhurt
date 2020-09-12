<div class="tab-pane fade @if(array_key_first($tabs) === $tab['slug']) show active @endif" id="{{$tab['slug']}}"
     role="tabpanel" aria-labelledby="{{$tab['slug']}}-tab">
    <div class="row">
        @include('section.items', array('section' => $tab['section'], 'elements' => $tab['elements']))
    </div>
    @include('section.cards.footer', array('elements' => $tab['elements']))
</div>
