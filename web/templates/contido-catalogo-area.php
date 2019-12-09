<?php defined('ANS') or die(); ?>

<section class="content catalogo-mapa catalogo-area">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Catálogo'); ?></h1>

			<nav>
                <div id="export-btn-group" class="btn-group disabled">
                    <button class="btn" disabled>
                        <i class="icon-share-alt"></i> <?php __e('Exportar a CSV'); ?> <span class="caret"></span>
                    </button>
                    <ul>
                        <li>
                            <a id="exportar-especies" href="?phpcan_exit_mode=csv">
                                <i class="icon-share-alt"></i> <?php __e('Datos das especies'); ?>
                            </a>
                        </li>
                        <li>
                            <a id="exportar-observacions" href="?phpcan_exit_mode=csv&tipo=observacions">
                                <i class="icon-share-alt"></i> <?php __e('Datos das observacions'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
                
				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<?php if ($user) { ?>

						<li>
							<a href="<?php echo path('editar', 'avistamento'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo avistamento'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo path('editar', 'especie'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova especie'); ?>
							</a>
						</li>

						<?php } else { ?>

						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Novo avistamento'); ?>
							</a>
						</li>
						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova especie'); ?>
							</a>
						</li>

						<?php } ?>
					</ul>
				</div>
			</nav>
		</div>
	</header>

	<div class="content wrapper">
		<div class="tabs tabs-page">
			<ul>
				<li>
					<a href="<?php echo path('catalogo', 'mapa'); ?>"><?php echo __('Mapa catálogo'); ?></a>
				</li>
				<li>
					<a href="<?php echo path('catalogo'); ?>"><?php echo __('Especies pestana'); ?></a>
				</li>
                <li>
					<a href="<?php echo path(); ?>" class="selected"><?php echo __('Ferramenta areas'); ?></a>
				</li>
				<li class="pestana-right">
					<a href="<?php echo path('catalogo', 'sen-identificar'); ?>"><i class="icon-pencil"></i> <?php echo __('Sen identificar'); ?></a>
				</li>
			</ul>
		</div>
	</div>

	<section id="avistamentos" class="mapa-avistamentos wrapper" data-offset="-100">
		<div class="content-options filtro-localizacion">
            
            <button type="button" id="btn-pan" class="btn right" disabled>
                <?php __e('Ver no mapa'); ?>
            </button>
            <h2 class="left"><?php __e('Filtrar resultados'); ?></h2>
            
            <label for="territorio"><?php __e('Territorio'); ?></label>
            <?php
            echo $Form->select($territorios, array(
                'variable' => 'territorio',
                'option_value' => 'url',
                'option_text' => 'nome',
                'id' => 'territorio',
                'class' => 'w3 select-territorios',
                'first_option' => '',
                'data-placeholder' => __('Todos'),
                'data-selected' => $Vars->var['territorio'],
                'data-child' => 'select.select-provincias',
                'data-anchor' => 'avistamento-territorio'
            ));
            ?>
            <label for="provincia"><?php __e('Provincia'); ?></label>
            <?php
            echo $Form->select(array(
                'variable' => 'provincia',
                'option_value' => 'url',
                'option_title' => 'nome',
                'id' => 'provincia',
                'class' => 'w3 select-provincias',
                'first_option' => '',
                'data-placeholder' => __('Todas'),
                'data-selected' => $Vars->var['provincia'],
                'data-child' => 'select.select-concellos',
                'data-anchor' => 'avistamento-provincia',
                //'required' => true
            ));
            ?>
            <label for="concello"><?php __e('Concello'); ?></label>
            <?php
            echo $Form->select(array(
                'variable' => 'concello',
                'option_value' => 'url',
                'option_title' => 'nome',
                'id' => 'concello',
                'class' => 'w3 select-concellos',
                'first_option' => '',
                'data-placeholder' => __('Todos'),
                'data-selected' => $Vars->var['concello'],
                'data-anchor' => 'avistamento-concellos',
            ));
            ?>
		</div>

		<div class="wrapper content-mapa">
			
			<div class="mapa"></div>
			
			<div id="map-controls" class="map-controls hidden">

				<!-- Spinner -->
				<span class="cargando hidden" id="map-loading-spinner">
					<i class="icon-spinner icon-spin"></i>&nbsp;<?php __e('Cargando'); ?>
				</span>

				<div id="mapa-toolbar-top">
					<!-- Controles del mapa -->
					<div class="columnas controles-mapa">
						<div class="row row-controles">
							<button id="fullscreen" class="btn fullscreen right">
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

					<!-- opciones extra del mapa -->
					
                    <div class="columnas listado-especies right">
						<div class="row panel panel-hidden row-listado">
							<div class="panel-toggle">
								<span class="panel-view">
									<i class="icon-angle-down"></i> <span><?php __e('Ver listado de especies'); ?></span>
								</span>
								<span class="panel-hide hidden">
									<i class="icon-angle-up"></i> <span><?php __e('Ocultar listado de especies'); ?></span>
								</span>
							</div>

							<div class="panel-content hidden seleccion-especie">
                                <div class="especies-seleccionadas">
                                    <section class="lista-especies-seleccionadas">
                                        <p class="sen-especies ">
                                            <?php __e('Non hai ningun area seleccionada o no hai ninguna especie en el area seleccionada'); ?>
                                        </p>
                                        <ul></ul>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                    
					<div class="columnas extra-mapa-ancho">
						<div class="row">
                            <label>
								<input type="checkbox" name="tipo-limits" id="toggle-limits"/>
								<span><?php __e('División territorial'); ?></span>
							</label>

							<label>
								<input type="checkbox" name="tipo-mapa" id="toggle-labels"/>
								<span><?php __e('Etiquetas'); ?></span>
							</label>
                            
                            <button id="seleccionar-poligono" class="btn">
                                <i class="icon-crop"></i> <?php __e('Seleccionar área'); ?>
                            </button>
						</div>
					</div>
                    
                    <div class="columnas seleccion-especie">
				</div>

				<div id="mapa-toolbar-bottom">

					<div class="row panel panel-hidden panel-reverse row-avistamentos">
						<div class="panel-toggle">
							<span class="panel-view">
								<i class="icon-angle-up"></i> <span><?php __e('Ver filtros e observacións'); ?></span>
							</span>
							<span class="panel-hide hidden">
								<i class="icon-angle-down"></i> <span><?php __e('Ocultar filtros e observacións'); ?></span>
							</span>
						</div>

						<div class="panel-content">
							<form action="<?php echo path('get-avistamentos'); ?>" class="subcontent-filter" method="get">
								<fieldset>
									<label>
										<div class="desplegable w3 disabled" id="filtro-ano" data-name="ano" data-value="">
											<i class="icon-caret-down right"></i> <span><?php __e('Ano de observación')?></span>
											<ul class="hidden" tabindex="-1">
												<li data-value=""><?php __e('Todos'); ?></li>
												<?php foreach(range('2013', date('Y')) as $anho) { ?>
												<li data-value="<?php echo $anho; ?>"><?php echo $anho; ?></li>
												<?php } ?>
											</ul>
										</div>
									</label>

									<label>
										<div class="desplegable w3 disabled" id="filtro-nivel" data-name="ameaza" data-value="">
											<i class="icon-caret-down right"></i> <span><?php __e('Nivel ameaza')?></span>
											<ul class="hidden" tabindex="-1">
												<li data-value=""><?php __e('Todas'); ?></li>
                                                <?php foreach (getNiveisAmeazas('especies') as $indice => $nivel) { ?>
												<li data-value="<?php echo $indice; ?>"><span class="nivel-ameaza n<?php echo $indice; ?>"></span> <?php echo $nivel; ?></li>
                                                <?php } ?>
											</ul>
										</div>
									</label>									

									<label>
										<div class="desplegable w3 disabled" id="filtro-validada" data-name="validada" data-value="">
											<i class="icon-caret-down right"></i> <span><?php __e('Validación')?></span>
											<ul class="hidden" tabindex="-1">
												<li data-value=""><?php __e('Todas'); ?></li>
												<li data-value="1"><?php __e('Validadas'); ?></li>
												<li data-value="0"><?php __e('Sen validar'); ?></li>
											</ul>
										</div>

									</label>

                                    <label>
                                        <div class="desplegable w3 disabled" id="filtro-proteccion" data-name="proteccion" data-value="">
                                            <i class="icon-caret-down right"></i> <span><?php __e('Protección')?></span>
                                            <ul class="hidden" tabindex="-1">
                                                <li data-value=""><?php __e('Todas'); ?></li>
                                                <?php foreach ($proteccions as $proteccion) { ?>
                                                <li data-value="<?= $proteccion['id']; ?>"><?= $proteccion['nome']; ?></li>
                                                <?php } ?>
                                            </ul>
                                        </div>

                                    </label>
								</fieldset>
							</form>

							<div id="container-listaxe-avistamentos">
								<p class="sen-especie">
									<?php __e('Selecciona unha area para ver os seus avistamentos'); ?>
								</p>
								<section class="listaxe-avistamentos hidden" id="listaxe-avistamentos"></section>
							</div>
						</div>
					</div>
				</div>
			</div>

			<section class="info-position-mapa">
				<div class="latlng">
					<span id="map-latlng"></span>
					<strong><?php __e('Lat/Lng'); ?></strong>
				</div>
				<div class="utm">
					<strong><?php __e('UTM'); ?></strong>
					<span id="utm-zone"></span>
					<span id="utm-easting"></span>
					<span id="utm-northing"></span>
				</div>
			</section>
		</div>
	</div>
</section>
<div class="hidden">
    <form id="form-exportacion" method="post" target="iframe-export"></form>
    <iframe id="iframe-export"></iframe>
</div>