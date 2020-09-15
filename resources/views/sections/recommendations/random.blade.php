@extends('layouts.default')
@section('title')Рекомендации@stop
@section('subtitle')@stop
@section('content')
    <script>
        $(document).ready(function () {

            var years_interval = $('#years_interval');
            years_interval.ionRangeSlider({
                min: 1890,
                max: {!! $options['years']['max'] !!},
                step: 10,
                type: 'double',
                prettify_enabled: false,
                grid: true,
                from: {!! $options['years']['from'] !!},
                to: {!! $options['years']['to'] !!}
            });

            var rec_num_interval = $('#rec_num_interval');
            rec_num_interval.ionRangeSlider({
                min: 3,
                max: 30,
                step: 3,
                prettify_enabled: false,
                grid: true,
                grid_snap: true,
                from: {!! $options['limit'] !!}
            });

        });
    </script>

    <div class="pb-4">
        <div class="card @include('card.class')">

            <form method="post" action="<?=$_SERVER['REQUEST_URI']?>" class="form_tabs">

                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#filter-1" role="tab">Общие настройки</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
        <!-- Tab panes -->
        <div class="tab-content mb-4">

            <div class="tab-pane active" id="filter-1" role="tabpanel">

                <div class="form-row">

                    <fieldset class="col-md-12">

                        <legend class="col-form-legend">Раздел</legend>
                        <div class="custom-control custom-radio custom-control-inline"
                             onclick="toggle_section('books', $('#element_type_books').prop('checked'));">
                            <input type="radio" id="element_type_books" name="element_type" value="books"
                                   class="custom-control-input" autocomplete="off"
                                   @if('books' == $section->alt_name) checked @endif>
                            <label class="custom-control-label" for="element_type_books">Книги</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline"
                             onclick="toggle_section('films', $('#element_type_films').prop('checked'));">
                            <input type="radio" id="element_type_films" name="element_type" value="films"
                                   class="custom-control-input" autocomplete="off"
                                   @if('films' == $section->alt_name) checked @endif>
                            <label class="custom-control-label" for="element_type_films">Фильмы</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline"
                             onclick="toggle_section('games', $('#element_type_games').prop('checked'));">
                            <input type="radio" id="element_type_games" name="element_type" value="games"
                                   class="custom-control-input" autocomplete="off"
                                   @if('games' == $section->alt_name) checked @endif>
                            <label class="custom-control-label" for="element_type_games">Игры</label>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-outline-secondary" type="button" data-toggle="collapse"
                                    data-target="#collapseDetails" aria-expanded="false"
                                    aria-controls="collapseDetails">
                                Подробнее
                            </button>
                        </div>

                        <div class="collapse" id="collapseDetails">

                            <legend class="col-form-legend mt-4">Годы выпуска</legend>
                            <div><input name="years" id="years_interval"></div>

                            <legend class="col-form-legend mt-4">Количество рекомендаций</legend>
                            <div><input name="recommendations" id="rec_num_interval"></div>

                        </div>

                    </fieldset>

                </div>

            </div>

        </div>

        @if (!Auth::check())
            {!! DummyHelper::regToRecommend() !!}
        @endif

        <div><input type="submit" class="btn btn-primary mt-4" value="Случайный список"></div>

                </div>

    </form>
        </div>
    </div>

    @if(count($elements))
        @include('section')
    @endif

@stop