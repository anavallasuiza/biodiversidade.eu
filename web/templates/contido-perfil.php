<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Perfil de usuario'); ?></h1>

			<?php if ($usuario['id'] === $user['id']) { ?>
			<nav>
				<?php if ($Acl->check('action', 'importar-observacions')) { ?>
				<a href="<?php echo path('importar-observacions'); ?>" class="btn icon-upload"><?php __e('Importar observacions'); ?></a>
				<?php } else { ?>
                <a href="<?php echo path('solicitar-importar'); ?>" class="btn icon-upload modal-ajax"><?php __e('Solicitar importar bloques de datos'); ?></a>
                <?php } ?>

				<a a href="<?php echo path('perfil-editar'); ?>" class="btn icon-edit"><?php __e('Editar o perfil'); ?></a>

				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<li>
							<a href="<?php echo path('editar-grupo', 'avistamento'); ?>">
								<i class="icon-plus"></i> <?php __e('Un avistamento'); ?>
							</a>
						</li>

						<?php if ($Acl->check('action', 'nova-crear')) { ?>
						<li>
							<a href="<?php echo path('editar', 'nova'); ?>">
								<i class="icon-plus"></i> <?php __e('Unha noticia'); ?>
							</a>
						</li>
						<?php } ?>

						<li>
							<a href="<?php echo path('editar', 'evento'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo evento'); ?>
							</a>
						</li>

						<li>
							<a href="<?php echo path('editar', 'equipo'); ?>">
								<i class="icon-plus"></i> <?php __e('Un equipo'); ?>
							</a>
						</li>
					</ul>
				</div>
			</nav>
			<?php } else if ($user) { ?>
            <nav>
                <?php if (in_array('editor', arrayKeyValues($user['roles'], 'code')) && !in_array('editor', arrayKeyValues($usuario['roles'], 'code')) && $usuario['id'] !== $user['id']) { ?>
                <a href="<?php echo path('actualizar-nivel', $usuario['id']); ?>" class="btn modal-ajax"><?php __e('Actualizar o nivel'); ?></a>
                <?php } ?>

                <a a href="<?= path('perfil', $usuario['nome']['url'], 'mensaxe'); ?>" class="btn"><?php __e('Enviar mensaxe'); ?></a>
            </nav>
            <?php } ?>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<div class="subcontent">
            <?php if (in_array('nivel-3', arrayKeyValues($user['roles'], 'code')) && $usuario['solicita_importar']) { ?>
            <div class="ly-row">
                <h3>
                    <?php __e('Este usuario ha solicitado permisos para importar bloques de datos.'); ?>
                    <a href="<?php echo path() . get('phpcan_action', 'perfil-importar'); ?>" class="btn btn-highlight">
                        <?php __e('Dar permisos'); ?>
                    </a>
                </h3>
            </div>
            <?php }?>

			<article class="perfil perfil-permalink">
				<?php
				echo $Html->img(array(
					'src' => $usuario['avatar'],
					'alt' => $usuario['nome']['title'],
					'width' => 200,
					'height' => 200,
					'transform' => 'zoomCrop,200,200',
					'class' => 'usuario perfil-avatar'
				));
				?>

				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/editor.png'),
               		'class' => 'editor'
                ));
	            ?>

				<header>
					<h1><?php echo $usuario['nome']['title'].' '.$usuario['apelido1']; ?></h1>

                    <?php if ($usuario['especialidade']) { ?>
                    <h3><?php echo $usuario['especialidade']; ?></h3>
                    <?php } ?>

					<div class="perfil-bio">
						<?php echo $usuario['bio']; ?>
					</div>

					<ul class="perfil-links">
						<?php if ($usuario['facebook']) { ?><li><a href="<?php echo $usuario['facebook']; ?>"><i class="icon-facebook"></i> <?php echo __('Facebook'); ?></a></li><?php } ?>
						<?php if ($usuario['twitter']) { ?><li><a href="<?php echo $usuario['twitter']; ?>"><i class="icon-twitter"></i> <?php echo __('Twitter'); ?></a></li><?php } ?>
						<?php if ($usuario['linkedin']) { ?><li><a href="<?php echo $usuario['linkedin']; ?>"><i class="icon-linkedin"></i> <?php echo __('Linkedin'); ?></a></li><?php } ?>
					</ul>
				</header>
			</article>

			<div class="tabs tabs-perfil">
				<ul>
					<?php if ($logs) { ?><li><a href="#logs"><?php echo __('A túa actividade'); ?></a></li><?php } ?>
					<?php if ($actividade) { ?><li><a href="#actividade"><?php echo __('Actividade nos contidos que vixías'); ?></a></li><?php } ?>
					<?php if ($rotas) { ?><li><a href="#lugares"><?php echo __('Os meus lugares'); ?></a></li><?php } ?>
					<?php if ($avistamentos) { ?><li><a href="#avistamentos"><?php echo __('Os meus avistamentos'); ?></a></li><?php } ?>
					<?php if ($vixiadas) { ?><li><a href="#vixiadas"><?php echo __('Especies vixiadas'); ?></a></li><?php } ?>
					<?php if ($proxectos) { ?><li><a href="#proxectos"><?php echo __('Os meus proxectos'); ?></a></li><?php } ?>
					<?php if ($equipos) { ?><li><a href="#equipos"><?php echo __('Os meus equipos'); ?></a></li><?php } ?>
					<?php if ($notas) { ?><li><a href="#notas"><?php echo __('As miñas notas'); ?></a></li><?php } ?>
					<?php if ($Acl->check('action', 'especie-validar')) { ?><li><a href="#especies-validar"><i class="icon-ok-sign"></i> <?php __e('Especies a validar'); ?></a></li><?php } ?>
					<?php if ($Acl->check('action', 'avistamento-validar')) { ?><li><a href="#avistamentos-validar"><i class="icon-ok-sign"></i> <?php __e('Avistamentos a validar'); ?></a></li><?php } ?>
					<?php if ($Acl->check('action', 'rota-validar')) { ?><li><a href="#rotas-validar"><i class="icon-ok-sign"></i> <?php __e('Rotas a validar'); ?></a></li><?php } ?>
				</ul>

				<?php if ($logs) { ?>
				<section id="logs">
					<ul class="listaxe">
						<?php
						foreach ($logs as $row) {
							$Templates->render('aux-activity-expanded.php', array(
								'log' => $row
							));
						}
						?>
					</ul>

					<?php
					$Templates->render('aux-paxinacion.php', array(
						'pagination' => $pagination['logs'],
						'p' => 'p-logs',
						'anchor' => 'logs'
					));
					?>
				</section>
				<?php } ?>

				<?php if ($actividade) { ?>
				<section id="actividade">
					<ul class="listaxe">
						<?php
						foreach ($actividade as $row) {
							$Templates->render('aux-activity-expanded.php', array(
								'amosar_usuario' => true,
								'log' => $row
							));
						}
						?>
					</ul>

					<?php
					$Templates->render('aux-paxinacion.php', array(
						'pagination' => $pagination['actividade'],
						'p' => 'p-actividade',
						'anchor' => 'actividade'
					));
					?>
				</section>
				<?php } ?>

				<?php if ($rotas) { ?>
				<section id="lugares">
					<div class="ly-11">
						<?php $metade = ceil(count($rotas) / 2); ?>

						<ul class="listaxe ly-e1">
							<?php
							foreach ($rotas as $num => $row) {
								echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

								$Templates->render('aux-rota.php', array(
									'rota' => $row
								));
							}
							?>
						</ul>

						<?php
						$Templates->render('aux-paxinacion.php', array(
							'pagination' => $pagination['rotas'],
							'p' => 'p-rotas',
							'anchor' => 'rotas'
						));
						?>
					</div>
				</section>
				<?php } ?>

				<?php if ($avistamentos) { ?>
				<section id="avistamentos" class="listaxe">
					<div class="ly-11">
						<?php $metade = ceil(count($avistamentos) / 2); ?>

						<ul class="listaxe ly-e1">
							<?php
							foreach ($avistamentos as $num => $row) {
								echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

								$Templates->render('aux-avistamento.php', array(
									'avistamento' => $row
								));
							}
							?>
						</ul>

						<?php
						$Templates->render('aux-paxinacion.php', array(
							'pagination' => $pagination['avistamentos'],
							'p' => 'p-avistamentos',
							'anchor' => 'avistamentos'
						));
						?>
					</div>
				</section>
				<?php } ?> 				

				<?php if ($vixiadas) { ?>
				<section id="vixiadas" class="listaxe">
					<div class="ly-11">
						<?php $metade = ceil(count($vixiadas) / 2); ?>

						<ul class="listaxe ly-e1">
							<?php
							foreach ($vixiadas as $num => $row) {
								echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

								$Templates->render('aux-avistamento.php', array(
									'avistamento' => $row
								));
							}
							?>
						</ul>

						<?php
						$Templates->render('aux-paxinacion.php', array(
							'pagination' => $pagination['vixiadas'],
							'p' => 'p-vixiadas',
							'anchor' => 'vixiadas'
						));
						?>
					</div>
				</section>
				<?php } ?> 

				<?php if ($proxectos) { ?>
				<section id="proxectos">
					<div class="ly-11">
						<?php $metade = ceil(count($proxectos) / 2); ?>

						<ul class="listaxe ly-e1">
							<?php
							foreach ($proxectos as $num => $row) {
								echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

								$Templates->render('aux-proxecto.php', array(
									'proxecto' => $row,
									'meu' => true
								));
							}
							?>
						</ul>

						<?php
						$Templates->render('aux-paxinacion.php', array(
							'pagination' => $pagination['proxectos'],
							'p' => 'p-proxectos',
							'anchor' => 'proxectos'
						));
						?>
					</div>
				</section>
				<?php } ?>

				<?php if ($equipos) { ?>
				<section id="equipos">
					<div class="ly-11">
						<?php $metade = ceil(count($equipos) / 2); ?>

						<ul class="listaxe ly-e1">
							<?php
							foreach ($equipos as $num => $row) {
								echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

								$Templates->render('aux-equipo.php', array(
									'equipo' => $row
								));
							}
							?>
						</ul>

						<?php
						$Templates->render('aux-paxinacion.php', array(
							'pagination' => $pagination['equipos'],
							'p' => 'p-equipos',
							'anchor' => 'equipos'
						));
						?>
					</div>
				</section>
				<?php } ?>

				<?php if ($notas) { ?>
				<section id="notas">
					<div class="ly-11">
						<?php $metade = ceil(count($notas) / 2); ?>

						<ul class="listaxe ly-e1">
							<?php
							foreach ($notas as $num => $row) {
								echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

								$Templates->render('aux-nota.php', array(
									'nota' => $row
								));
							}
							?>
						</ul>

						<?php
						$Templates->render('aux-paxinacion.php', array(
							'pagination' => $pagination['notas'],
							'p' => 'p-notas',
							'anchor' => 'notas'
						));
						?>
					</div>
				</section>
				<?php } ?>

				<?php if ($Acl->check('action', 'especie-validar')) { ?>
				<section id="especies-validar" class="listado-especies">
					<?php if ($especies_validar) { ?>

					<form class="formulario-pisos" method="post">
						<?php $metade = ceil(count($especies_validar) / 2); ?>

						<ul class="especies">
							<?php
							foreach ($especies_validar as $num => $row) {
								$Templates->render('aux-especie.php', array(
									'especie' => $row,
									'checkbox' => true
								));
							}
							?>
						</ul>

						<fieldset class="footer">
							<p class="formulario-buttons">
								<?php
								echo $Form->button(array(
									'type' => 'submit',
									'name' => 'phpcan_action',
									'value' => 'especie-validar',
									'class' => 'btn btn-highlight',
									'text' => ('<i class="icon-save"></i> '.__('Validar'))
								));
								?>
							</p>
						</fieldset>

						<?php
						$Templates->render('aux-paxinacion.php', array(
							'pagination' => $pagination['especies_validar'],
							'p' => 'p-especies_validar',
							'anchor' => 'especies-validar'
						));
						?>
					</form>

					<?php } else { ?>

					<div class="alert alert-success">
						<?php __e('Non existen contidos pendentes de validar'); ?>
					</div>

					<?php } ?>
				</section>
				<?php } ?>

				<?php if ($Acl->check('action', 'avistamento-validar')) { ?>
				<section id="avistamentos-validar" class="listaxe">
					<div class="ly-11">
						<form class="formulario-pisos filtro" method="get">
							<fieldset>
								<label>
									<?php
									echo $Form->select(array(
										'options' => $observadores,
										'variable' => 'observador',
										'option_title' => 'nome',
										'option_value' => 'id',
										'class' => 'w3',
										'first_option' => '',
										'data-placeholder' => __('Observador')
									));
									?>
								</label>

								<label>
									<button type="submit" class="btn btn-highlight">
										<i class="icon-filter"></i>
										<?php __e('Filtrar'); ?>
									</button>
								</label>
							</fieldset>
						</form>

						<?php if ($avistamentos_validar) { ?>

						<form class="formulario-pisos" method="post">
							<?php $metade = ceil(count($avistamentos_validar) / 2); ?>

							<ul class="listaxe ly-e1">
								<?php
								foreach ($avistamentos_validar as $num => $row) {
									echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

									$Templates->render('aux-avistamento.php', array(
										'avistamento' => $row,
										'checkbox' => true
									));
								}
								?>
							</ul>

							<fieldset>
								<label>
									<input type="checkbox" value="#avistamentos-validar .listaxe" class="checkall" />
									<?php __e('Marcar todos os elementos listados nesta páxina'); ?>
								</label>
							</fieldset>

							<fieldset class="footer">
								<p class="formulario-buttons">
									<?php
									echo $Form->button(array(
										'type' => 'submit',
										'name' => 'phpcan_action',
										'value' => 'avistamento-validar',
										'class' => 'btn btn-highlight',
										'text' => ('<i class="icon-save"></i> '.__('Validar'))
									));
									?>
								</p>
							</fieldset>

							<?php
							$Templates->render('aux-paxinacion.php', array(
								'pagination' => $pagination['avistamentos_validar'],
								'p' => 'p-avistamentos_validar',
								'anchor' => 'avistamentos-validar'
							));
							?>
						</form>

						<?php } else { ?>

						<div class="alert alert-success">
							<?php __e('Non existen contidos pendentes de validar'); ?>
						</div>

						<?php } ?>
					</div>
				</section>
				<?php } ?>

				<?php if ($Acl->check('action', 'rota-validar')) { ?>
				<section id="rotas-validar" class="listaxe">
					<div class="ly-11">
						<?php if ($rotas_validar) { ?>

						<form class="formulario-pisos" method="post">
							<?php $metade = ceil(count($rotas_validar) / 2); ?>

							<ul class="listaxe ly-e1">
								<?php
								foreach ($rotas_validar as $num => $row) {
									echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

									$Templates->render('aux-rota.php', array(
										'rota' => $row,
										'checkbox' => true
									));
								}
								?>
							</ul>

							<fieldset class="footer">
								<p class="formulario-buttons">
									<?php
									echo $Form->button(array(
										'type' => 'submit',
										'name' => 'phpcan_action',
										'value' => 'rota-validar',
										'class' => 'btn btn-highlight',
										'text' => ('<i class="icon-save"></i> '.__('Validar'))
									));
									?>
								</p>
							</fieldset>

							<?php
							$Templates->render('aux-paxinacion.php', array(
								'pagination' => $pagination['rotas_validar'],
								'p' => 'p-rotas_validar',
								'anchor' => 'rotas-validar'
							));
							?>
						</form>

						<?php } else { ?>

						<div class="alert alert-success">
							<?php __e('Non existen contidos pendentes de validar'); ?>
						</div>

						<?php } ?>
					</div>
				</section>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
