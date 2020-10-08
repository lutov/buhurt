@if(Auth::check())
    <form action="/comment/add" method="POST" id="comment_form">
    <div class="card @include('card.class') mb-4">
        <div class="card-header">
            <label for="comment" data-toggle="collapse" data-target="#collapsible_form" aria-controls="collapsible_form"
                   class="m-0">
                <abbr title="Показать форму">
                    Ваш комментарий
                </abbr>
            </label>
        </div>
        <div id="collapsible_form" class="collapse">
            <div class="card-body">
                <textarea name="comment" placeholder="Комментарий" class="form-control" id="comment" autocomplete="off"></textarea>
                <input type="hidden" name="comment_id" id="comment_id" autocomplete="off" />
            </div>
            <div class="card-footer">
                <input type="button" value="Сохранить" id="comment_save" role="button" class="btn btn-secondary" onclick="comment_add('{{ $section->alt_name }}', '{{ $element->id }}')" />
            </div>
        </div>
    </div>
    </form>
@else
    {!! DummyHelper::regToComment() !!}
@endif
