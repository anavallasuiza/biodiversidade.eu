<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('blogs'); ?>"><?php echo __('Blogs'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $blog['titulo']; ?></h2>

			<nav>
                <?php if ($user) { ?>
				<a class="btn" href="<?php echo get(array('phpcan_action' => 'vixiar')); ?>">
                    <i class="icon-eye-open"></i> <?php echo $blog['vixiar'] ? __('Deixar de seguir') : __('Seguir este blog'); ?>
                </a>
                <?php } ?>

				<a class="btn icon-rss" href="<?php echo path('rss', 'blog', $blog['url']); ?>"><?php __e('Subscríbete'); ?></a>

				<?php if ($user && $Acl->check('action', 'blog-editar')) { ?>
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
					</button>

					<ul>
						<li>
							<a href="<?php echo path('editar', 'blog', $blog['url']); ?>">
								<i class="icon-pencil"></i>
								<?php __e('Editar blog'); ?>
							</a>
						</li>

						<li>
							<a href="<?php echo path('editar', 'post', $blog['url']); ?>">
								<i class="icon-plus"></i>
								<?php __e('Novo post'); ?>
							</a>
						</li>

						<li>
							<a href="<?php echo path('editores-blog', $blog['url']); ?>">
								<i class="icon-user"></i>
								<?php __e('Xestionar editores'); ?>
							</a>
						</li>
					</ul>
				</div>
				<?php } ?>
			</nav>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<?php if ($posts) { ?>
		<section class="subcontent ly-e1">
			<ul class="listaxe">
				<?php
				foreach ($posts as $post) {
					$Templates->render('aux-post.php', array(
						'post' => $post
					));
				}
				?>
			</ul>

			<?php
			$Templates->render('aux-paxinacion.php', array(
				'pagination' => $Data->pagination
			));
			?>
		</section>
		<?php } ?>

		<?php if ($comentarios || $proxectos) { ?>
		<section class="subcontent ly-e2">
			<?php if ($proxectos) { ?>
			<header>
				<h1><?php __e('Proxectos asociados'); ?></h1>
			</header>

			<div>
				<ul class="listaxe">
					<?php foreach ($proxectos as $proxecto) { ?>
					<li>
						<a href="<?php echo path('proxecto', $proxecto['url']); ?>"><?php echo $proxecto['titulo']; ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>

			<?php if ($comentarios) { ?>
			<header>
				<h1><?php __e('Últimos comentarios'); ?></h1>
			</header>

			<div>
				<ul class="listaxe">
					<?php
					foreach ($comentarios as $comentario) {
						$Templates->render('aux-comentario-ultimos.php', array(
							'comentario' => $comentario
						));
					}
					?>
				</ul>
			</div>
			<?php } ?>
		</section>
		<?php } ?>
	</div>
</section>
