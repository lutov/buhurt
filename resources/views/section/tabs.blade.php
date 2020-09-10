<div class="tab-content" id="myTabContent">
    @foreach($tabs as $tab)
        @include('section.tab', array('tab' => $tab))
    @endforeach
</div>
