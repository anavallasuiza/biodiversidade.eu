<?php defined('ANS') or die(); ?>

<li>
	<article class="caderno">
		<i class="icon-file icon-3x pull-left"></i>
		<div class="content">
			<header>
				<h1><a href="<?php echo path('caderno', $proxecto['url'], $caderno['url']); ?>"><?php echo $caderno['titulo']; ?></a></h1>
			</header>

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

			<div class="caderno-intro">
				<?php echo textCutter($caderno['texto']); ?>
			</div>

			<div class="actividade-accions">
				<a href="<?php echo path('editar', 'caderno', $proxecto['url'], $caderno['url']); ?>" class="btn btn-mini icon-edit"><?php echo __('Editar') ?></a>
			</div>
		</div>
	</article>
</li>
