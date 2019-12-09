<?php defined('ANS') or die(); ?>

<section class="content catalogo-mapa">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Catálogo'); ?></h1>

			<nav>
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
					<a href="<?php echo path(); ?>" class="selected"><?php echo __('Mapa catálogo'); ?></a>
				</li>
				<li>
					<a href="<?php echo path('catalogo'); ?>"><?php echo __('Especies pestana'); ?></a>
				</li>
                <li>
					<a href="<?php echo path('catalogo', 'areas'); ?>"><?php echo __('Ferramenta areas'); ?></a>
				</li>
				<li class="pestana-right">
					<a href="<?php echo path('catalogo', 'sen-identificar'); ?>"><i class="icon-pencil"></i> <?php echo __('Sen identificar'); ?></a>
				</li>
			</ul>
		</div>
	</div>

	<section id="avistamentos" class="mapa-avistamentos wrapper" data-offset="-100">
		<div class="wrapper content-options">
			
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

						<div class="row opciones-avanzadas">
							<label class="right">
								<input type="checkbox" name="tipo-limits" id="toggle-limits"/>
								<span><?php __e('Ver división territorial'); ?></span>
							</label>

							<label class="left">
								<input type="checkbox" name="tipo-mapa" id="toggle-labels"/>
								<span><?php __e('Ver etiquetas'); ?></span>
							</label>
						</div>

                        <?php if ($Acl->check('action', 'livro-vermello')) { ?>
						<div class="row opciones-avanzadas">
							<h2><?php __e('Livro Vermelho'); ?></h2>

							<a class="btn disabled right" id="export-grid" href="#"><?php echo __('Exportar grid') ?></a>

							<label>
								<input type="checkbox" name="toggle-agl" id="toggle-agl"/>
								<span><?php __e('Ver límites'); ?></span>
							</label>
							
							<label class="label-imaxe-overlay">
								<input type="checkbox" id="toggle-agl-image"/>
								<span><?php __e('Ver imaxe'); ?></span>
							</label>
							
							<label class="label-imaxe-overlay-size">
								<input type="checkbox" id="toggle-agl-image-size" disabled/>
								<span><?php __e('Axustar a imaxe'); ?></span>
							</label>
						</div>
                        <?php } ?>
					</div>

					<!-- opciones extra del mapa -->
					
					<div class="columnas extra-mapa">
                        <!--
						<div class="row">							
                            <label>
                                <input type="radio" name="tipo-grella" class="tipo-grella" value="mixed" checked/>
                                <?php __e('Cuadrícula + puntos'); ?>
                            </label>
                            
                            <div class="ver-grella">
                                <label>
                                    <input type="radio" name="tipo-grella" class="tipo-grella" value="grid" />
                                    <?php __e('Cuadrícula'); ?>
                                </label>
                                <div class="desplegable" id="grid-size" data-value="all">
                                    <i class="icon-caret-down right"></i> <span><?php __e('Todas')?></span>
                                    <ul class="hidden" tabindex="-1">
                                        <li data-value="all"><?php __e('Todas'); ?></li>
                                        <li data-value="10km"><?php __e('10km2'); ?></li>
                                        <li data-value="1km"><?php __e('1km2'); ?></li>
                                    </ul>
                                </div>
                            </div>
                            <label>
                                <input type="radio" name="tipo-grella" class="tipo-grella" value="points" />
                                <?php __e('Puntos'); ?>
                            </label>
							<label>
								<input type="checkbox" id="toggle-grella-completa"/>
								<span><?php __e('Grella completa'); ?></span>
							</label>
						</div>
                        -->
                    
                        <div class="row panel panel-hidden row-ver-como">
                            <div class="panel-toggle">
                                <span class="panel-view">
                                    <i class="icon-angle-down"></i> <span><strong><?php __e('Visualización da información xeográfica'); ?></strong></span>
                                </span>
                                <span class="panel-hide hidden">
                                    <i class="icon-angle-up"></i> <span><strong><?php __e('Visualización da información xeográfica'); ?></strong></span>
                                </span>
                            </div>

                            <div class="panel-content hidden">
                                <div>
                                    <header>
                                        <h2><?php __e('Como se mostra no mapa'); ?></h2>
                                    </header>
                                    <p>
                                        <label>
                                            <input type="radio" name="tipo-grella" class="tipo-grella" value="grid" data-size="10km" checked/>
                                            <?php __e('Todo como cuadrículas de 10km2'); ?>
                                        </label>
                                    </p>
                                    <p>
                                        <label>
                                            <input type="radio" name="tipo-grella" class="tipo-grella" value="grid" data-size="1km" checked/>
                                            <?php __e('So en cuadrículas de 1km2 (puntos e cuadrículas de 1km2)'); ?>
                                        </label>
                                    </p>
                                    <p>
                                        <label>
                                            <input type="radio" name="tipo-grella" class="tipo-grella" value="points" />
                                            <?php __e('So en puntos'); ?>
                                        </label>
                                    </p>
                                    <p>
                                        <label>
                                            <input type="radio" name="tipo-grella" class="tipo-grella" value="mixed" checked/>
                                            <?php __e('Cuadrículas e puntos (información orixinal de pontos e cuadrículas)'); ?>
                                        </label>
                                    </p>
                                </div>
                            </div>
                        </div>
					</div>
					

					<!-- Seleccion de especie -->
					<div class="columnas seleccion-especie">
						<div class="row row-buscador formulario">
							<label>
								<i class="icon-spinner icon-spin cargando"></i>
								<input type="search" id="texto-buscar" name="texto" value="" class="no-appearance" placeholder="<?php __e('Buscar especie'); ?>" autofocus>
							</label>
						</div>

						<div class="row panel panel-hidden row-listado">
							<div class="panel-toggle">
								<span class="panel-view">
									<i class="icon-angle-down"></i> <span><?php __e('Ver opcións e árbore taxonómica'); ?></span>
								</span>
								<span class="panel-hide hidden">
									<i class="icon-angle-up"></i> <span><?php __e('Ocultar opcións e árbore taxonómica'); ?></span>
								</span>
							</div>

							<div class="panel-content hidden">
								<section class="content-tree">
									<div class="formulario">
										<fieldset>
											<p class="formulario-filtrar">
												<input type="checkbox" id="con-avistamentos" name="con-avistamentos" value="1">
												<label for="con-avistamentos"><?php __e('Ver so especies con observacións'); ?></label>
											</p>
                                            <div class="formulario-filtrar">
                                                <div class="desplegable" id="estado-especies" data-value="">
                                                    <i class="icon-caret-down right"></i> <span><?php __e('Validadas e sen validar')?></span>
                                                    <ul class="hidden" tabindex="-1">
                                                        <li data-value=""><?php __e('Validadas e sen validar'); ?></li>
                                                        <li data-value="validadas"><?php __e('So validadas'); ?></li>
                                                        <li data-value="sen-validar"><?php __e('So sen validar'); ?></li>
                                                    </ul>
                                                </div>
											</div>
                                            <p class="formulario-filtrar">
												<input type="checkbox" id="protexidas" name="protexidas" value="1">
												<label for="protexidas"><?php __e('So protexidas'); ?></label>
											</p>
										</fieldset>
									</div>

									<div class="especies-seleccionadas">
										<h2><?php __e('Especies seleccionadas'); ?></h2>

										<section class="lista-especies-seleccionadas">
											<p class="sen-especies <?php echo $especie ? 'hidden': ''; ?>">
												<?php __e('Non hai ningunha especies seleccionada'); ?>
											</p>
											<ul>
												<?php 
												if ($especies) { 
													foreach($especies as $especie) {
												?>
												<li data-sinonimos="<?php echo $especie['sinonimo']; ?>" data-comun="<?php echo $especie['nome_comun']; ?>" class="selected">
													<span class="especie con-avistamentos" data-codigo="<?php echo $especie['url']; ?>" data-name="<?php echo $especie['nome']; ?>" data-marker="<?php echo fileWeb('templates|img/marker-orange.png'); ?>" data-color="#DC8607">
														<i class="right icon-remove"></i>
														<img class="imaxe-especie-seleccionada" src="<?php echo fileWeb('templates|img/marker-orange.png'); ?>"/>
														<?php echo $especie['nome']; ?>
													</span>
												</li>
												<?php 
													}
												} 
												?>
											</ul>
										</section>
									</div>

									<ul class="tree">
										<?php foreach ($grupos as $grupo) {?>
										<li>
											<span class="grupo request <?php echo $grupo['avistamentos'] ? 'con-avistamientos' : ''; ?>" data-codigo="<?php echo $grupo['url']; ?>" data-url="<?php echo path('get-listado-clases'); ?>">
												<i class="icon-caret-right"></i> <?php echo $grupo['nome']; ?>
											</span>
											<ul></ul>
										</li>
										<?php } ?>
									</ul>
								</section>
							</div>
						</div>
					</div>
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
								</fieldset>
							</form>

							<div id="container-listaxe-avistamentos">
								<p class="sen-especie">
									<?php __e('Selecciona unha especie para ver os seus avistamentos'); ?>
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
	</section>
</section>