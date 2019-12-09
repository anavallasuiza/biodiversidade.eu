<?php defined('ANS') or die(); ?>

<li>
	<article class="rota">
		<header>
			<h1>
				<a href="<?php echo path('nota', $nota['url']); ?>"><?php echo $nota['titulo']; ?></a>
			</h1>

			<ul class="rota-informacion">
				<li><?php __e('Engadida'); ?>: <strong><?php echo $Html->time($nota['data']); ?></strong></li>
				<?php if ($nota['puntos']) { ?>
				<li><?php __e('Contén %s puntos', count($nota['puntos'])); ?></li>
				<?php } ?>
			</ul>
		</header>
	</article>
</li>
