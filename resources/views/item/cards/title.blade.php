<div class="card @include('card.class') mb-4">
    @if(isset($tabs) && is_array($tabs) && count($tabs))
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                @foreach($tabs as $key => $tab)
                    <li class="nav-item">
                        @php
                            /** @var array $tabs */
                            /** @var array $tab */
                            $active = array_key_first($tabs) === $tab['slug'];
                        @endphp
                        <a class="nav-link @if($active) active @endif" id="{{$tab['slug']}}-tab" data-toggle="tab" href="#{{$tab['slug']}}" role="tab" aria-controls="{{$key}}" aria-selected="@if($active) true @else false @endif">
                            {{$tab['name']}}
                            <span class="small text-secondary">({{$tab['count']}})</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card-body text-center">
        <h1 class="card-title">{!! $title !!}</h1>
        @if(isset($subtitle))
            <div class="h2 card-subtitle text-muted">{!! $subtitle !!}</div>
        @endif
    </div>
    @if(!empty($options['sort_options']))
        <div class="card-footer">
            @include('section.sort')
        </div>
    @endif
</div>
