<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('Equipos'); ?>"><?php echo __('Equipos de traballo') ?></a></h1>

			<?php if ($user) { ?>
			<nav>
				<?php if ((DENTRO === false) && empty($solicitado)) { ?>
				<a class="btn" href="<?php echo path('equipo', $equipo['url']).get('phpcan_action', 'equipo-solicitude'); ?>" data-confirm="<?php __e('Queres solicitar a membresía neste equipo?'); ?>">
					<i class="icon-user"></i>
					<?php __e('Solicitar membresía'); ?>
				</a>
				<?php } ?>

				<?php if ($Acl->check('action', 'equipo-editar')) { ?>
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
					</button>

					<ul>
						<li>
							<a href="<?php echo path('editar', 'equipo', $equipo['url']); ?>">
								<i class="icon-pencil"></i>
								<?php __e('Editar'); ?>
							</a>

							<a href="<?php echo path('editores-equipo', $equipo['url']); ?>">
								<i class="icon-user"></i>
								<?php __e('Xestionar membros'); ?>
							</a>
						</li>
					</ul>
				</div>
				<?php } ?>
			</nav>	
			<?php } ?>
		</div>	
	</header>

	<div class="content wrapper ly-f1">
		<div class="subcontent ly-e1">
			<article class="perfil perfil-permalink">
				<?php
				if ($equipo['imaxe']) {
					echo $Html->img(array(
						'src' => $equipo['imaxe'],
						'width' => 100,
						'height' => 100,
						'transform' => 'zoomCrop,100,100',
						'class' => 'usuario perfil-avatar'
					));
				}
				?>

				<header>
					<h1><?php echo $equipo['titulo']; ?></h1>

					<div class="perfil-bio">
						<?php echo $equipo['texto']; ?>
					</div>
				</header>

				<ul class="membros">
					<?php foreach ($equipo['usuarios'] as $usuario) { ?>
					<li>
						<a href="<?php echo path('perfil', $usuario['nome']['url']); ?>">
							<?php
							echo $Html->img(array(
								'src' => $usuario['avatar'],
								'alt' => $usuario['nome']['title'],
								'width' => 35,
								'height' => 35,
								'transform' => 'zoomCrop,35,35'
							));

							echo ' '.$usuario['nome']['title'].' '.$usuario['apelido1'];
							?>
						</a>
					</li>
					<?php } ?>
				</ul>
			</article>

			<?php if ($logs) { ?>
			<section id="actividade">
				<ul class="listaxe">
					<?php
					foreach ($logs as $log) {
						$Templates->render('aux-activity.php', array(
							'log' => $log
						));
					}
					?>
				</ul>
			</section>
			<?php } ?>	
		</div>

		<section class="subcontent ly-e2">
			<?php if ($solicitudes) { ?>
			<div class="alert alert-success">
			    <a href="<?php echo path('editores-equipo', $equipo['url']); ?>">
			    	<?php __e('Tes %s solicitudes de membresía pendentes de revisar', $solicitudes); ?>
			    </a>
			</div>
			<?php } ?>

			<?php if ($equipos) { ?>
			<header>
				<h1><?php echo __('Outros equipos'); ?></h1>
			</header>

			<ul class="listaxe">
				<?php foreach ($equipos as $cada) { ?>
				<li><?php
					$Templates->render('aux-equipo.php', array(
						'equipo' => $cada
					));
				?></li>
				<?php } ?>
			</ul>

			<a class="btn-link" href="<?php echo path('equipos'); ?>">
				<i class="icon-arrow-right"></i>
				<?php echo __('Consulta todos os equipos'); ?>
			</a>
			<?php } ?>
		</section>
	</div>
</section>
