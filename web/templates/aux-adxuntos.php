<?php
defined('ANS') or die();

if (empty($adxuntos)) {
    return;
}
?>

<div class="listaxe-descargas">
    <h3><?php __e('Descargas'); ?></h3>

    <?php
    foreach ($adxuntos as $adxunto) {
        echo $Html->a($adxunto['titulo'], ($adxunto['ligazon'] ?: fileWeb('uploads|'.$adxunto['arquivo'])));
    }
    ?>
</div>