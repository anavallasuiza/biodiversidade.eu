<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Ameazas e iniciativas de conservación'); ?></h1>

			<nav>
                <?php if ($user) { ?>
				<a class="btn" href="<?php echo get(array('phpcan_action' => 'vixiar')); ?>">
                    <i class="icon-eye-open"></i> <?php echo $ameaza['vixiar'] ? __('Deixar de seguir') : __('Vixiar ameaza'); ?>
                </a>
                <?php } ?>

                <?php if (empty($ameaza['notificada'])) { ?>
                <a href="<?php echo path('ameaza', $ameaza['url'], 'notificar'); ?>" class="btn modal-ajax">
                    <i class="icon-bullhorn"></i> <?php __e('Notificar ás autoridades'); ?>
                </a>
                <?php } ?>

                <?php if ($user) { ?>
                <div class="btn-group">
                    <button class="btn">
                        <i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
                    </button>

                    <ul>
                        <li>
            				<?php if ($Acl->check('action', 'ameaza-editar')) { ?>
            				<a href="<?php echo path('editar', 'ameaza', $ameaza['url']); ?>">
            					<i class="icon-pencil"></i> <?php __e('Editar ameaza'); ?>
            				</a>
            				<?php } ?>
                        </li>

                        <li>
            				<a href="<?php echo path('editar', 'ameaza'); ?>">
            					<i class="icon-plus"></i> <?php __e('Nova ameaza') ?>
            				</a>
                        </li>
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

			<article class="ameaza ameaza-permalink" data-markers="<?php echo $shapes['markers']; ?>"  data-polygons="<?php echo $shapes['polygons']; ?>" data-polylines="<?php echo $shapes['polylines']; ?>">
				<header>
					<h1>
						<?php echo $ameaza['titulo']; ?>

						<?php if ($ameaza['estado']) { ?>
						<span class="estado activa"><i class="icon-refresh"></i> <?php __e('Activa'); ?></span>
						<?php } else { ?>
						<span class="estado solucionada"><i class="icon-refresh"></i> <?php __e('Desactiva'); ?></span>
						<?php } ?>
					</h1>
				</header>
                
                <ul class="tipo-ameaza">
                    <li>
                        <strong><?php __e('Tipo de ameaza'); ?></strong>
                        <?php echo $Html->aList($ameaza['ameazas_tipos'], 'nome', 'id', path('ameazas').'?ameaza_tipo='); ?>
                    </li>

                    <?php if ($ameaza['nivel']) { ?>
                    <li>
                        <strong><?php __e('Nivel de ameaza'); ?></strong>
                        <a href="<?php echo path('ameazas') . get('nivel', $ameaza['nivel']); ?>">
                            <?php __e('nivel-ameaza-ameazas-'.$ameaza['nivel']); ?>
                        </a>
                    </li>
                    <?php } ?>
                    
                    <?php if ($ameaza['data']) { ?>
                    <li>
                        <strong><?php __e('Data'); ?></strong>
                        <?php echo $Html->time($ameaza['data']); ?>
                    </li>
                    <?php } ?>
                    
                    <?php if ($zonas['nome']) { ?>
                    <li>
                        <strong><?php __e('Lugar'); ?></strong>
                        <?php $Templates->render('aux-ameaza-zona.php', array('zonas' => $zonas));?>
                    </li>
                    <?php } ?>
                </ul>
                
                <?php if ($user) { ?>
                <section class="estado-ameaza">
                    <form action="<?php echo path(); ?>" class="formulario-pisos" method="post">
                        <fieldset>
                            <?php if ($estado['usuarios_autor']['id'] === $user['id']) { ?>

                            <?php __e('Marcaches esta ameaza como %s', __($estado['action'])); ?>

                            <?php } else { ?>

                            <div class="action">
                                <h2><?php echo __('Coñeces o estado desta ameaza?'); ?></h2>

                                <button type="submit" name="phpcan_action" value="ameaza-estado" class="btn">
                                    <?php echo $ameaza['estado'] ? __('Esta ameaza xa está desactivada') : __('Esta ameaza volve a estar activa'); ?>
                                </button>
                            </div>

                            <?php if ($estado) { ?>
                            <p class="estado-b">
                                <?php __e('<a href="%s">%s</a> marcou esta ameaza como %s', path('perfil', $estado['usuarios_autor']['nome']['url']), $estado['usuarios_autor']['nome']['title'].' '.$estado['usuarios_autor']['apelido1'], $estado['action']); ?>
                            </p>
                            <?php } ?>

                            <?php }?>
                        </fieldset>
                    </form>
                </section>
                <?php } ?>

				<div class="ameaza-intro">
					<?php echo $ameaza['texto']; ?>
				</div>

				<?php
				if ($imaxes) {
					$Templates->render('aux-gallery.php', array(
						'images' => $imaxes,
						'hide' => 'templates|img/logo-imaxe.png'
					));
				}
				?>
				
				<?php if ($ameaza['especies']) { ?>
				<section class="listaxe-relacionada">
					<header>
						<h1><?php __e('Especies ameazadas'); ?>:</h1>
					</header>

					<ul class="ameaza-especies">
						<?php $especies = array_chunk($ameaza['especies'], ceil(count($ameaza['especies']) / 2)); ?>
						<li>
							<ul>
								<?php foreach ($especies[0] as $especie) { ?>
								<li>
                                    <a href="<?php echo path('especie', $especie['url']); ?>">
                                        <?php echo $especie['nome']; ?>
                                    </a>
                                </li>
								<?php } ?>
							</ul>
						</li>

						<?php if (count($especies[1])) { ?>
						<li>
							<ul>
								<?php foreach ($especies[1] as $especie) { ?>
								<li>
                                    <a href="<?php echo path('especie', $especie['url']); ?>">
                                        <?php echo $especie['nome']; ?>
                                    </a>
                                </li>
								<?php } ?>
							</ul>
						</li>
						<?php } ?>
					</ul>
				</section>
				<?php } ?>

                <section id="avistamentos" class="hidden"></section>
			</article>

			<?php $Templates->render('aux-comentarios.php', array(
				'comentarios' => $comentarios
			)); ?>
		</section>

		<section class="subcontent ly-e2 sidebar-ameaza sidebar-ficha">
            <?php $Templates->render('aux-traducir.php', array(
                'idioma' => $ameaza['idioma']
            )); ?>

            <section class="info">
				<header>
                    <p class="autor">
						<strong><?php __e('Creado por'); ?></strong>
                        <a href="<?php echo path('perfil', $ameaza['usuarios_autor']['nome']['url']); ?>">
                            <?php echo $ameaza['usuarios_autor']['nome']['title'].' '.$ameaza['usuarios_autor']['apelido1']; ?>
                        </a>
					</p>
                    <?php if (strtotime($ameaza['data_alta']) > 0) { ?>
				    <p class="data">
                        <strong><?php __e('Data de alta'); ?></strong> <?php echo $Html->time($ameaza['data_alta']); ?>
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
            </section>

			<div class="content-mapa">
				<div class="mapa small-map"></div>
			</div>

            <div id="mapa-toolbar-top" class="toolbar-mapa">                            
                <button id="fullscreen" class="btn fullscreen right">
                    <i class="icon-fullscreen"></i>
                </button>
                
                <button id="zoom-plus" type="button" tabindex="-1" class="btn zoom">
                    <i class="icon-plus"></i>
                </button>
                
                <button id="zoom-minus" type="button" tabindex="-1" class="btn zoom">
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

            <?php if ($ameaza['kml']) { ?>
            <div class="footer-link">
                <a id="link-kml" href="<?php echo fileWeb('uploads|'.$ameaza['kml'], false, true); ?>">
                    <?php __e('Descargar o kml da ameaza'); ?>
                </a>
            </div>
            <?php } ?>
		</section>
	</div>
</section>
