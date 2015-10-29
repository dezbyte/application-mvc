<div style="background-color: #f5aab5; padding: 10px;">
    <h1><?=__FILE__?></h1>
    tada...
    <br>
    ID: <?= $id ?>

    <a href="<?= $url->create( 'users:item', [ 'id' => $id ] ) ?>">link</a>

</div>