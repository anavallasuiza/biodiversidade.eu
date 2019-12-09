<?php
defined('ANS') or die();

$lugar = $avistamento['concellos']['nome']['title'] ?: $avistamento['localidade'];
?>

<li class="<?php echo $css ? $css : ''; ?>">
	<article class="especie-avistamento <?php echo $avistamento['especies'] ? '' : 'non-identificada'; ?>" <?php echo $mapa ? 'data-puntos="'. ($avistamento['puntos'] ? str_replace('"', '\'', json_encode($avistamento['puntos'])) : '') . '" data-centroides1="'. ($avistamento['centroides1'] ? str_replace('"', '\'', json_encode($avistamento['centroides1'])) : '') . '" data-centroides10="'. ($avistamento['centroides10'] ? str_replace('"', '\'', json_encode($avistamento['centroides10'])) : '') . '"' : ''; ?>  data-especie="<?php echo $avistamento['especies']['url']; ?>" data-codigo="<?php echo $avistamento['url']; ?>">

		<?php 
		if (empty($avistamento['especies'])) {
			echo '<span><i class="icon-exclamation-sign"></i> '.__('Sen indentificar').'</span>';
		}
		?>

		<figure class="especie-imaxe">
			<?php
			echo $Html->img(array(
				'src' => ($avistamento['imaxe'] ? ('uploads|'.$avistamento['imaxe']['imaxe']) : 'templates|img/logo-imaxe.png'),
				'alt' => ($avistamento['nome'].$avistamento['especies']['nome']),
				'width' => 265,
				'height' => 130,
				'transform' => 'zoomCrop,200,130'
			));
			?>
		</figure>

		<header>
			<h1>
				<a href="<?php echo path('avistamento', $avistamento['url']); ?>">
					<?php 
					if ($avistamento['especies']['nome']) {
						echo $avistamento['especies']['nome'] . ' - <span class="detalle-nome-especie">' . $avistamento['especies']['grupos']['nome'] . ' ' . $avistamento['especies']['familias']['nome'] . '</span>';
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
            <?php 
                $lugar = $avistamento['concellos']['nome']['title'] ? : $avistamento['localidade'];
                
                if ($lugar) {
            ?>
            <div title="<?php __e('Lugar onde se produciu a observación'); ?>">
                <i class="icon-map-marker"></i> <?php echo $lugar . ($avistamento['nome_zona'] ? ' - ' . $avistamento['nome_zona']: ''); ?>
            </div>
            <?php }?>
            <?php if ($avistamento['usuarios_autor']) { ?>
            <span class="autor-especie">
                <?php echo __('Subido por'); ?>
                <a href="<?php echo path('perfil', $avistamento['usuarios_autor']['nome']['url']); ?>">
                    <?php echo $avistamento['usuarios_autor']['nome']['title'].' '.$avistamento['usuarios_autor']['apelido1']; ?>
                </a>
            </span>
            <?php } ?>
            <?php if ($avistamento['comentarios']) { ?>
            <div><?php echo (count($avistamento['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($avistamento['comentarios'])); ?></div>
            <?php } ?>
		</header>
	</article>
</li>
