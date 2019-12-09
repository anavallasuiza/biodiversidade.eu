<?php defined('ANS') or die(); ?>

<section class="content portada">
	<?php if (MOBILE) { ?>

	<div class="wrapper">
		<div class="preportada-intro">
			<a href="<?php echo path('info', 'aplicacion-mobil'); ?>">
				<figure>
					<?php
					echo $Html->img(array(
						'src' => 'templates|img/app.png',
						'alt' => __('Información sobre a aplicación móbil')
					));
					?>
				</figure>

				<div>
					<h1><?php __e('Estás accedendo á web de <strong>Biodiversidade ameazada</strong> desde un dispositivo móbil, descubre a nosa <span>aplicación móbil</span> para traballo de campo.'); ?></h1>
	                <p><span class="btn btn-highlight"><?php __e('Saber máis'); ?></p>
				</div>
			</a>
		</div>
	</div>

	<?php } else { ?>

	<div class="banner-superior">
		<div class="mapa-portada"></div>

		<div class="content-portada-intro">
			<div class="portada-intro">
				<header>
					<h1><?php echo __('Coñecer, xestionar e implicar'); ?></h1>
					<p><?php echo __('Galiza e Norte de Portugal. A mesma flora e semellantes ameazas. Biodiversidade Vexetal Ameazada Galicia-Norte de Portugal é unha aposta común para a conservación da biodiversidade, socializando o coñecemento e creando ferramentas colectivas sen fronteiras.'); ?></p>
				</header>

				<nav>

					<a href="<?php echo path('catalogo', 'mapa'); ?>" class="btn btn-highlight">
						<?php __e('Ver mapa'); ?>
					</a>

					<?php if ($user) { ?>

					<a href="<?php echo path('editar-grupo', 'avistamento'); ?>" class="btn btn-highlight">
						<i class="icon-plus"></i>
						<?php echo __('Novo avistamento') ?>
					</a>
					<a href="<?php echo path('editar', 'ameaza'); ?>" class="btn btn-highlight">
						<i class="icon-plus"></i>
						<?php echo __('Nova ameaza') ?>
					</a>

					<?php } else { ?>

					<a href="<?php echo path('entrar'); ?>" class="btn btn-highlight modal-ajax">
						<i class="icon-plus"></i>
						<?php echo __('Novo avistamento') ?>
					</a>
					<a href="<?php echo path('entrar'); ?>" class="btn btn-highlight modal-ajax">
						<i class="icon-plus"></i>
						<?php echo __('Nova ameaza') ?>
					</a>

					<?php } ?>
				</nav>
			</div>
		</div>
	</div>
	<?php } ?>

	<div id="mapa-toolbar" class="toolbar-mapa hidden">
		<button id="zoom-plus" class="btn zoom">
			<i class="icon-plus"></i>
		</button>

		<button id="zoom-minus" class="btn zoom">
			<i class="icon-minus"></i>
		</button>
	</div>

	<div class="content wrapper">
		<?php if ($validar) { ?>
		<div class="alert alert-success" style="margin-bottom: 20px">
		    <div class="wrapper">
		        <?php __e('Como editor, tes a posibilidade de validar os seguintes contidos:'); ?>

		        <p>
			        <?php
			        if ($validar['especies']) {
			        	echo ' '.$Html->a(__('%s especies', $validar['especies']), path('perfil').'#especies-validar');
			        }

			        if ($validar['avistamentos']) {
			        	echo $validar['especies'] ? ' |' : '';
			        	echo ' '.$Html->a(__('%s avistamentos', $validar['avistamentos']), path('perfil').'#avistamentos-validar');
			        }

			        if ($validar['rotas']) {
			        	echo ($validar['especies'] || $validar['avistamentos']) ? ' |' : '';
			        	echo ' '.$Html->a(__('%s rotas', $validar['rotas']), path('perfil').'#rotas-validar');
			        }
			        ?>
			    </p>
		    </div>
		</div>
		<?php } ?>

		<?php if ($user && $actividade) { ?>

		<div class="ly-f1">
			<section class="ly-e1">
				<div class="ly-21">
					<section class="ly-e21 subcontent">
						<header>
							<h1><?php echo __('Últimos avistamentos'); ?></h1>
						</header>

						<ul class="listaxe listaxe-2col ly-listaxe_portada" data-equalheight="> li">
							<?php
							foreach ($avistamentos as $num => $avistamento) {
								$Templates->render('aux-avistamento-portada.php', array(
									'avistamento' => $avistamento,
									'mapa' => true,
									'shapes' => $shapes[$avistamento['url']]
								));
							}
							?>
						</ul>

						<a class="btn btn-highlight" href="<?php echo path('avistamentos'); ?>"><?php echo __('Ver todos os avistamentos'); ?></a>
        <!--            BANNER DOAÇÃO-->

                    <div class="banner-donate">
                        <a href="<?php echo path('achegas'); ?>"><?php
                                echo $Html->img(array(
                                'src' => $Html->imgSrc('templates|img/banners-doar/banner-fai-a-tua-achega_'.$Vars->getLanguage().'.gif'),
                                ));
                            ?></a>
                    </div>

					</section>

					<?php if ($ameazas) { ?>
					<section class="ly-e22 subcontent lista-ameazas">
						<header>
							<h1><?php echo __('Últimas ameazas'); ?></h1>
						</header>

						<ul class="listaxe">
							<?php
							foreach ($ameazas as $ameaza) {
								$Templates->render('aux-ameaza.php', array(
									'ameaza' => $ameaza
								));
							}
							?>
						</ul>

						<a class="btn btn-highlight" href="<?php echo path('ameazas'); ?>"><?php echo __('Ver todas as ameazas'); ?></a>
					</section>
					<?php } ?>

                    <?php if ($iniciativas) { ?>
					<section class="ly-e22 subcontent">
						<header>
							<h1><?php echo __('Últimas iniciativas'); ?></h1>
						</header>

						<ul class="listaxe">
							<?php
							foreach ($iniciativas as $iniciativa) {
								$Templates->render('aux-iniciativa.php', array(
									'iniciativa' => $iniciativa
								));
							}
							?>
						</ul>

						<a class="btn btn-highlight" href="<?php echo path('ameazas'); ?>#iniciativas"><?php echo __('Ver todas as iniciativas'); ?></a>
					</section>
					<?php } ?>
				</div>
			</section>

            <?php if ($logs) { ?>
            <div class="ly-e2" style="margin-bottom: 20px;">
                <section class="actividade-portada">
                    <header>
                        <h1><?php echo __('Última actividade'); ?></h1>
                    </header>

                    <ul class="listaxe">
                        <?php
                        foreach ($logs as $log) {
                            $Templates->render('aux-activity-expanded.php', array(
                                'amosar_usuario' => true,
                                'log' => $log
                            ));
                        }
                        ?>
                    </ul>
                </section>
            </div>
            <?php } ?>

			<?php if ($actividade) { ?>
			<div class="ly-e2">
				<section class="actividade-portada">
					<header>
						<h1><?php echo __('Evento nos contidos que vixías') ?></h1>
					</header>

					<ul class="listaxe">
						<?php
						foreach ($actividade as $log) {
							$Templates->render('aux-activity-expanded.php', array(
								'amosar_usuario' => true,
								'log' => $log
							));
						}
						?>
					</ul>

					<a class="btn btn-highlight" href="<?php echo path('perfil'); ?>#actividade"><?php echo __('Ver toda a actividade'); ?></a>
				</section>
			</div>
			<?php } ?>
		</div>
	</div>

	<?php } else { ?>

	<div class="ly-f1">
		<section class="ly-e1 subcontent">
			<header>
				<h1><?php echo __('Últimos avistamentos') ?></h1>
			</header>

			<div class="ly-111_portada" data-equalheight="> ul > li">
				<?php $tercio = floor(count($avistamentos) / 3); ?>

				<ul class="listaxe ly-e1">
					<?php
					foreach (array_slice($avistamentos, 0, $tercio) as $num => $avistamento) {
						$Templates->render('aux-avistamento-portada.php', array(
							'avistamento' => $avistamento,
							'mapa' => true,
							'shapes' => $shapes[$avistamento['url']]
						));
					}
					?>
				</ul>

				<ul class="listaxe ly-e2">
					<?php
					foreach (array_slice($avistamentos, $tercio, $tercio) as $num => $avistamento) {
						$Templates->render('aux-avistamento-portada.php', array(
							'avistamento' => $avistamento,
							'mapa' => true,
							'shapes' => $shapes[$avistamento['url']]
						));
					}
					?>
				</ul>

				<ul class="listaxe ly-e3">
					<?php
					foreach (array_slice($avistamentos, $tercio*2) as $num => $avistamento) {
						$Templates->render('aux-avistamento-portada.php', array(
							'avistamento' => $avistamento,
							'mapa' => true,
							'shapes' => $shapes[$avistamento['url']]
						));
					}
					?>
				</ul>
			</div>
			<a class="btn btn-highlight" href="<?php echo path('avistamentos'); ?>"><?php echo __('Ver todos os avistamentos'); ?></a>

<!--            BANNER DOAÇÃO-->

            <div class="banner-donate">
                <a href="<?php echo path('achegas'); ?>"><?php
                        echo $Html->img(array(
                        'src' => $Html->imgSrc('templates|img/banners-doar/banner-fai-a-tua-achega_'.$Vars->getLanguage().'.gif'),
                        ));
                    ?></a>
            </div>

		</section>

        <?php if ($logs) { ?>
        <div class="ly-e2" style="margin-bottom: 20px;">
            <section class="actividade-portada">
                <header>
                    <h1><?php echo __('Última actividade'); ?></h1>
                </header>

                <ul class="listaxe">
                    <?php
                    foreach ($logs as $log) {
                        $Templates->render('aux-activity-expanded.php', array(
                            'amosar_usuario' => true,
                            'log' => $log
                        ));
                    }
                    ?>
                </ul>
            </section>
        </div>
        <?php } ?>

		<?php if ($ameazas) { ?>
		<section class="ly-e2 subcontent portada lista-ameazas">
			<header>
				<h1><?php echo __('Últimas ameazas'); ?></h1>
			</header>

			<ul class="listaxe">
				<?php
				foreach ($ameazas as $i =>$ameaza) {
					$Templates->render('aux-ameaza.php', array(
						'ameaza' => $ameaza
					));

                    if ($i >= 2) {
                        break;
                    }
				}
				?>
			</ul>

			<a class="btn btn-highlight" href="<?php echo path('ameazas'); ?>"><?php echo __('Ver todas as ameazas'); ?></a>
		</section>
		<?php } ?>

        <?php if ($iniciativas) { ?>
        <section class="ly-e2 subcontent portada">
            <header>
                <h1><?php echo __('Últimas iniciativas'); ?></h1>
            </header>

            <ul class="listaxe">
                <?php
                foreach ($iniciativas as $iniciativa) {
                    $Templates->render('aux-iniciativa.php', array(
                        'iniciativa' => $iniciativa
                    ));
                }
                ?>
            </ul>

            <a class="btn btn-highlight" href="<?php echo path('ameazas'); ?>#iniciativas"><?php echo __('Ver todas as iniciativas'); ?></a>
        </section>
        <?php } ?>
	</div>
	<?php } ?>
