@php
    use App\Helpers\UserHelper;
    use App\Models\User\User;
    /** @var $comment */
    $user_id = $comment->user_id;
    $user = User::find($user_id);
    $user_options = UserHelper::getOptions($user);
    $is_my_private = in_array(1, $user_options);
    $rate = $comment->rate;
@endphp
@if(!$is_my_private || (Auth::check() && $user_id == Auth::user()->id))
    <div class="card @include('card.class') mb-4" id="comment_{!! $comment->id !!}">
        @if(Auth::check() && $user_id == Auth::user()->id)
            <div class="card-header text-right">
                <div class="btn-group">
                    <span role="button" class="btn btn-sm btn-secondary" onclick="comment_edit({!! $comment->id !!})" title="Редактировать">&#9998;</span>
                    <span role="button" class="btn btn-sm btn-secondary" onclick="comment_delete({!! $comment->id !!})" title="Удалить">&#10006;</span>
                </div>
            </div>
        @endif
        <div class="card-body">
            <p class="card-text" id="comment_{!! $comment->id !!}_text">
                {!! nl2br($comment->comment) !!}
            </p>
            @if($rate)
                <p class="card-text">Оценка: {!! $rate !!}</p>
            @endif
        </div>
        <div class="card-footer small text-muted">
            <a href="/user/{!! $user_id !!}/profile">{!! $comment->user->username !!}</a>, {!! LocalizedCarbon::instance($comment->created_at)->diffForHumans() !!}
        </div>
    </div>
@endif
