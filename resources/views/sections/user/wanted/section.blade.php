@extends('layouts.default')
@section('title'){{$user->username}}@stop
@section('subtitle')Список желаемого@stop
@section('content')
    @include('section', array('subtitle' => 'Список желаемого '.$user->username))
@stop
