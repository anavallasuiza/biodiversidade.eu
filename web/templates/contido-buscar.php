<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Buscador'); ?></h1>
		</div>
	</header>

	<div class="content wrapper ly-1f">
		<section class="subcontent">
			<header>
				<h1 class="titulo"><?php __e('Resultados'); ?></h1>
				<p class="buscador"><?php __e('Buscando por <strong>%s</strong>', $Vars->var['q']); ?></p>
			</header>

			<?php if ($resultado): ?>

			<ul class="listaxe">
				<?php foreach ($resultado as $item): ?>
				<li>
					<article class="nova">
						<header>
							<h1><a href="<?php echo $item->getLink(); ?>"><?php echo $item->getHtmlTitle(); ?></a></h1>
						</header>

						<footer><?php echo $item->getLink(); ?></footer>

						<div class="nova-intro">
							<p><?php echo $item->getHtmlSnippet(); ?></p>
						</div>
					</article>
				</li>
				<?php endforeach; ?>
			</ul>

			<?php $Templates->render('aux-paxinacion.php', array('pagination' => $pagination)); ?>

			<?php else: ?>

			<h1 class="no-results"><?php __e('Non se atoparon resultados'); ?></h1>

			<?php endif; ?>
		</section>
	</div>
</section>
