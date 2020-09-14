<div class="tab-pane fade @if(array_key_first($tabs) === $tab['slug']) show active @endif" id="{{$tab['slug']}}"
     role="tabpanel" aria-labelledby="{{$tab['slug']}}-tab">
    @include('section.list', array('section' => $tab['section'], 'elements' => $tab['elements']))
</div>
