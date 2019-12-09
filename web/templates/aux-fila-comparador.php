<?php 
defined('ANS') or die();

$width = 750 / count($tipos);
$height = 1000 / count($tipos);
?>
<li>
    <div>
        <div class="info right">
            <div class="move"><i class="icon-reorder"></i></div>
            <div class="remove"><i class="icon-remove"></i></div>
            <h1><a href="<?php echo path('especie', $especie['url']); ?>"><?php echo $especie['nome']; ?></a></h1>
        </div>
        <ul>
            <?php foreach($especie['tipos'] as $tipo => $images) { ?>
            <li>
                <?php
                if (empty($images)) {
                    echo $Html->img(array(
                        'src' => $Html->imgSrc('templates|img/logo-imaxe.png'),
                        'class' => 'empty',
                        'alt' => '',
                        'transform' => 'zoomCrop, ' . $width . ',' . $height,
                        'width'=> $width,
                        'height' => $height
                    ));
                    
                } else { ?>
                <div class="images">
                    <div>
                    <?php 
                    foreach($images as $image) {
                        echo $Html->img(array(
                            'src' => $image['imaxe'],
                            'data-src' => fileWeb('uploads|'.$image['imaxe']),
                            'data-especie' => $especie['nome'],
                            'data-tipo' => $tipo,
                            'alt' => '',
                            'rel' => ($especie['url'].'-'.$tipo),
                            'transform' => ('zoomCrop,'.$width.','.$height),
                            'width'=> $width,
                            'height' => $height
                        ));
                    }
                    ?>
                    </div>
                    
                </div>
                <div class="actions">
                    <?php if (count($images) > 1) { ?>
                    <span class="next right" data-anssliderindex="+1"><i class="icon-caret-right"></i></span>
                    <span class="previous left" data-anssliderindex="-1"><i class="icon-caret-left"></i></span>
                    <div><span class="actual-index">1</span>/<?php echo count($images); ?></div>
                    <?php } else { ?>
                    &nbsp;
                    <?php } ?>
                </div>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
    </div>
</li>