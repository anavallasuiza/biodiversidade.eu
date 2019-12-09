<?php defined('ANS') or die(); ?>

<li>
	<article class="act-contido">
		<i class="icon-bulhorn icon-2x pull-left"></i>

		<header>
			<div class="usuario">
				<?php
				echo $Html->img(array(
					'src' => $log['usuarios_autor']['avatar'],
					'alt' => $log['usuarios_autor']['nome']['title'],
					'width' => 30,
					'height' => 30,
					'transform' => 'zoomCrop,30,30',
					'class' => 'usuario'
				));
				?>
				<?php echo ucfirst($Html->time($log['date'])); ?>
				<p><a href="#">Aymará Ghiglione</a> notificou <a href="#">lorem ipsum</a></p>
			</div>
		</header>
	</article>
</li>
