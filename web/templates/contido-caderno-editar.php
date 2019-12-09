<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('proxecto', $caderno['proxectos']['url']); ?>"><?php __e('Proxectos'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php __e('Caderno'); ?></h2>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="subcontent ly-e1">
			<article class="caderno caderno-permalink">
				<div>
					<header id="editable" contenteditable="true">
						<h1>Título do caderno</h1>
					</header>

					<div class="caderno-intro" id="editable" contenteditable="true">
						Contido do caderno
					</div>

					<p><a class="btn" href="#"><i class="icon-save"></i> Gardar</a></p>


					<footer>
						<p><?php __e('Creado o %s', $Html->time($caderno['data_alta'])); ?></p>

						<?php if ($caderno['data_actualizado']) { ?>
						<p><?php __e('Actualizado o %s', $Html->time($caderno['data_actualizado'])); ?></p>
						<?php } ?>

						<p class="autor">
							<?php __e('Creado por'); ?>
							<a href="<?php echo path('perfil', $caderno['usuarios_autor']['nome']['url']); ?>"><?php echo $caderno['usuarios_autor']['nome']['title'].' '.$caderno['usuarios_autor']['apelido1']; ?></a>
						</p>
					</footer>
				</div>
			</article>

			<?php
			$Templates->render('aux-comentarios.php', array(
				'comentarios' => $comentarios
			));
			?>
		</section>

		<?php if ($logs) { ?>

		<section class="subcontent ly-e2">
			<header>
				<h1><?php __e('Últimas actualizacións'); ?></h1>
				<ul class="listaxe-enlaces">
					<?php foreach ($logs as $log) { ?>
					<li><?php echo ucfirst($Html->time($log['date'], '', 'absolute')); ?><a class="user" href="<?php echo $log['usuarios_autor']['nome']['url']; ?>">: <?php echo $log['usuarios_autor']['nome']['title'].' '.$log['usuarios_autor']['apelido1']; ?></a> <?php __e('actualizou este documento'); ?></li>
					<?php } ?>
				</ul>
			</header>
		</section>

		<?php } ?>
	</div>
</section>
