@extends('layouts.default')
@section('title'){!! $user->username !!}@stop
@section('subtitle')@stop
@section('content')
    @include('user.cards.header')
    <div class="row">
        <div class="col-md-3 mb-4">
            @include('user.cards.avatar')
            @include('user.cards.avatar-upload')
        </div>
        <div class="col-md-9 mb-4">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade @if(array_key_first($tabs) === 'info') show active @endif" id="info" role="tabpanel" aria-labelledby="info-tab">
                    @include('user.cards.info')
                </div>
                @if($has_genres)
                    <div class="tab-pane fade @if(array_key_first($tabs) === 'genres') show active @endif" id="genres" role="tabpanel" aria-labelledby="genres-tab">
                        @include('user.cards.genres')
                    </div>
                @endif
                @if($has_rates)
                    <div class="tab-pane fade @if(array_key_first($tabs) === 'rates') show active @endif" id="rates" role="tabpanel" aria-labelledby="rates-tab">
                        @include('user.cards.rates')
                    </div>
                @endif
                <div class="tab-pane fade @if(array_key_first($tabs) === 'achievements') show active @endif" id="achievements" role="tabpanel" aria-labelledby="achievements-tab">
                    @include('user.cards.achievements')
                </div>
                @if($has_options)
                    <div class="tab-pane fade @if(array_key_first($tabs) === 'options') show active @endif" id="options" role="tabpanel" aria-labelledby="options-tab">
                        @include('user.cards.options')
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
