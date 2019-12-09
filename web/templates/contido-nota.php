<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('As miñas notas'); ?></h1>

			<nav>
                <a href="<?php echo path('editar', 'nota', $nota['url']); ?>" class="btn">
					<i class="icon-pencil"></i>
					<?php __e('Editar nota'); ?>
                </a>
                <a href="<?= path('editar', 'avistamento').'?nota='.$nota['url']; ?>" class="btn">
                    <i class="icon-plus"></i>
                    <?php __e('Crear observación'); ?>
                </a>
			</nav>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="subcontent ly-e1">
            <a class="btn-link" href="javascript:void(0);" onclick="history.back();">
                <i class="icon-arrow-left"></i>
                <?php echo __('Voltar'); ?>
            </a>

			<article class="nova nova-permalink">
				<header>
					<h1><?php echo $nota['titulo']; ?></h1>
				</header>

				<footer>
					<?php 
					echo ucfirst($Html->time($nota['data'], '', 'absolute'));

					if ($nota['comentarios']) {
						echo (count($nota['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($nota['comentarios']));
					}
					?>
				</footer>

				<div class="nova-intro">
					<p><?php echo $nota['texto']; ?></p>
				</div>

				<?php if ($nota['puntos']) { ?>
				<div id="map" style="width: 100%; height: 500px; margin: 20px 0;"></div>

				<?php foreach ($nota['puntos'] as $punto) { ?>
				<div class="hidden" data-map-location="<?php echo $punto['latitude'].','.$punto['lonxitude']; ?>">
					<p><?php echo $punto['titulo']; ?></p>
					<p><strong><?php echo $punto['latitude'].', '.$punto['lonxitude']; ?></strong></p>
				</div>
				<?php } ?>
				<?php }?>
			</article>
		</section>
	</div>
</section>
