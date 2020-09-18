<div class="card @include('card.class')">
    {!! Form::open(array('action' => 'User\UserController@avatar', 'class' => 'avatar', 'method' => 'POST', 'files' => true)) !!}
    <div class="card-body">
        <div class="form-group">
            <label for="avatar">Аватар</label>
            <input type="file" class="form-control-file" name="avatar" id="avatar">

        </div>
        <div class="form-group m-0">
            {!! Form::submit('Загрузить', $attributes = array('id' => 'upload_avatar', 'type' => 'button', 'class' => 'btn btn-sm btn-secondary')) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
