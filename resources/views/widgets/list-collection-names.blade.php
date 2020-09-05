<ul class="list-group list-group-flush" id="{!! $id !!}">
    @foreach ($collection as $element)
        <li class="list-group-item list-group-item-action">{!! $element->name !!}</li>
    @endforeach
</ul>
