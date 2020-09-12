@if(Auth::check())
    {!! Form::open(array('action' => 'User\CommentController@add', 'class' => '', 'method' => 'POST', 'id' => 'comment_form')); !!}
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
                {!! Form::textarea('comment', null, array('placeholder' => 'Комментарий', 'class' => 'form-control', 'id' => 'comment', 'autocomplete' => 'off')); !!}
                {!! Form::hidden('comment_id', null, array('id' => 'comment_id', 'autocomplete' => 'off')); !!}
            </div>
            <div class="card-footer">
                {!! Form::button('Сохранить', array('id' => 'comment_save', 'role' => 'button', 'class' => 'btn btn-secondary', 'onclick' => 'comment_add(\''.$section.'\', \''.$element->id.'\')')); !!}
            </div>
        </div>
    </div>
    {!! Form::close(); !!}
@else
    {!! DummyHelper::regToComment() !!}
@endif
