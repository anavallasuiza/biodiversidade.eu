<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Comunidade'); ?></h1>

			<nav>
                <?php if ($user) { ?>
				<a class="btn" href="<?php echo path('editar', 'comunidade'); ?>">
                    <i class="icon-plus"></i> <?php __e('Nova ficha'); ?>
                </a>

                <?php } else { ?>

				<a class="btn" class="modal-ajax" href="<?php echo path('entrar'); ?>">
                    <i class="icon-plus"></i> <?php __e('Nova ficha'); ?>
                </a>
                <?php } ?>
			</nav>
		</div>
	</header>

	<div class="content wrapper">
			<div class="ly-111">
				<?php foreach ($comunidade as $column => $rows) { ?>
				<ul class="listaxe ly-e<?php echo $column + 1; ?>">
					<?php
					foreach ($rows as $cell) {
						$Templates->render('aux-comunidade.php', array(
							'comunidade' => $cell
						));
					}
					?>
				</ul>
				<?php } ?>

				<?php
				$Templates->render('aux-paxinacion.php', array(
					'pagination' => $Data->pagination
				));
				?>
			</div>
		</section>
	</div>
</section>
