@extends('layouts.default')
@section('title'){{$user->username}}@stop
@section('subtitle')Список нежелаемого@stop
@section('content')
    @include('section', array('subtitle' => 'Список нежелаемого '.$user->username))
@stop
