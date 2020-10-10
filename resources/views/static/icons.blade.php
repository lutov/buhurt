@extends('layouts.default')
@section('title')Иконки@stop
@section('subtitle')@stop
@section('content')
    @include('item.cards.title', array('title' => 'Иконки достижений', 'subtitle' => '<a href="http://thenounproject.com/">http://thenounproject.com/</a>'))
    <div class="pb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php
                    $result = '';
                    foreach ($icons as $icon) {
                        $result .= '<div class="col-md-3 mb-4">';
                        $result .= '<img src="/data/img/achievements/raw/'.$icon.'.png" alt="" class="img-fluid">';
                        $result .= '</div>';
                    }
                    echo $result;
                    ?>
                </div>
            </div>
        </div>
    </div>
@stop
