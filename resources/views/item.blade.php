<div itemscope itemtype="http://schema.org/{!! $schema !!}">
    @include('item.cards.header')
    @include('item.body')
    @include('item.footer')
</div>
{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}
