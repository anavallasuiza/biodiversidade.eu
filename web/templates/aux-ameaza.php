<?php defined('ANS') or die(); ?>
<li>
	<article class="ameaza" data-markers="<?php echo $ameaza['shapes']['markers']; ?>" data-polygons="<?php echo $ameaza['shapes']['polygons']; ?>" data-polylines="<?php echo $ameaza['shapes']['polylines']; ?>" <?php if ($ameaza['kml']) { ?>data-kml="<?php echo fileWeb('uploads|'.$ameaza['kml']); ?>"<?php } ?> data-id="<?php echo $ameaza['url']; ?>">
        <figure class="ameaza-imaxe">
            <?php
            echo $Html->img(array(
                'src' => ($ameaza['imaxe'] ? ('uploads|'.$ameaza['imaxe']['imaxe']) : 'templates|img/logo-imaxe.png'),
                'alt' => $ameaza['titulo'],
                'width' => 80,
                'height' => 80,
                'transform' => 'zoomCrop,80,80'
            ));
            ?>
        </figure>
        
		<header>
			<h1>
				<a href="<?php echo path('ameaza', $ameaza['url']); ?>"><?php echo $ameaza['titulo']; ?></a>

				<?php if ($ameaza['estado']) { ?>
				<span class="estado activa"><i class="icon-refresh"></i> <?php __e('Activa'); ?></span>
				<?php } else { ?>
				<span class="estado solucionada"><i class="icon-refresh"></i> <?php __e('Desactiva'); ?></span>
				<?php } ?>
			</h1>

			<ul class="ameaza-informacion">
				<li class="data">
                    <?php $Html->time($ameaza['data_alta']); ?>

                    <a href="<?php echo path('perfil', $ameaza['usuarios_autor']['nome']['url']); ?>">
                        <?php echo $ameaza['usuarios_autor']['nome']['title'].' '.$ameaza['usuarios_autor']['apelido1']; ?>
                    </a>
                </li>

                <?php if ($ameaza['ameazas_tipos']) { ?>
                <li>
                    | <?php echo $Html->aList($ameaza['ameazas_tipos'], 'nome', 'id', path('ameazas').'?ameaza_tipo='); ?>
                </li>
                <?php } ?>

                <?php if ($ameaza['nivel']) { ?>
                <li>
                    | <a href="<?php echo path('ameazas') . get('nivel', $ameaza['nivel']); ?>">
                        <?php __e('nivel-ameaza-ameazas-'.$ameaza['nivel']); ?>
                    </a>
                </li>
                <?php } ?>

                <?php if ($ameaza['zonas'] || $ameaza['lugar']) { ?>
                <li>
                    <?php
                    if ($ameaza['concellos']) {
                        echo ' | '.__('Concello').': '.$ameaza['concellos']['nome']['title'];
                    }

                    if ($ameaza['lugar']) {
                        echo ' | '.__('Lugar').': '.$ameaza['lugar'];
                    }

                    if ($ameaza['zonas']) {
                        $Templates->render('aux-ameaza-zona.php', array(
                            'zonas' => $ameaza['zonas']
                        ));
                    }
                    ?>
                </li>
                <?php } ?>

                <?php if ($ameaza['data']) { ?>
                <li>
                    <?php echo $Html->time($ameaza['data']); ?>
                </li>
                <?php } ?>

                <?php if ($ameaza['comentarios']) { ?>
                <li>
                    <?php echo (count($ameaza['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($ameaza['comentarios'])); ?>
                </li>
                <?php } ?>
			</ul>
		</header>
	</article>
</li>
