{!! Form::open(array('action' => array('Data\\'.$controller.'@transfer', $element->id), 'class' => 'transfer', 'method' => 'POST', 'files' => false)) !!}
<div>
    {!! Form::text('recipient_id', '', array('placeholder' => 'Преемник', 'id' => 'recipient', 'class' => 'form-control')) !!}
</div>
<div class="btn-group mt-3">
    {!! Form::submit('Перенести', array('id' => 'do_transfer', 'type' => 'button', 'class' => 'btn btn-sm btn-secondary')) !!}
</div>
{!! Form::close() !!}
