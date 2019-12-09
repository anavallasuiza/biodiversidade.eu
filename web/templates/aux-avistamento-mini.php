<?php 
defined('ANS') or die(); 

$lugar = $concellos['nome']['title'] ?: $localidade;
?>

<div id="<?php echo $url; ?>" class="datos-avistamento <?php echo $css ?: ''; ?>">
	<article class="especie-avistamento mediano" data-puntos="<?php echo $puntos; ?>" data-centroides1="<?php echo $centroides1; ?>" data-centroides10="<?php echo $centroides10; ?>" data-shapes="<?php echo $shapes; ?>" data-especie="<?php echo $especie['url']; ?>" data-parent="<?php echo $especie['parent'] ? $especie['parent']['url']: '';?>" data-codigo="<?php echo $url; ?>">
		<figure class="especie-imaxe">
			<?php
			echo $Html->img(array(
				'src' => ($imaxe ? ('uploads|'.$imaxe['imaxe']) : 'templates|img/logo-imaxe.png'),
				'alt' => ($nome.$especie['nome']),
				'width' => 80,
				'height' => 80,
				'transform' => 'zoomCrop,80,80'
			));
			?>
		</figure>

		<header>
			<h1>
				<a href="<?php echo path('avistamento', $url); ?>">
                    <?php if ($especie['nome']) {
					   echo $especie['nome'] . ' - ' . $especie['grupos']['nome'] . ' ' . $especie['familias']['nome'];
                    } else { 
						echo $posible ? __('Posible %s', $posible) : __('Sen identificar');
					} ?>
				</a>

				<?php if ($validado) { ?>
				<span class="estado solucionada"<?php echo $usuarios_validador ? (' title="'.__('Validado por %s', $usuarios_validador['nome']['title']).'"') : ''; ?>><i class="icon-thumbs-up"></i> <?php __e('Validada'); ?></span>
				<?php } else { ?>
				<span class="estado activa"><i class="icon-thumbs-down"></i> <?php __e('Non validada'); ?></span>
				<?php } ?>
			</h1>

			<ul class="avistamento-informacion">
				<?php if ($lugar) { ?>
				<li title="<?php __e('Lugar onde se produciu a observación'); ?>">
					<i class="icon-map-marker"></i> <?php echo $lugar . ($nome_zona ? ' - ' . $nome_zona: ''); ?>
				</li>
				<?php }?>

				<?php if (strtotime($data_observacion) > 0) { ?>
				<li title="<?php __e('Data e hora aproximadas da observación'); ?>">
					<i class="icon-calendar"></i> <?php echo $Html->time($data_observacion, '', 'absolute-hour-short'); ?>
				</li>
				<?php } ?>

				<?php if ($usuarios_autor) { ?>
				<li title="<?php __e('Usuario que diud e alta a observación'); ?>">
					<i class="icon-user"></i>
					<a href="<?php echo path('perfil', $usuarios_autor['nome']['url']); ?>">
						<?php echo $usuarios_autor['nome']['title'].' '.$usuarios_autor['apelido1']; ?>
					</a>
					<?php echo $Html->time($data_alta, '', 'short'); ?>
				</li>
				<?php } ?>
				
				<?php if ($punto) { ?>
				<li title="">
					<i class="icon-location-arrow"></i> <?php echo $punto; ?>
				</li>
				<?php } ?>

				<?php if ($xeolocalizacions) { ?>
				<li title="">
					<i class="icon-map-marker"></i> <?php __e('%s xeolocalizacións', count($xeolocalizacions)); ?>
				</li>
				<?php } ?>
			</ul>
		</header>

		<footer>
			<a href="<?php echo path('especie', $especie['url']); ?>"><?php __e('Ficha da especie'); ?></a>
			<a href="<?php echo path('avistamento', $url); ?>"><?php __e('Ficha da observacion'); ?></a>

			<?php if ((EDITOR === true) || ($user && ($usuarios_autor['id'] === $user['id']))) {?>
			<a href="<?php echo path('editar', 'avistamento', $url); ?>"><?php __e('Editar ou eliminar observación'); ?></a>
			<?php } ?>
		</footer>
	</article>
</div>
