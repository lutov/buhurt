@extends('layouts.default')
@section('title')«{!! $query !!}»@stop
@section('subtitle')@stop
@section('content')
    @php
        use App\Helpers\RolesHelper;
        use App\Helpers\TextHelper;
        use Illuminate\Http\Request;
        /** @var int $total */
        /** @var Request $request */
        $isAdmin = RolesHelper::isAdmin($request);
        $found = TextHelper::number($total, array('результат', 'результата', 'результатов'));
    @endphp
    @include('item.cards.title', array('title' => '«'.$query.'»', 'subtitle' => $found))
    @if(0 != $total)
        @include('section.tabs')
    @else
        @if(Auth::check())
            @include('card.quick-links')
        @else
            <div class="pb-4">
                <div class="card @include('card.class')">
                    <div class="card-body">
                        {!! DummyHelper::regToAdd() !!}
                    </div>
                </div>
            </div>
        @endif
    @endif
@stop
