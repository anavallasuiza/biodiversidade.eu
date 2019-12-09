<?php defined('ANS') or die(); ?>

<li>
	<article class="act-contido">
		<i class="icon-pencil icon-2x pull-left"></i>

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

				<p><?php __e('%s fixo un comentario en <a href="%s">%s</a>', $autor, path($relation2['route'], $table2[$relation2['url']]), $table2[$relation2['title']]);?></p>

				<div class="activity-details">
				    <?php echo textCutter($log['texto']);?>
				</div>
			</div>
		</header>
	</article>
</li>
