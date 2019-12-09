<?php defined('ANS') or die(); ?>


<header class="main-blog">
	<div class="wrapper clear">
		<h1 class="image logo-biodiv-blog"><a href="<?php echo path(''); ?>"><?php echo __('Biodiversidade ameazada') ?></a></h1>
	</div>
</header>

<div class="imaxe-blog-cabeceira" style="background-image: url('<?php echo fileWeb('uploads|'.$blog['imaxe']); ?>');">
	<div class="wrapper clear">
		<header>
			<h1 class="titulo-blog"><?php echo $blog['titulo']; ?></h1>

			<div class="blog-descricion">
				<?php echo $blog['texto']; ?>
			</div>
		</header>
	</div>
</div>

<?php include ($Templates->file('aux-alerta.php')); ?>