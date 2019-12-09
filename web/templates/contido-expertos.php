<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Editores'); ?></h1>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent">
			<article class="listado-editores">
				<div class="ly-11">
					<?php $metade = ceil(count($editores) / 2); ?>

					<ul class="listaxe ly-e1">
						<?php
						foreach ($editores as $num => $usuario) {
							echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

							$Templates->render('aux-editor.php', array(
								'usuario' => $usuario
							));
						}
						?>
					</ul>
				</div>
			</article>
		</section>
	</div>
</section>
