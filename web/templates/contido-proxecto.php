<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('blogs'); ?>"><?php __e('Blogs e proxectos'); ?></a></h1>

			<?php if ($user) { ?>
			<nav>
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
					</button>

					<ul>
						<li>
							<a href="<?php echo path('editar', 'proxecto'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo proxecto'); ?>
							</a>
						</li>

                        <?php if ($Acl->check('action', 'caderno-crear')) { ?>
						<li>
							<a href="<?php echo path('editar', 'caderno', $proxecto['url']); ?>">
								<i class="icon-plus"></i> <?php __e('Novo caderno'); ?>
							</a>
						</li>
                        <?php } ?>

						<?php if ($Acl->check('action', 'proxecto-editar')) { ?>
						<li>
							<a href="<?php echo path('editar', 'proxecto', $proxecto['url']); ?>">
								<i class="icon-pencil"></i> <?php __e('Editar'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo path('editores-proxecto', $proxecto['url']); ?>">
								<i class="icon-user"></i> <?php __e('Xestionar usuarios'); ?>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</nav>
			<?php } ?>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="subcontent ly-e1">
			<article class="proxecto">
				<header>
					<h1><?php echo $proxecto['titulo']; ?></h1>
				</header>

                <div>
                    <small><?php __e('Código de proxecto'); ?>:
                    <strong><?php echo $proxecto['id']; ?></strong></small>
                </div>

				<?php
				if ($adxuntos) {
					$Templates->render('aux-adxuntos.php', array(
						'adxuntos' => $adxuntos
					));
				}
				?>

				<div class="proxecto-intro">
					<?php echo $proxecto['intro']; ?>
				</div>

				<ul class="membros">
					<?php foreach ($proxecto['usuarios'] as $usuario) { ?>
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

                <?php
				if ($imaxes) {
					$Templates->render('aux-gallery.php', array(
						'images' => $imaxes,
						'hide' => 'templates|img/logo-imaxe.png'
					));
				}
				?>

				<div class="tabs tabs-proxecto">
					<ul>
						<?php if ($cadernos) { ?><li><a href="#cadernos"><?php __e('Cadernos'); ?></a></li><?php } ?>
						<li><a href="#descricion"><?php __e('Descrición proxecto'); ?></a></li>
						<?php if (MEU) { ?><li><a href="#comentarios"><?php __e('Comentarios'); ?></a></li><?php } ?>
						<?php if ($ameazas) { ?><li><a href="#ameazas"><?php __e('Ameazas'); ?></a></li><?php } ?>
                        <?php if ($espazos) { ?><li><a href="#espazos"><?php __e('Espazos'); ?></a></li><?php } ?>
                        <?php if ($rotas) { ?><li><a href="#rotas"><?php __e('Rotas'); ?></a></li><?php } ?>
                        <?php if ($iniciativas) { ?><li><a href="#iniciativas"><?php __e('Iniciativas de conservación'); ?></a></li><?php } ?>
                        <?php if ($especies) { ?><li><a href="#especies"><?php __e('Especies'); ?></a></li><?php } ?>
                        <?php if ($avistamentos) { ?><li><a href="#avistamentos"><?php __e('Avistamentos'); ?></a></li><?php } ?>
                        <?php if ($avistamentos) { ?><li><a href="#avistamentos-mapa"><?php __e('Mapa de avistamentos'); ?></a></li><?php } ?>
					</ul>

					<?php if ($cadernos) { ?>
					<section class="cadernos" id="cadernos">
						<div class="ly-11">
							<?php $metade = ceil(count($cadernos) / 2); ?>

							<ul class="listaxe ly-e1">
								<?php
								foreach ($cadernos as $num => $caderno) {
									echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

									$Templates->render('aux-caderno.php', array(
										'proxecto' => $proxecto,
										'caderno' => $caderno
									));
								}
								?>
							</ul>
						</div>
					</section>
					<?php } ?>

					<section id="descricion" class="descricion">
						<?php echo $proxecto['texto']; ?>
					</section>

                    <?php if (MEU) { ?>
					<section id="comentarios">
						<?php
						$Templates->render('aux-comentarios.php', array(
							'comentarios' => $comentarios
						));
						?>
					</section>
                    <?php } ?>

					<?php if ($ameazas) { ?>
					<section id="ameazas">
						<div class="listado-ameazas paxinado content">
							<ul class="listaxe">
								<?php foreach ($ameazas as $ameaza) {
									$Templates->render('aux-ameaza.php', array(
										'ameaza' => $ameaza
									));
								} ?>
							</ul>
						</div>
					</section>
					<?php } ?>

					<?php if ($espazos) { ?>
					<section id="espazos" class="listaxe">
						<div class="ly-11">
							<?php $metade = ceil(count($espazos) / 2); ?>

							<ul class="listaxe ly-e1">
								<?php
								foreach ($espazos as $num => $espazo) {
									echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

									$Templates->render('aux-espazo.php', array(
										'espazo' => $espazo
									));
								}
								?>
							</ul>
						</div>
					</section>
					<?php } ?>

                    <?php if ($rotas) { ?>
                    <section id="rotas" class="listaxe">
                        <div class="ly-11">
                            <?php $metade = ceil(count($rotas) / 2); ?>

                            <ul class="listaxe ly-e1">
                                <?php
                                foreach ($rotas as $num => $rota) {
                                    echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

                                    $Templates->render('aux-rota.php', array(
                                        'rota' => $rota
                                    ));
                                }
                                ?>
                            </ul>
                        </div>
                    </section>
                    <?php } ?>

                    <?php if ($iniciativas) { ?>
                    <section id="iniciativas" class="listaxe">
                        <div class="ly-11">
                            <?php $metade = ceil(count($iniciativas) / 2); ?>

                            <ul class="listaxe ly-e1">
                                <?php
                                foreach ($iniciativas as $num => $iniciativa) {
                                    echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

                                    $Templates->render('aux-iniciativa.php', array(
                                        'iniciativa' => $iniciativa
                                    ));
                                }
                                ?>
                            </ul>
                        </div>
                    </section>
                    <?php } ?>

					<?php if ($especies) { ?>
					<section id="especies">
						<ul class="especies">
							<?php
							foreach ($especies as $especie) {
								$Templates->render('aux-especie.php', array(
									'especie' => $especie
								));
							}
							?>
						</ul>
					</section>
					<?php } ?>

                    <?php if ($avistamentos) { ?>
                    <section id="avistamentos" class="listaxe">
                        <div id="listado-avistamentos" class="listado-paxinado-cliente">
                            <div class="ly-11">
                                <ul class="listaxe ly-e1">
                                    <?php
                                    foreach ($avistamentos as $num => $avistamento) {
                                        if ($num % 6 != 0) {
                                            echo $Html->each('</ul><ul class="listaxe ly-e2">', 3, $num - 1);
                                        }

                                        echo $Html->each('</ul></div><div class="ly-11 hidden"><ul class="listaxe ly-e1">', 6, $num - 1);

                                        $Templates->render('aux-avistamento.php', array(
                                            'avistamento' => $avistamento,
                                            'mapa' => true
                                        ));
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                        $Templates ->render('aux-paxinacion-cliente.php', array(
                            'length' => ceil(count($avistamentos) / 6),
                            'list' => 'listado-avistamentos'
                        ));
                        ?>
                    </section>

                    <section id="avistamentos-mapa">
                        <div class="content-mapa">
                            <div class="map"></div>
                            <div id="mapa-toolbar-top" class="toolbar-mapa">
                                <button id="fullscreen" class="btn fullscreen right" type="button">
                                    <i class="icon-fullscreen"></i>
                                </button>

                                <button id="zoom-plus" type="button" tabindex="-1" class="btn zoom" type="button">
                                    <i class="icon-plus"></i>
                                </button>

                                <button id="zoom-minus" type="button" tabindex="-1" class="btn zoom" type="button">
                                    <i class="icon-minus"></i>
                                </button>

                                <label>
                                    <div class="desplegable w3" id="map-type" data-value="mapa">
                                        <i class="icon-caret-down right"></i> <span><?php __e('Mapa')?></span>
                                        <ul class="hidden" tabindex="-1">
                                            <li data-value="mapa"><?php __e('Mapa'); ?></li>
                                            <li data-value="satelite"><?php __e('Satelite'); ?></li>
                                            <li data-value="relieve"><?php __e('Relieve'); ?></li>
                                        </ul>
                                    </div>
                                </label>

                                <section class="toolbar-options">
                                    <label>
                                        <span><?php __e('Ver etiquetas'); ?></span>
                                        <input type="checkbox" id="toggle-labels"/>
                                    </label>
                                </section>
                            </div>
                        </div>
                    </section>
                    <?php } ?>
				</div>
			</article>
		</section>

        <section class="subcontent ly-e2">
            <?php if ($log) { ?>

			<header>
				<h1><?php __e('Últimas actualizacións'); ?></h1>
			</header>

			<div>
				<ul class="listaxe">
					<?php
					foreach ($logs as $log) {
						$Templates->render('aux-activity-expanded.php', array(
							'log' => $log,
							'ocultar_contido' => true
						));
					}
					?>
				</ul>
			</div>

    		<?php } else if (!MEU && $user) { ?>

            <header>
                <h1><?php __e('Solicitar participación'); ?></h1>
            </header>

            <?php if ($proxecto['usuarios_solicitude']) { ?>

            <p><?php __e('A túa solicitude de participación neste proxecto está sendo tramitada.'); ?></p>

            <?php } else { ?>

            <p><?php __e('Se queres participar neste proxecto, podes solicitar a participación para así poder acceder a todos os contidos dispoñibles.'); ?></p>
            <p><?php __e('O tempo de resposta desta solicitude dependerá dos usuarios administradores do proxecto.'); ?></p>

            <p><a href="?phpcan_action=proxecto-solicitude" class="btn btn-highlight"><?php __e('Solicitar participación'); ?></a></p>

            <?php } ?>

            <?php } ?>
        </section>
	</div>
</section>
