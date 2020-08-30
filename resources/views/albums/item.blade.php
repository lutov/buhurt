@extends('layouts.default')

@section('title'){!! $element->name !!}@stop

@section('subtitle'){!! $element->alt_name !!}@stop

@section('keywords')альбом, {!! DatatypeHelper::arrayToString($element->bands, ', ', '/bands/', true) !!}, {!! $element->name !!}, {!! $element->year !!}@stop
@section('description'){!! DatatypeHelper::arrayToString($element->bands, ', ', '/bands/', true) !!} — {!! $element->name !!} ({!! $element->year !!})@stop

@section('content')
	@include('widgets.item', array('schema' => 'MusicAlbum'))
@stop
