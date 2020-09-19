@extends('layouts.default')
@section('title'){{$user->username}}@stop
@section('subtitle'){{$section_name}}@stop
@section('content')
    @include('section', array('subtitle' => 'Оценки '.$user->username, 'export' => '/user/'.$user->id.'/rates/'.$section->alt_name.'/export'))
@stop
