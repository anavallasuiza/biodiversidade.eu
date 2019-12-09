<?php
defined('ANS') or die();

$lugar = $avistamento['concellos']['nome']['title'] ?: $avistamento['localidade'];

if (empty($avistamento['especies']) && $avistamento['especie']) {
	$avistamento['especies'] = $avistamento['especie'];
}

$tags = '';

if ($mapa) {
	$puntos = $avistamento['puntos'] ? "'" . json_encode($avistamento['puntos']) . "'" : '""';
	$centroides1 = $avistamento['centroides1'] ? "'" . json_encode($avistamento['centroides1']) . "'" : '""';
	$centroides10 = $avistamento['centroides10'] ? "'" . json_encode($avistamento['centroides10']) . "'" : '""';

	$tags = 'data-puntos='.$puntos.' data-centroides1='.$centroides1.' data-centroides10='.$centroides10;
}

?>

<li id="<?php echo $avistamento['url']; ?>" class="<?php echo $css ? $css : ''; ?>">
	<article class="especie-avistamento" <?php echo $tags ?  : ''; ?>  data-especie="<?php echo $avistamento['especies']['url']; ?>" data-codigo="<?php echo $avistamento['url']; ?>">
		<figure class="especie-imaxe">
			<?php
			echo $Html->img(array(
				'src' => ($avistamento['imaxe'] ? ('uploads|'.$avistamento['imaxe']['imaxe']) : 'templates|img/logo-imaxe.png'),
				'alt' => ($avistamento['nome'].$avistamento['especies']['nome']),
				'width' => 130,
				'height' => 130,
				'transform' => 'zoomCrop,130,130'
			));
			?>
		</figure>

		<header>
			<h1>
                <?php if ($checkbox) { ?>
                <input type="checkbox" name="avistamentos[id][]" value="<?php echo $avistamento['id']; ?>" />
                <?php } ?>

				<a href="<?php echo path('avistamento', $avistamento['url']); ?>">
					<?php 
					if ($avistamento['especies']['nome']) {
						echo $avistamento['especies']['nome'] . ' - ' . $avistamento['especies']['grupos']['nome'] . ' ' . $avistamento['especies']['familias']['nome'];
					} else { 
						echo $avistamento['posible'] ? __('Posible %s', $avistamento['posible']) : __('Sen identificar');
					} 
					?>
				</a>

				<?php if ($avistamento['validado']) { ?>
				<span class="estado solucionada"<?php echo $avistamento['usuarios_validador'] ? (' title="'.__('Validado por %s', $avistamento['usuarios_validador']['nome']['title']).'"') : ''; ?>><i class="icon-thumbs-up"></i> <?php __e('Validada'); ?></span>
				<?php } else { ?>
				<span class="estado activa"><i class="icon-thumbs-down"></i> <?php __e('Non validada'); ?></span>
				<?php } ?>
			</h1>

			<ul class="avistamento-informacion">
				<?php if ($lugar) { ?>
				<li>
					<?php echo __('Lugar:') ?>
					<strong><?php echo $lugar . ($avistamento['nome_zona'] ? ' - ' . $avistamento['nome_zona']: ''); ?></strong>
				</li>
				<?php }?>

				<?php if (strtotime($avistamento['data_observacion']) > 0) { ?>
				<li>
					<?php echo __('Día e hora:'); ?>
					<strong><?php echo $Html->time($avistamento['data_observacion'], '', 'absolute-hour'); ?></strong>
				</li>
				<?php } ?>

				<?php if ($avistamento['usuarios_autor']) { ?>
				<li class="user">
					<?php echo __('Subido por'); ?>
					<a href="<?php echo path('perfil', $avistamento['usuarios_autor']['nome']['url']); ?>">
						<?php echo $avistamento['usuarios_autor']['nome']['title'].' '.$avistamento['usuarios_autor']['apelido1']; ?>
					</a>
					<?php echo $Html->time($avistamento['data_alta']); ?>
				</li>
				<?php } ?>

				<?php if ($avistamento['puntos']) { ?>
				<li title="">
					<i class="icon-map-marker"></i> <?php echo count($avistamento['puntos']) > 1 ? __('%s xeolocalizacións', count($avistamento['puntos'])) : __('%s xeolocalización', count($avistamento['puntos'])); ?>
				</li>
				<?php } ?>
			</ul>
		</header>
	</article>
</li>
