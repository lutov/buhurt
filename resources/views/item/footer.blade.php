@if(isset($tabs['similar']) && count($tabs['similar']['elements']))
    @include('section.cards.subtitle')
    <div class="tab-content" id="tabFooter">
        @include('section.tab', array('tab' => $tabs['similar']))
        <div class="tab-pane fade @if(array_key_first($tabs) === $tabs['comments']['slug']) show active @endif"
             id="{{$tabs['comments']['slug']}}" role="tabpanel" aria-labelledby="{{$tabs['comments']['slug']}}-tab">
            <div class="row">
                <div class="col-md-12">
                    @include('comments.form')
                    <div itemscope itemtype="http://schema.org/UserComments" class="comments">
                        @foreach($comments as $comment)
                            @include('comments.card')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
