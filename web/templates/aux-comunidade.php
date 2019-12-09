<?php defined('ANS') or die(); ?>

<li>
	<article class="ficha-comunidade">
		<figure>
			<a href="<?php echo path('comunidade', $comunidade['url']); ?>">
				<?php
				echo $Html->img(array(
					'src' => $comunidade['logo'],
					'alt' => $comunidade['nome'],
					'class' => 'miniatura',
					'transform' => 'resize,220,',
					'class' => 'perfil-logo'
				));
				?>
			</a>
		</figure>

		<header>
			<h1><a href="<?php echo path('comunidade', $comunidade['url']); ?>"><?php echo $comunidade['nome']; ?></a></h1>

			<ul class="datos">
				<?php if ($comunidade['web']) { ?>
				<li><?php __e('Web'); ?>: <a href="<?php echo $comunidade['web']; ?>" target="_blank"><?php echo $comunidade['web']; ?></a> |</li> 
				<?php } if ($comunidade['telefono']) { ?>
				<li><?php __e('Teléfono'); ?>: <a href="tel:<?php echo $comunidade['telefono']; ?>"><?php echo $comunidade['telefono']; ?></a> |</li> 
				<?php } if ($comunidade['correo']) { ?>
				<li><?php __e('Correo electrónico'); ?>: <a href="mailto:<?php echo $comunidade['correo']; ?>"><?php echo $comunidade['correo']; ?></a></li> 
				<?php } ?>
			</ul>
		</header>

		<div class="descricion">
			<?php echo textCutter($comunidade['texto'], 200); ?>
		</div>
	</article>
</li>
