<?php

/**
 * @var string $content
 * @var \Dez\Mvc\UrlRouteQuery\AnonymousMapper $mapper
 * */

$filter = $mapper->filter();

?>
<style>
    .active, .active * {
        font-weight: bold;
        color: black;
    }
</style>
<div style="border: 3px dotted coral;">
    <code><?=__FILE__?></code>
    <a href="<?= $url->create( 'index:index' ) ?>">hello</a>
    <hr>
    <a href="<?= $filter->reset(); ?>">reset filters</a>
    <ul>
        <span>
            diagonal
        </span>
        <li><a href="<?= $filter->attach('resolution', 'hd', 'lk')->attach('size', 17.5, 'le'); ?>">smalls</a></li>
        <li><a href="<?= $filter->leave('resolution', 4, 'ge')->attach('size', 27, 'gt'); ?>">super big 5k</a></li>

        <li><a class="<?= ($mapper->has('size', 21) ? 'active' : null); ?>" href="<?= $filter->leave('size', 21); ?>">21"</a></li>
        <li><a class="<?= ($mapper->has('size', 23) ? 'active' : null); ?>" href="<?= $filter->leave('size', 23); ?>">23"</a></li>
        <li><a class="<?= ($mapper->has('size', 23.5) ? 'active' : null); ?>" href="<?= $filter->leave('size', 23.5); ?>">23.5"</a></li>
        <li><a class="<?= ($mapper->has('size', 27) ? 'active' : null); ?>" href="<?= $filter->leave('size', 27); ?>">27"</a></li>
        <li><a class="<?= ($mapper->has('size', 19, 'gt') ? 'active' : null); ?>" href="<?= $filter->leave('size', 19, 'gt')->attach('size', 27, 'lt'); ?>">all 19" - 27"</a></li>
    </ul>

    <ul>
        <span>
            vendor
        </span>
        <li><a class="<?= ($mapper->has('vendor', 'lg') ? 'active' : null); ?>" href="<?= $filter->leave('vendor', 'lg'); ?>">lg</a></li>
        <li><a class="<?= ($mapper->has('vendor', 'dell') ? 'active' : null); ?>" href="<?= $filter->leave('vendor', 'dell'); ?>">dell</a></li>
        <li><a class="<?= ($mapper->has('vendor', 'acer') ? 'active' : null); ?>" href="<?= $filter->leave('vendor', 'acer'); ?>">acer</a></li>
        <li><a class="<?= ($mapper->has('vendor', 'samsung') ? 'active' : null); ?>" href="<?= $filter->leave('vendor', 'samsung'); ?>">samsung</a></li>
        <li><a class="<?= ($mapper->has('vendor', 'asus') ? 'active' : null); ?>" href="<?= $filter->leave('vendor', 'asus'); ?>">asus</a></li>
    </ul>

</div>