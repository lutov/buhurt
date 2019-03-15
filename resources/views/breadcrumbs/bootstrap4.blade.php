<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 13.03.2019
 * Time: 13:22
 */
?>

@if ($breadcrumbs)
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$breadcrumb->last)
                <?php $breadcrumb_url = preg_replace('/\?[0-9]+/', '', $breadcrumb->url); ?>
                <li class="breadcrumb-item"><a href="{{ $breadcrumb_url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb->title }}</li>
            @endif
        @endforeach
    </ol>
@endif