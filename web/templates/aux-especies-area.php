<?php
defined('ANS') or die();

foreach($grupos as $grupo) { 
?>
    <li class="grupo"><?php echo $grupo['nome']; ?></li>
    <?php foreach($grupo['especies'] as $especie) { ?>
    <li class="selected" data-sinonimos="<?php echo $especie['sinonimos']; ?>" data-comun="<?php echo $especie['nome_comun']?>">
        <span class="especie" data-codigo="<?php echo $especie['url']; ?>" data-name="<?php echo $especie['nome']; ?>" title="<?php echo $especie['nome']; ?>">
            <?php echo $especie['nome']; ?>
        </span>
    </li>
    <?php }
}?>