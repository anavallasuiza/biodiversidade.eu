<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Blogues e proxectos'); ?></h1>

			<nav>
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<?php if ($user) { ?>

						<li>
							<a href="<?php echo path('editar', 'blog'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo blogue'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo path('editar', 'proxecto'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo proxecto'); ?>
							</a>
						</li>

						<?php } else { ?>

						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo blogue'); ?>
							</a>
						</li>
						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo proxecto'); ?>
							</a>
						</li>

						<?php } ?>
					</ul>
				</div>
			</nav>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<div class="ly-11">
			<?php $metade = ceil(count($blogs) / 2); ?>

			<ul class="listaxe ly-e1">
				<?php
				foreach ($blogs as $num => $blog) {
					echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

					$Templates->render('aux-blog.php', array(
						'blog' => $blog
					));
				}
				?>
			</ul>

			<?php
			$Templates->render('aux-paxinacion.php', array(
				'pagination' => $Data->pagination
			));
			?>
		</div>
	</div>

    <div class="content wrapper ly-f1 sidebar-ficha">
        <?php if (empty($user)) { ?>

        <aside class="info">
            <header>
                <h1><?php __e('Que son os proxectos?'); ?></h1>
            </header>

            <div class="intro">
                <?php __e('Explicacion que son os proxectos lateral blogs/proxectos.'); ?>
            </div>

            <a class="btn login modal-ajax" href="<?php echo path('entrar'); ?>">
                <i class="icon-signin"></i> <?php __e('Entra ou rexístrate'); ?>
            </a>
        </aside>

        <?php if ($proxectos['todos']) { ?>

        <header>
            <h1><?php __e('Estes son algúns dos proxectos que temos agora'); ?></h1>
        </header>

        <ul class="listaxe">
            <?php
            foreach ($proxectos['todos'] as $proxecto) {
                $Templates->render('aux-proxecto.php', array(
                    'proxecto' => $proxecto,
                    'meu' => false
                ));
            }
            ?>
        </ul>

        <?php } ?>

        <?php } else { ?>

        <?php if (empty($proxectos['meus'])) { ?>

        <aside class="info">
            <header>
                <h1><?php __e('Que son os proxectos?'); ?></h1>
            </header>

            <div class="intro">
                <?php __e('Explicacion que son os proxectos lateral blogs/proxectos.'); ?>
            </div>

            <a class="btn" href="<?php echo path('editar', 'proxecto'); ?>">
                <i class="icon-pencil"></i> <?php __e('Crea un'); ?>
            </a>
        </aside>

        <?php } else { ?>

        <header>
            <h1><?php __e('Os meus proxectos'); ?></h1>
        </header>

        <ul class="listaxe">
            <?php
            foreach ($proxectos['meus'] as $proxecto) {
                $Templates->render('aux-proxecto.php', array(
                    'proxecto' => $proxecto,
                    'meu' => true
                ));
            }
            ?>
        </ul>

        <?php } if ($proxectos['todos']) { ?>

        <header>
            <h1><?php __e('Outros proxectos de biodiversidade.eu'); ?></h1>
        </header>

        <ul class="listaxe">
            <?php
            foreach ($proxectos['todos'] as $proxecto) {
                $Templates->render('aux-proxecto.php', array(
                    'proxecto' => $proxecto,
                    'meu' => false
                ));
            }
            ?>
        </ul>

        <?php } ?>

        <?php } ?>
    </div>
</section>
