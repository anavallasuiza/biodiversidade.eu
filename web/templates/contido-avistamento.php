<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Avistamentos'); ?></h1>

			<?php if ($user) { ?>
            <nav>
				<a class="btn" href="<?php echo get(array('phpcan_action' => 'vixiar')); ?>">
                    <i class="icon-eye-open"></i> <?php echo $avistamento['vixiar'] ? __('Deixar de seguir') : __('Vixiar esta observación'); ?>
                </a>

                <div class="btn-group">
                    <button class="btn">
                        <i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
                    </button>

                    <ul>
                        <?php if ($avistamento['especies']) { ?>

                        <li>
            				<a href="<?php
                            echo path('editar', 'avistamento').get(array(
                                'especie' => $avistamento['especies']['url'],
                                'grupo' => $avistamento['especies']['grupos']['url']
                            ));
                            ?>">
            					<i class="icon-plus"></i> <?php __e('Novo avistamento desta especie'); ?>
            				</a>
                        </li>

                        <li>
                            <a href="<?php echo path('editar-grupo', 'avistamento'); ?>">
                                <i class="icon-plus"></i> <?php __e('Novo avistamento doutra especie'); ?>
                            </a>
                        </li>

                        <?php } else { ?>

                        <li>
                            <a href="<?php echo path('editar-grupo', 'avistamento'); ?>">
                                <i class="icon-plus"></i> <?php __e('Novo avistamento'); ?>
                            </a>
                        </li>

                        <?php } ?>

                        <?php if ($Acl->check('action', 'avistamento-editar')) { ?>
                        <li>
                            <a href="<?php echo path('editar', 'avistamento', $avistamento['url']); ?>">
                                <i class="icon-pencil"></i> <?php __e('Editar avistamento'); ?>
                            </a>
                        </li>
                        <?php } ?>

                        <?php if ($Acl->check('action', 'avistamento-validar') && !$avistamento['validado']) { ?>
                        <li>
                            <a href="<?php echo path('avistamento', $avistamento['url']) . get(array('phpcan_action' => 'avistamento-validar', 'avistamentos[id][]' => $avistamento['id'])); ?>">
                                <i class="icon-ok-sign"></i> <?php __e('Validar'); ?>
                            </a>
                        </li>
                        <?php } ?>

                        <li>
                            <a href="<?php echo path('editar', 'ameaza').get('avistamento', $avistamento['url']); ?>">
                                <i class="icon-plus"></i> <?php __e('Crear ameaza'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
			</nav>
			<?php } ?>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="ly-e1">
            <a class="btn-link" href="javascript:void(0);" onclick="history.back();">
                <i class="icon-arrow-left"></i>
                <?php echo __('Voltar'); ?>
            </a>

			<article class="especie-avistamento especie-avistamento-permalink">
				<header>
					<h1>
						<?php if ($avistamento['especies']['nome']) { ?>
						<span><?php echo $avistamento['especies']['nome']; ?></span>
						<?php } else { ?>
						<span><?php echo $avistamento['posible'] ? __('Posible %s', $avistamento['posible']) : __('Sen identificar'); ?></span>
						<?php } ?>

						<?php if ($avistamento['validado']) { ?>
						<span class="estado solucionada"<?php echo $avistamento['usuarios_validador'] ? (' title="'.__('Validado por %s', $avistamento['usuarios_validador']['nome']['title']).'"') : ''; ?>><i class="icon-thumbs-up"></i> <?php __e('Validada'); ?></span>
						<?php } else { ?>
						<span class="estado activa"><i class="icon-thumbs-down"></i> <?php __e('Non validada'); ?></span>
						<?php } ?>
					</h1>

					<h2><?php echo $avistamento['especies']['nome_comun']; ?></h2>

                    <?php if ($avistamento['especies']['nome']) { ?>
                    <p><a href="<?php echo path('especie', $avistamento['especies']['url']); ?>"><?php __e('Ver a especie'); ?></a></p>
                    <?php } ?>
				</header>

				<?php
				if ($imaxes) {
					$Templates->render('aux-gallery.php', array(
						'images' => $imaxes,
						'hide' => 'templates|img/logo-imaxe.png'
					));
				}
				?>

                <?php if ($avistamento['territorios'] || $avistamento['puntos']) { ?>
				<section class="datos-ficha">
                    <?php if ($avistamento['territorios']) { ?>
					<h1><?php __e('Localización'); ?></h1>

					<ul class="avistamento-informacion">
						<?php if ($avistamento['territorios']) { ?>
						<li><strong><?php __e('Territorio'); ?>:</strong> <?php echo $avistamento['territorios']['nome']; ?></li>
						<?php } ?>

						<?php if ($avistamento['provincias']) { ?>
						<li><strong><?php __e('Provincia'); ?>:</strong> <?php echo $avistamento['provincias']['nome']['title']; ?></li>
						<?php } ?>

						<?php if ($avistamento['concellos']) { ?>
						<li><strong><?php __e('Concello'); ?>:</strong> <?php echo $avistamento['concellos']['nome']['title']; ?></li>
						<?php } ?>

						<?php if ($avistamento['localidade']) { ?>
						<li><strong><?php __e('Localidade'); ?>:</strong> <?php echo $avistamento['localidade']; ?></li>
						<?php } ?>

						<?php if ($avistamento['nome_zona']) { ?>
						<li><strong><?php __e('Nome da zona'); ?>:</strong> <?php echo $avistamento['nome_zona']; ?></li>
						<?php } ?>
					</ul>
                    <?php } ?>

                    <?php if ($avistamento['puntos']) { ?>
                    <h1><?php __e('Xeolocalización'); ?></h1>
                    <ul class="listado-puntos">
                        <?php foreach($avistamento['puntos'] as $i => $punto) { ?>
						<li>
                            <span>
                                <?php
                                if ($punto['tipo'] === 'mgrs') {
                                    $texto = strtoupper($punto['datum']) . ' - ' . $punto['mgrs'];
                                } else if ($punto['tipo'] === 'utm') {
                                    $texto = strtoupper($punto['datum']) . ' - ' . $punto['utm_fuso'] . ' ' . $punto['utm_x'] . ' ' . $punto['utm_y'];
                                } else  {
                                    $texto = $punto['latitude'] . ' ' . $punto['lonxitude'];
                                }

                                echo $texto;
                                ?>
                            </span>
						</li>
                        <?php
                        if ($i > 300) {
                        ?>
                        <li>
                            <span><?php __e('... e %s máis.', count($avistamento['puntos']) - ($i + 1)); ?></span>
                        </li>
                        <?php
                            break;
                        }
                        ?>

                        <?php } ?>
                    </ul>
				    <?php } ?>
				</section>
                <?php } ?>

				<?php if ($acompanhantes) { ?>
				<section id="especies" class="datos-ficha">
					<h1><?php __e('Especies acompañantes'); ?></h1>

					<section class="listaxe-relacionada">
						<ul class="avistamentos-especies">
							<?php foreach (array_chunk($acompanhantes, ceil(count($acompanhantes) / 2)) as $columna) { ?>
							<li>
								<ul>
									<?php foreach ($columna as $fila) { ?>
									<li><a href="<?php echo path('especie', $fila['url']); ?>"><?php echo $fila['nome']; ?></a></li>
									<?php } ?>
								</ul>
							</li>
							<?php } ?>
						</ul>
					</section>
				</section>
				<?php } ?>

				<section class="datos-ficha">
					<h1><?php __e('Observacións'); ?></h1>

					<div class="avistamento-intro">
						<?php echo $avistamento['observacions']; ?>
					</div>

					<ul class="avistamento-informacion">
						<?php if ($avistamento['referencias_tipos']) { ?>
						<li><strong><?php __e('Fonte de datos'); ?>:</strong> <?php echo(join(', ', arrayKeyValues($avistamento['referencias_tipos'], 'nome'))); ?></li>
						<?php } ?>

						<?php if ($avistamento['referencia']) { ?>
						<li><strong><?php __e('Referencia'); ?>:</strong> <?php echo $avistamento['referencia']; ?></li>
						<?php } ?>

						<?php if ($avistamento['outros_observadores']) { ?>
						<li><strong><?php __e('Outros observadores'); ?>:</strong> <?php echo $avistamento['outros_observadores']; ?></li>
						<?php } ?>

						<?php if (strtotime($avistamento['data_observacion']) > 0) { ?>
						<li><strong><?php __e('Data da observación'); ?>:</strong> <?php echo $Html->time($avistamento['data_observacion']); ?></li>
						<?php } ?>
					</ul>
				</section>

				<section class="datos-ficha">
					<h1><a href="#fields-habitat" class="expand">+ <?php __e('Hábitat'); ?></a></h1>

					<div id="fields-habitat" class="hidden">
						<ul class="avistamento-informacion">
							<?php if ($avistamento['habitats_tipos']) { ?>
							<li><strong><?php __e('Tipo de hábitat'); ?>:</strong> <?php __e($avistamento['habitats_tipos']['nome']); ?></li>
							<?php } ?>

							<?php if ($avistamento['estado_conservacion']) { ?>
							<li><strong><?php __e('Estado de conservación'); ?>:</strong> <?php __e($avistamento['estado_conservacion']); ?></li>
							<?php } ?>

							<?php if ($avistamento['abundancia']) { ?>
							<li><strong><?php __e('Abundancia da especie'); ?>:</strong> <?php __e($avistamento['abundancia']); ?></li>
							<?php } ?>

							<?php if ($avistamento['distribucion']) { ?>
							<li><strong><?php __e('Distribución da especie'); ?>:</strong> <?php __e($avistamento['distribucion']); ?></li>
							<?php } ?>

							<?php if ($avistamento['fenoloxia_individuos']) { ?>
							<li><strong><?php __e('Fenoloxía do individuos'); ?>:</strong> <?php __e($avistamento['fenoloxia_individuos']); ?></li>
							<?php } ?>

							<?php if ($avistamento['substrato_xeoloxico']) { ?>
							<li><strong><?php __e('Sustrato xeolóxico'); ?>:</strong> <?php __e($avistamento['substrato_xeoloxico']); ?></li>
							<?php } ?>

							<?php if ($avistamento['uso_solo']) { ?>
							<li><strong><?php __e('Uso do solo'); ?>:</strong> <?php __e($avistamento['uso_solo']); ?></li>
							<?php } ?>

							<li><strong><?php __e('Solo actualmente activo'); ?>:</strong> <?php echo $avistamento['uso_activo'] ? __('Sí') : __('Non'); ?></li>

							<?php if ($avistamento['xestion_ambiental']) { ?>
							<li><strong><?php __e('Xestión ambiental'); ?>:</strong> <?php __e($avistamento['xestion_ambiental']); ?></li>
							<?php } ?>

							<?php if ($avistamento['observacions_conservacion']) { ?>
							<li><strong><?php __e('Observacións e recomendacións de conservación'); ?>:</strong> <?php echo $avistamento['observacions_conservacion']; ?></li>
							<?php } ?>
						</ul>
					</div>
				</section>

                <?php if ($avanzadoXermoplasma || $avanzadoCoroloxia) { ?>
				<section class="datos-ficha">
					<h1><a href="#fields-avanzadas" class="expand">+ <?php __e('Opcións avanzadas'); ?></a></h1>

					<div id="fields-avanzadas" class="hidden">
						<div class="tabs">
							<ul>
                                <?php if ($avanzadoXermoplasma) { ?>
								<li><a href="#xermoplasma"><?php __e('Xermoplasma'); ?></a></li>
                                <?php } ?>
                                <?php if ($avanzadoCoroloxia) { ?>
								<li><a href="#coroloxia"><?php __e('Coroloxía'); ?></a></li>
                                <?php } ?>
							</ul>

                            <?php if ($avanzadoXermoplasma) { ?>
							<section id="xermoplasma">
								<?php include ($Templates->file('aux-avistamento-xermoplasma.php')); ?>
							</section>
                            <?php } ?>

                            <?php if ($avanzadoCoroloxia) { ?>
							<section id="coroloxia">
								<?php include ($Templates->file('aux-avistamento-coroloxia.php')); ?>
							</section>
                            <?php } ?>
						</div>
					</div>
				</section>
                <?php } ?>
			</article>

            <?php
            $Templates->render('aux-comentarios.php', array(
                'comentarios' => $comentarios
            ));
            ?>
		</section>

		<section class="ly-e2 sidebar-ficha">
            <div class="social-share">
                <a href="<?php echo shareTwitter($avistamento['especies']['nome']); ?>" class="social-share-twitter" rel="nofollow" target="_blank"></a>
                <a href="<?php echo shareFacebook(); ?>" class="social-share-facebook" rel="nofollow" target="_blank"></a>
            </div>

			<section class="info">
				<header>
					<?php if ($autor) { ?>
					<p class="autor">
						<strong><?php __e('Creado por'); ?></strong>
						<a href="<?php echo path('perfil', $autor['nome']['url']); ?>"><?php echo $autor['nome']['title'].' '.$autor['apelido1']; ?></a>
					</p>
					<?php } ?>

                    <?php if ($avistamento['validador']) { ?>
                    <p class="autor">
						<strong><?php __e('Validado por'); ?></strong>
                        <a href="<?php echo path('perfil', $avistamento['validador-url']); ?>"><?php echo $avistamento['validador']; ?></a>
                    </p>
                    <?php } ?>

                    <p class="autor">
                        <strong><?php __e('Engadido o'); ?></strong>
                        <?php echo $Html->time($avistamento['data_alta'], '', 'absolute'); ?>
                    </p>

					<?php if ($avistamento['outros_observadores']) { ?>
					<p class="autor">
						<strong><?php __e('Obervadores'); ?>:</strong>
						<?php echo $avistamento['outros_observadores']; ?>
					</p>
					<?php } ?>

					<?php if (strtotime($avistamento['data_observacion']) > 0) { ?>
					<p class="data">
						<strong><?php __e('Data do avistamento'); ?>:</strong>
						<?php echo $Html->time($avistamento['data_observacion']); ?>
					</p>
					<?php } ?>

				</header>

                <?php if ($seguidores) { ?>
                <section class="membros">
                    <h1><?php __e('Seguida por'); ?></h1>

                    <ul>
                        <?php foreach ($seguidores as $seguidor) { ?>
                        <li>
                            <a href="<?php echo path('perfil', $seguidor['nome']['url']); ?>">
                                <?php
                                echo $Html->img(array(
                                    'src' => $seguidor['avatar'],
                                    'width' => 30,
                                    'height' => 30,
                                    'transform' => 'zoomCrop,30,30'
                                ));

                                echo ' ' . $seguidor['nome']['title'].' '.$seguidor['apelido1'];
                                ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </section>
                <?php } ?>

				<?php if ($avistamento['puntos'] && $avistamento['puntos']['arquivo']) { ?>
				<section class="ligazons">
					<h1><?php __e('Máis información'); ?></h1>

					<ul>
						<li><a href="<?php echo fileWeb('uploads|'.$avistamento['puntos']['arquivo']); ?>"><?php __e('Ver arquivo cartográfico'); ?></a></li>
					</ul>
				</section>
				<?php } ?>
			</section>

			<?php if ($puntos) { ?>
			<div id="mini-mapa-container">
				<section id="mini-mapa" class="mini-mapa">
                    <div class="content-mapa">
                        <div class="mapa" data-points="<?php echo $puntos; ?>"  data-shapes="<?php echo $shapes; ?>"  data-centroides1="<?php echo $centroides1; ?>"  data-centroides10="<?php echo $centroides10; ?>"></div>
                        <div class="toolbar hidden">
                            <div id="mapa-toolbar-top" class="toolbar-mapa">
                                <button id="fullscreen" class="btn fullscreen right" type="button">
                                    <i class="icon-fullscreen"></i>
                                </button>

                                <button id="zoom-plus" class="btn zoom">
                                    <i class="icon-plus"></i>
                                </button>

                                <button id="zoom-minus" class="btn zoom">
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
                            </div>
                        </div>
                    </div>
				</section>
			</div>
			<?php } ?>

            <div class="banner-donate">
                <a href="<?php echo path('achegas'); ?>"><?php
                        echo $Html->img(array(
                        'src' => $Html->imgSrc('templates|img/banners-doar/banner-fai-a-tua-achega_'.$Vars->getLanguage().'.gif'),
                        ));
                    ?></a>
            </div>
		</section>
	</div>
</section>
