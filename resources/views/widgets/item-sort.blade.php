<noindex><!--noindex-->
    <div class="card bg-dark mt-4">
        <div class="card-body">
            {!! Form::open(array('class' => 'sort', 'method' => 'GET')); !!}
            <div class="input-group input-group-sm">
                {!! Form::select('sort', $sort_options, $sort, array('class' => 'custom-select', 'autocomplete' => 'off')); !!}
                {!! Form::select('order', $sort_direction, $order, array('class' => 'custom-select', 'autocomplete' => 'off')); !!}
                {!! Form::hidden('page', $page); !!}
                <div class="input-group-append">
                    {!! Form::submit('Сортировать', array('class' => 'btn btn-secondary')); !!}
                </div>
            </div>
            {!! Form::close(); !!}
        </div>
    </div>
<!--/noindex--></noindex>
