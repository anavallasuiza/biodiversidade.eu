<?php defined('ANS') or die(); ?>

<li>
    <a href="<?php echo path('especie', $especie['url']); ?>">
        <article class="especie">
            <?php if ($especie['nivel_ameaza']) { ?>
            <i class="nivel-ameaza n<?php echo $especie['nivel_ameaza']; ?>"></i>
            <?php } ?>

            <figure class="especie-imaxe">
                <?php

                if ($especie['imaxe']) {
                    $imaxe = $especie['imaxe']['imaxe'];
                } else if ($especie['avistamentos']) {
                    $imaxe = $especie['avistamentos']['imaxes']['imaxe'];
                } else {
                    $imaxe = 'templates|img/logo-imaxe.png';
                }

                echo $Html->img(array(
                    'src' => $imaxe,
                    'alt' => $especie['nome'],
                    'width' => 150,
                    'height' => 150,
                    'transform' => 'zoomCrop,150,150'
                ));
                ?>
            </figure>

            <header>
                <h1>
                    <?php if ($checkbox) { ?>
                    <input type="checkbox" name="especies[id][]" value="<?php echo $especie['id']; ?>" />
                    <?php } ?>

                    <?php echo $especie['nome']; ?>
                </h1>
            </header>
        </article>
    </a>
</li>
