<div class="card @include('card.class') mb-4">
    @if(isset($tabs) && is_array($tabs) && count($tabs))
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                @foreach($tabs as $key => $tab)
                    @php
                        $count = ('comments' == $tab['slug']) ? '<span itemprop="reviewCount">'.$tab['count'].'</span>' : $tab['count']
                    @endphp
                    <li class="nav-item">
                        @php
                            /** @var array $tabs */
                            /** @var array $tab */
                            $active = array_key_first($tabs) === $tab['slug']
                        @endphp
                        <a class="nav-link @if($active) active @endif" id="{{$tab['slug']}}-tab" data-toggle="tab"
                           href="#{{$tab['slug']}}" role="tab" aria-controls="{{$key}}"
                           aria-selected="@if($active) true @else false @endif">
                            {{$tab['name']}}
                            <span class="small text-secondary">({!! $count !!})</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
