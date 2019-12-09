<?php defined('ANS') or die(); ?>



<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('rotas'); ?>"><?php __e('Resultados'); ?></a></h1>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent ly-e1">
			<header>
				<h1><?php __e('Buscando por %s', $Vars->str('q')); ?></h1>
			</header>

			<?php if ($results['results']): ?>

			<ul class="listaxe">
				<?php foreach ($results['results'] as $result): ?>
				<li>
					<article class="resultado">
						<header>
							<h1><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. </a></h1>
							<small>dolor in reprehenderit.com</small>
						</header>
						<p>dolor in reprehenderit dolor in reprehenderitdolor in reprehenderit</p>
					</article>
				</li>
				<?php endforeach; ?>
			</ul>

			<?php else: ?>
			<header>
				<h1><?php __e('Non se atoparon resultados'); ?></h1>
			<header>
			<?php endif; ?>
		</section>
	</div>
</section>