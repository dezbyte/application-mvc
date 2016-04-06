<?php
/**
 * @var string $content
 * @var \Dez\Mvc\GridRouteMapper\AnonymousMapper $mapper
 * */
use Dez\Mvc\GridRouteMapper\Mapper;

?>
<div style="border: 3px dotted coral;">
    <code><?=__FILE__?></code>
<!--    <pre style="border: 3px double black;">--><?php //print_r($content); ?><!--</pre>-->
    <a href="<?= $url->create( 'index:index' ) ?>">hello</a>
    <hr>
    <ul>
        <span>
            diagonal
        </span>
        <li><a href="<?= $mapper->filter('diagonal', Mapper::MAPPER_EQUAL, 21); ?>">21"</a></li>
        <li><a href="<?= $mapper->filter('diagonal', Mapper::MAPPER_EQUAL, 23); ?>">23"</a></li>
        <li><a href="<?= $mapper->filter('diagonal', Mapper::MAPPER_EQUAL, 23.5); ?>">23.5"</a></li>
        <li><a href="<?= $mapper->filter('diagonal', Mapper::MAPPER_EQUAL, 27); ?>">27"</a></li>
        <li><a href="<?= $mapper->filter('diagonal', Mapper::MAPPER_GREATER_THAN_EQUAL, 19)->filter('diagonal', Mapper::MAPPER_LESS_THAN_EQUAL, 27, false); ?>">all 19" - 27"</a></li>
    </ul>

    <ul>
        <span>
            model
        </span>
        <li><a href="<?= $mapper->filter('model', Mapper::MAPPER_EQUAL, 'dell', false); ?>">dell</a></li>
        <li><a href="<?= $mapper->filter('model', Mapper::MAPPER_EQUAL, 'acer', false); ?>">acer</a></li>
        <li><a href="<?= $mapper->filter('model', Mapper::MAPPER_EQUAL, 'samsung', false); ?>">samsung</a></li>
        <li><a href="<?= $mapper->filter('model', Mapper::MAPPER_EQUAL, 'asus', false); ?>">asus</a></li>
    </ul>
</div>