<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Rotas e espazos'); ?></h1>

            <nav>
                <a class="btn" href="?phpcan_exit_mode=csv">
                    <i class="icon-download"></i> <?php __e('Exportar a CSV'); ?>
                </a>

                <a class="btn" href="?phpcan_exit_mode=kml">
                    <i class="icon-download"></i> <?php __e('Descargar como KML'); ?>
                </a>

                <?php if ($user) { ?>

				<a class="btn" href="<?php echo get(array('phpcan_action' => 'vixiar')); ?>">
					<i class="icon-eye-open"></i> <?php echo $espazo['vixiar'] ? __('Deixar de seguir') : __('Vixiar espazo'); ?>
				</a>

                <div class="btn-group">
                    <button class="btn">
                        <i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
                    </button>

                    <ul>
                        <li>
                            <a href="<?php echo path('editar', 'espazo'); ?>">
                                <i class="icon-plus"></i> <?php __e('Novo espazo'); ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo path('editar-grupo', 'avistamento'); ?>">
                                <i class="icon-plus"></i> <?php echo __('Novo avistamento') ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo path('editar', 'ameaza'); ?>">
                                <i class="icon-plus"></i> <?php echo __('Nova ameaza') ?>
                            </a>
                        </li>

                        <?php if ($Acl->check('action', 'espazo-editar')) { ?>
                        <li>
                            <a href="<?php echo path('editar', 'espazo', $espazo['url']); ?>">
                                <i class="icon-pencil"></i> <?php __e('Editar'); ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>

                <?php } ?>
            </nav>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="subcontent ly-e1">
            <a class="btn-link" href="javascript:void(0);" onclick="history.back();">
                <i class="icon-arrow-left"></i>
                <?php echo __('Voltar'); ?>
            </a>

			<article class="espazo espazo-permalink">
				<header>
					<h1><?php echo $espazo['titulo']; ?></h1>
				</header>

				<div class="espazo-intro">
					<?php echo $espazo['texto']; ?>
				</div>

                <div class="content-mapa">
                    <div class="mapa" <?php echo $shapes['markers'] ? 'data-markers="' . $shapes['markers']  . '"': ''; ?> <?php echo $shapes['polygons'] ? 'data-polygons="' . $shapes['polygons'] . '"': ''; ?> <?php echo $shapes['polylines'] ? 'data-polylines="' . $shapes['polylines'] . '"': ''; ?>></div>
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
                                    <li data-value="hybrid"><?php __e('Mapa e satélite'); ?></li>
                                </ul>
                            </div>
                        </label>

                        <section class="toolbar-options">
                            <label>
                                <span><?php __e('Ver etiquetas'); ?></span>
                                <input type="checkbox" id="toggle-labels" checked="checked"/>
                            </label>

                            <label>
                                <span><?php __e('Ver avistamentos'); ?></span>
                                <input type="checkbox" id="toggle-avistamentos" checked="checked"/>
                            </label>

                            <label>
                                <span><?php __e('Filtrar por especie:')?></span>
                                <select id="filtro-especie" class="w3" data-placeholder="<?php __e('Todas'); ?>">
                                    <option selected></option>
                                    <?php foreach($espazo['especies'] as $especie) { ?>
                                    <option value="<?php echo $especie['url']; ?>"><?php echo $especie['nome']; ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                        </section>
                    </div>
                    <div id="infoWindow" class="hidden">
                        <header>
                            <h1></h1>
                        </header>
                        <p></p>
                    </div>
                </div>

                <?php if ($espazo['kml']) { ?>
                <div class="footer-link">
                    <a id="link-kml" href="<?php echo fileWeb('uploads|'.$espazo['kml'], false, true); ?>">
                        <?php __e('Descargar o kml do espazo'); ?>
                    </a>
                </div>
                <?php } ?>

                <div class="tabs tabs-rota">
					<ul>
						<?php if ($espazo['especies']) { ?>
                        <li><a href="#especies"><?php echo __('Especies'); ?></a></li>
                        <?php } ?>
                        <?php if ($avistamentos) { ?>
						<li><a href="#avistamentos"><?php echo __('Avistamentos'); ?></a></li>
                        <?php } ?>
					</ul>

                    <?php if ($espazo['especies']) { ?>
                    <section id="especies" class="listaxe-relacionada especies-relacionadas">
                        <?php foreach($grupos as $grupo) { ?>
                        <h2><?php echo $grupo['nome']; ?></h2>
                        <ul class="rota-especies">
                            <?php foreach($grupo['familias'] as $familia) { ?>
                            <li>
                                <h3><?php echo $familia['nome']; ?></h3>
                                <ul>
                                    <?php foreach($familia['especies'] as $especie) { ?>
                                    <li>
                                        <a href="<?php echo path('especie', $especie['url']); ?>" class="ameazada-nivel-<?php echo $especie['nivel_ameaza']; ?>" title="<?php __e('nivel-ameaza-especies-'.$especie['nivel_ameaza']); ?>">
                                            <i class="nivel-ameaza n<?php echo $especie['nivel_ameaza']; ?>"></i> <?php echo $especie['nome']; ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
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
                    <?php } ?>
                </div>
			</article>

            <?php
            if ($imaxes) {
                $Templates->render('aux-gallery.php', array(
                    'images' => $imaxes,
                    'rel' => 'galeria-espazo'
                ));
            }
            ?>

			<?php
			$Templates->render('aux-comentarios.php', array(
				'comentarios' => $comentarios
			));
			?>
		</section>

		<section class="ly-e2 sidebar-ficha">
            <?php $Templates->render('aux-traducir.php', array(
                'idioma' => $espazo['idioma']
            )); ?>

			<section class="info">
				<header>
					<?php if ($espazo['usuarios_autor']) { ?>
					<p class="autor">
						<?php __e('Creado por'); ?>
						<a href="<?php echo path('perfil', $espazo['usuarios_autor']['nome']['url']); ?>">
							<?php echo $espazo['usuarios_autor']['nome']['title'].' '.$espazo['usuarios_autor']['apelido1']; ?>
						</a>
					</p>
					<?php } ?>

					<?php echo ucfirst($Html->time($espazo['data']) ); ?>
				</header>

                <section class="validacion">
                    <div>
                        <strong><?php __e('Estado'); ?></strong>
                    </div>
                    <span class="estado <?php echo $espazo['validado'] ? 'solucionada' : 'activa' ?>">
                        <?php if ($espazo['validado']) { ?>
                        <i class="icon-thumbs-up"></i> <?php __e('Validado'); ?>
                        <?php } else { ?>
                        <i class="icon-thumbs-down"></i> <?php __e('Non validado'); ?>
                        <?php } ?>
                    </span>
                </section>

				<?php if ($espazo['lugar'] || $espazo['espazos_figuras'] || $espazo['espazos_tipos']) { ?>
				<section>
					<ul class="espazo-informacion">
                        <?php if ($espazo['territorios']) { ?>
						<li>
                            <p><strong><?php echo $espazo['territorios']['nome']; ?></strong></p>
						</li>
						<?php } ?>

						<?php if ($espazo['lugar']) { ?>
						<li>
                            <p><strong><?php echo $espazo['lugar']; ?></strong></p>
						</li>
						<?php } ?>

						<?php if ($espazo['espazos_figuras']) { ?>
						<li>
                            <p><?php __e('Figura de protección'); ?>:</p>
                            <p><strong><?php echo $espazo['espazos_figuras']['nome']; ?></strong></p>
						</li>
						<?php } ?>

						<?php if ($espazo['espazos_tipos']) { ?>
						<li>
                            <p><?php __e('Tipo'); ?>:</p>
                            <p><strong><?php echo $espazo['espazos_tipos']['nome']; ?></strong></p>
						</li>
						<?php } ?>
					</ul>
				</section>
				<?php } ?>

                <section class="lenda">
                    <h1><?php __e('Lenda'); ?></h1>
                    <ul>
                        <?php foreach($tiposPois as $tipo) { ?>
                        <li>
                            <?php
								echo $Html->img(array(
									'src' => $tipo['imaxe'],
                                    'width' => 16,
									'height' => 26,
									'transform' => 'zoomCrop,16,26'
								));

								echo ' <span>' . $tipo['nome'] . '</span>';
								?>
                        </li>
                        <?php } ?>
                    </ul>
                </section>

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
			</section>
		</section>
	</div>
</section>
