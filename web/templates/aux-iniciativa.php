<?php defined('ANS') or die(); ?>
<li>
	<article class="ameaza" data-markers="<?php echo $iniciativa['shapes']['markers']; ?>" data-polygons="<?php echo $iniciativa['shapes']['polygons']; ?>" data-polylines="<?php echo $iniciativa['shapes']['polylines']; ?>" <?php if ($iniciativa['kml']) { ?>data-kml="<?php echo fileWeb('uploads|'.$iniciativa['kml']); ?>"<?php } ?> data-id="<?php echo $iniciativa['url']; ?>">
        <figure class="ameaza-imaxe">
            <?php
            echo $Html->img(array(
                'src' => ($iniciativa['imaxe'] ? ('uploads|'.$iniciativa['imaxe']['imaxe']) : 'templates|img/logo-imaxe.png'),
                'alt' => $iniciativa['titulo'],
                'width' => 80,
                'height' => 80,
                'transform' => 'zoomCrop,80,80'
            ));
            ?>
        </figure>

		<header>
			<h1>
				<a href="<?php echo path('iniciativa', $iniciativa['url']); ?>"><?php echo $iniciativa['titulo']; ?></a>

				<?php if ($iniciativa['estado']) { ?>
				<span class="estado activa"><i class="icon-refresh"></i> <?php __e('Activa'); ?></span>
				<?php } else { ?>
				<span class="estado solucionada"><i class="icon-refresh"></i> <?php __e('Desactiva'); ?></span>
				<?php } ?>
			</h1>

			<ul class="ameaza-informacion">
				<li class="data">
                    <?php __e('Creada por'); ?>

                    <a href="<?php echo path('perfil', $iniciativa['usuarios_autor']['nome']['url']); ?>">
                        <?php echo $iniciativa['usuarios_autor']['nome']['title'].' '.$iniciativa['usuarios_autor']['apelido1']; ?>
                    </a>

                    <?php
                    if (strtotime($iniciativa['data']) > 0) {
                        echo $Html->time($iniciativa['data']);
                    }
                    ?>
                </li>

                <?php if ($iniciativa['iniciativas_tipos']) { ?>
                <li>
                    <?php echo $Html->aList($iniciativa['iniciativas_tipos'], 'nome', 'id', path('iniciativas').'?iniciativa_tipo='); ?>
                </li>
                <?php } ?>

                <?php if ($iniciativa['zonas'] || $iniciativa['lugar']) { ?>
                <li>
                    <?php
                    if ($iniciativa['concellos']) {
                        echo __('Concello').': '.$iniciativa['concellos']['nome']['title'].'<br />';
                    }

                    if ($iniciativa['lugar']) {
                        echo __('Lugar').': '.$iniciativa['lugar'].'<br />';
                    }

                    if ($iniciativa['zonas']) {
                        $Templates->render('aux-iniciativa-zona.php', array(
                            'zonas' => $iniciativa['zonas']
                        ));
                    }
                    ?>
                </li>
                <?php } ?>

                <?php if ($iniciativa['comentarios']) { ?>
                <li>
                    <?php echo (count($iniciativa['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($iniciativa['comentarios'])); ?>
                </li>
                <?php } ?>
			</ul>
		</header>
	</article>
</li>
