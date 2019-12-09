<?php defined('ANS') or die(); ?>

<li>
	<article class="nova">
		<header>
			<h1><a href="<?php echo path('nova', $nova['url']); ?>"><?php echo $nova['titulo']; ?></a></h1>
		</header>

		<footer>
			<?php
			echo ucfirst($Html->time($nova['data'], '', 'absolute'));

			if ($nova['comentarios']) {
				echo (count($nova['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($nova['comentarios']));
			}
			?>
		</footer>

		<div class="nova-intro">
			<p><?php echo textCutter($nova['texto'], 300); ?></p>
		</div>
	</article>
</li>
