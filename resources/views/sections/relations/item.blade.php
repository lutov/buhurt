@extends('layouts.default')
@section('title')Связи «{!! $element->name !!}»@stop
@section('subtitle')@stop
@section('keywords'){!! $element->name !!}, сиквелы, приквелы, ремейки, адаптации@stop
@section('description')Связи «{!! $element->name !!}» с другими произведениями@stop
@section('content')
    <div class="row">
        <div class="@include('card.grid.sidebar') mb-4">
            @include('section.cards.item')
        </div>
        <div class="@include('card.grid.main') mb-4">
            @include('item.cards.title', array('title' => $element->name, 'subtitle' => 'Связи с произведениями'))
            @if($relations->count())
                <div class="row">
                    @foreach($relations as $relation)
                        @php
                            $relation_section = SectionsHelper::getSectionBy($relation->element_type);
                            $relation_element = $relation->element_type::find($relation->element_id);
                            $relation_element->caption = $relation->caption;
                        @endphp
                        <div class="@include('card.grid.third')">
                            @include('section.cards.item', array('section' => SectionsHelper::getSection($relation_section), 'element' => $relation_element))
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @if(RolesHelper::isAdmin($request))
        <script>
            function preview(element_id, section) {
                var block = $('#element_id_' + element_id);
                if ('' === block.prop('title')) {
                    var path = '/api/' + section + '/' + element_id + '/';
                    $.get(path, {}, function (data) {
                        block.prop('title', data.name);
                    });
                }
            }
        </script>
        <div class="pb-4">
            <div class="card @include('card.class')">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <form action="/{{ $section->alt_name }}/{{ $element->id }}/relations/add" class="add_relation" method="POST">
                                <div class="form-row">
                                    <div class="col">
                                        <input name="relations" placeholder="Связанные произведения" id="relations" class="form-control" />
                                    </div>
                                    <div class="col">
                                        @include('widgets.select', array('select_name' => 'section', 'options' => $sections_list, 'selected' => $section->alt_name, 'class' => 'form-control'))
                                    </div>
                                    <div class="col">
                                        @include('widgets.select', array('select_name' => 'relation', 'options' => $relations_list, 'selected' => null, 'class' => 'form-control'))
                                    </div>
                                    <div class="col">
                                        <input type="submit" value="Сохранить" id="relation_save" class="btn btn-secondary" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @foreach($relations_simple as $relation_simple)
                        @php
                            $relation_section = SectionsHelper::getSectionBy($relation_simple['element_type']);
                        @endphp
                        <div class="row mt-3">
                            <div class="col-md-10">
                                <form action="/{{ $section->alt_name }}/{{ $element->id }}/relations/edit" class="edit_relation" method="POST">
                                <div class="form-row">
                                    <input type="hidden" name="relation_id" value="'.$relation_simple['id'].'">
                                    <div class="col">
                                        <input name="element_id" value={{ $relation_simple['element_id'] }} placeholder="Произведение" id="element_id_{{ $relation_simple['element_id'] }}" class="form-control" onmouseover="preview('{{ $relation_simple['element_id'] }}', '{{ $relation_section }}')" autocomplete="off" />
                                    </div>
                                    <div class="col">
                                        @include('widgets.select', array('select_name' => 'element_section', 'options' => $sections_list, 'selected' => $relation_section, 'class' => 'form-control'))
                                    </div>
                                    <div class="col">
                                        @include('widgets.select', array('select_name' => 'relation_type', 'options' => $relations_list, 'selected' => $relation_simple['relation_id'], 'class' => 'form-control'))
                                    </div>
                                    <div class="col">
                                        <input type="submit" value="Сохранить" id="relation_save" class="btn btn-secondary" />
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form action="/{{ $section->alt_name }}/{{ $element->id }}/relations/delete" class="delete_relation" method="POST">
                                    <input type="hidden" name="relation_id" value="{!! $relation_simple['id'] !!}">
                                    <input type="submit" value="Удалить" id="relation_save" class="btn btn-danger" />
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@stop
