<div>
    <div class="btn-group">

        @php
            /** @var $section */
            /** @var $element */
            $link = $section->alt_name.'/'.$element->id;
            $like_class = 'btn-secondary';
            $liked_class = 'btn-light';
            $call = "'".$section->alt_name."', '".$element->id."', '".$like_class."', '".$liked_class."'";
            $b_class = 'btn btn-sm '.$like_class;
        @endphp

        @if($isAdmin)
            <a role="button" class="{!! $b_class !!}" href="/admin/edit/{{$link}}"
               title="Редактировать">
                &#9998;
            </a>
        @endif

        @if(method_exists($element, 'wanted'))
            @php
                /** @var bool $isWanted */
                if ($isWanted) {
                    $class = ' '.$liked_class;
                    $handler = 'unset_wanted('.$call.')';
                } else {
                    $class = ' '.$like_class;
                    $handler = 'set_wanted('.$call.')';
                }
            @endphp
            <button type="button" class="{!! $b_class.$class !!}" onclick="{!! $handler !!}"
                    id="want_{!! $element->id !!}" title="Хочу">
                &#10084;
            </button>

            @php
                /** @var bool $isUnwanted */
                if ($isUnwanted) {
                    $class = ' '.$liked_class;
                    $handler = 'unset_unwanted('.$call.')';
                } else {
                    $class = ' '.$like_class;
                    $handler = 'set_unwanted('.$call.')';
                }
            @endphp
            <button type="button" class="{!! $b_class.$class !!}" onclick="{!! $handler !!}"
                    id="not_want_{!! $element->id !!}" title="Не хочу">
                &#9785;
            </button>
        @endif

        @if($isAdmin)
            <a role="button" class="{!! $b_class !!}" href="/admin/delete/{!! $link !!}"
               onclick="return window.confirm('Удалить?');" title="Удалить">
                &#10006;
            </a>
        @endif

    </div>
</div>