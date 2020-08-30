{!! Breadcrumbs::render('element', $element) !!}
<div itemscope itemtype="http://schema.org/{!! $schema !!}">
    {!! ElementsHelper::getCardHeader($request, $section->alt_name, $element, $options) !!}
    {!! ElementsHelper::getCardBody($request, $section->alt_name, $element, $options) !!}
    {!! ElementsHelper::getCardFooter($request, $section->alt_name, $element, $options) !!}
</div>
{!! ElementsHelper::getCardComments($request, $comments, $section->alt_name, $element->id) !!}
{!! ElementsHelper::getCardScripts($section->alt_name, $element->id) !!}
