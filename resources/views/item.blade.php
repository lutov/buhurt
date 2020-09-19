<div itemscope itemtype="http://schema.org/{!! $schema !!}">
    @include('item.cards.header')
    <div class="row">
        @include('item.body')
    </div>
    @include('item.footer')
</div>
{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}
