<?php defined('ANS') or die(); ?>

<li>
	<article class="post">
		<?php if ($post['imaxe']) { ?>
		<figure>
			<?php echo $Html->img(array(
				'src' => $post['imaxe']['imaxe'],
				'transform' => 'zoomCrop,100,100',
				'width' => '100',
				'height' => '100',
				'class' => 'img-post-list'
			));?>
		</figure>
		<?php } ?>

		<header>
			<h1><a href="<?php echo path('post', $post['blogs']['url'], $post['url']); ?>"><?php echo $post['titulo']; ?></a></h1>
		</header>

		<footer>
			<?php
			echo ucfirst($Html->time($post['data'], '', 'absolute'));

			if ($post['comentarios']) {
				echo (count($post['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($post['comentarios']));
			}
			?>
		</footer>

		<div class="post-intro">
			<p><?php echo textCutter($post['texto'], 200); ?></p>
		</div>
	</article>
</li>
