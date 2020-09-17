@php
    use App\Models\User\User;
    /** @var $element */
    $user_id = $element->user_id;
    $user = User::find($user_id);
@endphp
<div class="card @include('card.class') mb-3" id="element_{!! $element->id !!}">
    <div class="card-header">
        <a href="/{!! $section !!}/{!! $element->element_id !!}">
            {!! $element->name !!}
        </a>
    </div>
    <div class="card-body" id="element_{!! $element->id !!}_text">
        <p class="card-text">
            {!! nl2br($element->text) !!}
        </p>
    </div>
    <div class="card-footer small text-muted">
        <a href="/user/{!! $user->id !!}/profile">{!! $user->username !!}</a>,
        {!! LocalizedCarbon::instance($element->created_at)->diffForHumans() !!}
    </div>
</div>