</section>

<section class="content portada-extra">
	<div class="wrapper">
		<div class="ly-f1">
			<div class="ly-e1">
				<div class="ly-11">
					<div class="ly-e1">
						<header>
							<h1><?php echo __('Rotas e espazos'); ?></h1>
						</header>

						<article class="rota">
							<?php
							if ($rota['imaxe']) {
								echo $Html->img(array(
									'src' => $rota['imaxe']['imaxe'],
									'alt' => $rota['titulo'],
									'width' => 100,
									'height' => 100,
									'transform' => 'zoomCrop,100,100',
									'class' => 'rota-miniatura'
								));
							}
							?>
							<header>
								<h1>
									<a href="<?php echo path('rota', $rota['url']); ?>"><?php echo $rota['titulo']; ?></a>

									<?php if ($rota['validado']) { ?>
									<span class="estado solucionada"<?php echo $rota['usuarios_validador'] ? (' title="'.__('Validado por %s', $rota['usuarios_validador']['nome']['title']).'"') : ''; ?>><i class="icon-thumbs-up"></i> <?php __e('Validada'); ?></span>
									<?php } else { ?>
									<span class="estado activa"><i class="icon-thumbs-down"></i> <?php __e('Non validada'); ?></span>
									<?php } ?>
								</h1>

								<ul class="rota-informacion">
									<?php if ($rota['territorios']) { ?><li><?php echo $rota['concellos']['nome']['title'].' '.$rota['provincias']['nome']['title'] . ' ' . $espazo['territorios']['nome']; ?></li><?php } ?>
					                <?php if ($rota['lugar']) { ?><li><p><strong><?php echo $rota['lugar']; ?></strong></p></li><?php } ?>
									<?php if ($rota['dificultade']) { ?><li><?php __e('Dificultade'); ?>: <strong><?php echo ucfirst($rota['dificultade']); ?></strong></li><?php } ?>
									<li><?php __e('Distancia'); ?>: <strong><?php echo (intval($rota['distancia'] > 1000) ? number_format($rota['distancia'] / 1000, 1, ',', '') . ' Km': $rota['distancia'] . ' m'); ?></strong></li>
									<?php if ($rota['duracion']) { ?><li><?php __e('Duración'); ?>: <strong><?php echo gmdate('H \h i \m', ($rota['duracion'] * 60)); ?></strong></li><?php } ?>
                                    <?php if ($rota['autor']) { ?><li><?php __e('Autor'); ?>: <strong><a href="<?php echo path('perfil', $rota['autor']['nome']['url']); ?>"><?php echo $rota['autor']['nome']['title'] . ' ' . $rota['autor']['apelido1']; ?></a></strong></li><?php } ?>
									<?php if ($rota['comentarios']) { ?><li><?php echo (count($rota['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($rota['comentarios'])); ?></li><?php } ?>
								</ul>
							</header>
						</article>

						<article class="espazo">
							<?php
							if ($espazo['imaxe']) {
								echo $Html->img(array(
									'src' => $espazo['imaxe']['imaxe'],
									'alt' => $espazo['titulo'],
									'width' => 100,
									'height' => 100,
									'transform' => 'zoomCrop,100,100',
									'class' => 'espazo-miniatura'
								));
							}
							?>
							<header>
								<h1>
									<a href="<?php echo path('espazo', $espazo['url']); ?>"><?php echo $espazo['titulo']; ?></a>

									<?php if ($espazo['validado']) { ?>
									<span class="estado solucionada"><i class="icon-thumbs-up"></i> <?php __e('Validado'); ?></span>
									<?php } else { ?>
									<span class="estado activa"><i class="icon-thumbs-down"></i> <?php __e('Non validado'); ?></span>
									<?php } ?>
								</h1>

								<ul class="espazo-informacion">
									<?php if ($espazo['territorios']) { ?><li><strong><?php echo $espazo['territorios']['nome']; ?></strong></li><?php } ?>
									<?php if ($espazo['espazos_figuras']) { ?><li><?php __e('Figura de protección'); ?>: <strong><?php echo $espazo['espazos_figuras']['nome']; ?></strong></li><?php } ?>
									<?php if ($espazo['espazos_tipos']) { ?><li><?php __e('Tipo'); ?>: <strong><?php echo $espazo['espazos_tipos']['nome']; ?></strong></li><?php } ?>
                                    <?php if ($espazo['usuarios_autor']) { ?><li><?php __e('Autor'); ?>: <strong><a href="<?php echo path('perfil', $espazo['usuarios_autor']['nome']['url']); ?>"><?php echo $espazo['usuarios_autor']['nome']['title'] . ' ' . $espazo['usuarios_autor']['apelido1']; ?></a></strong></li><?php } ?>
									<?php if ($espazo['comentarios']) { ?><li><?php __e('%s comentarios', count($espazo['comentarios'])); ?></li><?php } ?>
								</ul>
							</header>
						</article>
					</div>

					<div class="ly-e2">
						<header>
							<h1><?php echo __('Novas e eventos'); ?></h1>
						</header>

						<?php foreach ($novas as $nova) { ?>
						<article class="nova">
							<header>
								<h1><a href="<?php echo path('nova', $nova['url']); ?>"><?php echo $nova['titulo']; ?></a></h1>
							</header>

							<footer>
								<?php
								echo ucfirst($Html->time($nova['data'], '', 'absolute')); ?>
							</footer>
						</article>
						<?php } ?>

						<?php $Datetime = getDatetimeObject(); ?>

						<?php foreach ($eventos as $evento) { ?>
						<article class="actividade">
							<header>
								<time>
									<span class="dia"><?php echo date('d', strtotime($evento['data'])); ?></span>
									<span class="mes"><?php echo $Datetime->getMonth(date('m', strtotime($evento['data'])), true); ?></span>
								</time>

								<h1><a href="<?php echo path('evento', $evento['url']); ?>"><?php echo $evento['titulo']; ?></a></h1>

								<p class="actividade-lugar">
									<?php echo $evento['lugar']; ?>
								</p>
							</header>
						</article>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="ly-e2">
				<section class="banner">
					<a href="<?php echo path('actividades-didacticas'); ?>#actividades">
						<?php
						echo $Html->img(array(
							'src'=>'templates|img/baner-didactica.jpg',
							'transform' => 'zoomCrop,400,200'
						));
						?>
						<h1><?php echo __('Actividades didácticas'); ?></h1>
						<p><?php echo __('Texto banner actividades didácticas') ?></p>
					</a>
				</section>
			</div>
		</div>
	</div>
</section>