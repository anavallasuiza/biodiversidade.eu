<article class="equipo">
	<?php
	if ($equipo['imaxe']) {
		echo $Html->img(array(
			'src' => $equipo['imaxe'],
			'width' => 60,
			'height' => 60,
			'transform' => 'zoomCrop,60,60',
			'class' => 'imaxe-equipo'
		));
	}
	?>
	<header>
		<h1><a href="<?php echo path('equipo', $equipo['url']); ?>"><?php echo $equipo['titulo']; ?></a></h1>
	</header>

	<div class="perfil-bio"><?php echo textCutter(strip_tags($equipo['texto'])); ?></div>
</article>