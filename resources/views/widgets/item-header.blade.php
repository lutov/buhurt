<div class="card bg-dark text-white text-center mb-4">

    <div class="card-header">
        @if($element->writers)
            @php
                /** @var $element */
                $writers = DatatypeHelper::arrayToString($element->writers, ', ', '/persons/', false, 'author');
                $many_authors = (2 < mb_substr_count($writers, ', ')) ? true : false;
            @endphp

            @if($many_authors)
                @php
                    /** @var string $writers */
                    $authors = explode(', ', $writers);
                @endphp
                <abbr title="Произведения разных авторов" class="h2" data-toggle="collapse" data-target="#collapseAuthors" aria-expanded="false" aria-controls="collapseAuthors">
                    Альманах
                </abbr>
                <div class="h3 mt-2 collapse" id="collapseAuthors">
                    <ul class="list-unstyled">
                        <li class="mt-2">
                            {!! implode('</li><li class="mt-2">', $authors); !!}
                        </li>
                    </ul>
                </div>
            @else
                <div class="h2">{!! $writers; !!}</div>
            @endif
        @endif

        @if($element->bands)
            <div class="h2 card-subtitle">
                {!! DatatypeHelper::arrayToString($element->bands, ', ', '/bands/'); !!}
            </div>
        @endif

        <h1 itemprop="name" id="buhurt_name" class="card-title mb-0">{!! $element->name !!}</h1>

        @if(!empty($element->alt_name))
            @php
                /** @var $element */
                $long_name = (1 < count($element->alt_name)) ? true : false;
            @endphp
            @if($long_name)
                @php
                    /** @var $element */
                    $names = $element->alt_name;
                @endphp
                <div class="h3 mt-2 mb-0" id="buhurt_alt_name">
                    <ul class="list-unstyled mb-0">
                        <li>
                            <abbr title="Альтернативные названия" class="" data-toggle="collapse" data-target=".collapseAltName" aria-expanded="false" aria-controls="collapseAltName" itemprop="alternativeHeadline">
                                {!! array_shift($names); !!}
                            </abbr>
                        </li>
                        @foreach($names as $name)
                            <li itemprop="alternativeHeadline" class="collapse collapseAltName mt-2">
                                {!! $name !!}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="h3 mt-2 mb-0" itemprop="alternativeHeadline" id="buhurt_alt_name">{!! $element->alt_name[0] !!}</div>
            @endif
        @endif
    </div>

    @if(Auth::check())
        <input class="main_rating" name="val" value="{!! $rate !!}" type="text">
    @else
        <div class="card-body">
            {!! DummyHelper::regToRate(); !!}
        </div>
    @endif

    @if($rating['count'])
        <div class="card-footer">
            <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="small" style="opacity: .5;">
                <meta itemprop="worstRating" content = "1">
                    Средняя оценка: <b itemprop="ratingValue">{!! $rating['average'] !!}</b>
                <meta itemprop="bestRating" content = "10">,
                {!! TextHelper::ratingCount($rating['count'], array('голос', 'голоса', 'голосов')); !!}
                @if(0 != $element->comments->count())
                    , {!! TextHelper::reviewCount($element->comments->count(), array('комментарий', 'комментария', 'комментариев')); !!}
                @endif
            </div>
        </div>
    @endif

</div>