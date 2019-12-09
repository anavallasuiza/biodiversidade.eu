<?php defined('ANS') or die(); ?>

<li>
	<article class="blog">
		<?php
		echo $Html->img(array(
			'src' => $blog['imaxe'],
			'alt' => $blog['titulo'],
			'width' => 580,
			'height' => 100,
			'transform' => 'zoomCrop,580,100',
			'class' => 'blog-miniatura'
		));
		?>

		<header>
			<h1><a href="<?php echo path('blog', $blog['url']); ?>"><?php echo $blog['titulo']; ?></a></h1>
		</header>

		<div class="blog-descricion">
			<?php echo textCutter($blog['texto']); ?>
		</div>

		<?php
		if (isset($blog['blogs_posts']['titulo'])) {
			$Templates->render('aux-post.php', array(
				'post' => $blog['blogs_posts']
			));
		}
		?>
	</article>
</li>
