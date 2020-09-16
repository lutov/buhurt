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
            {!! DummyHelper::regToAdd() !!}
        @endif
    @endif
@stop
