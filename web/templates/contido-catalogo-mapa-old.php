<?php defined('ANS') or die(); ?>

<section class="content catalogo-mapa">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Catálogo'); ?></h1>
			<nav>
				<?php if ($user) { ?>
				<div class="btn-group">
					<button class="btn"><i class="icon-plus"></i>
						<?php __e('Crear'); ?>
						<span class="caret"></span>
					</button>
					<ul>
						<li>
							<a href="<?php echo path('editar-grupo', 'avistamento'); ?>">
								<?php __e('Novo avistamento'); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo path('editar', 'especie'); ?>">
								<?php __e('Nova especie'); ?>
							</a>
						</li>
					</ul>
				</div>

				<?php } else { ?>

				<div class="btn-group">
					<button class="btn"><i class="icon-plus"></i>
						<?php __e('Crear'); ?>
						<span class="caret"></span>
					</button>
					<ul>
						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<?php __e('Novo avistamento'); ?>
							</a>
						</li>
						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<?php __e('Nova especie'); ?>
							</a>
						</li>
					</ul>
				</div>
				<?php } ?>
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
				<li class="pestana-right">
					<a href="<?php echo path('catalogo', 'sen-identificar'); ?>"><i class="icon-pencil"></i> <?php echo __('Sen identificar'); ?></a>
				</li>
			</ul>

			<section id="avistamentos" class="ly-1f">
				<section class="subcontent ly-e1">
					<div class="formulario">
						<fieldset>
							<p class="formulario-filtrar">
								<label for="texto-familia"><?php __e('Filtrar'); ?></label>
								<input type="search" id="texto-familia" name="texto" value="" class="no-appearance" placeholder="<?php __('Escribe al menos 3 caracteres'); ?>">
							</p>
							<p class="formulario-filtrar">
								<input type="checkbox" id="con-avistamentos" name="con-avistamentos" value="1">
								<label for="con-avistamentos"><?php __e('Familias con avistamentos'); ?></label>
							</p>
						</fieldset>
					</div>

					<ul class="tree">
						<?php foreach ($reinos as $reino) { ?>
						<li>
							<span class="reino request <?php echo $reino['avistamentos'] ? 'con-avistamientos' : ''; ?>" data-codigo="<?php echo $reino['url']; ?>" data-url="<?php echo path('get-listado-clases'); ?>">
								<i class="icon-caret-right"></i> <?php echo $reino['nome']; ?>
							</span>
							<ul></ul>
						</li>
						<?php } ?>
					</ul>
				</section>

				<div class="ly-e2">
					<section class="subcontent">
						<header>
							<form action="<?php echo path('get-avistamentos'); ?>" class="subcontent-filter" method="get">
								<fieldset>	
									<label>
										<?php
										echo ' '.$Form->select(array(
											'options' => $territorios,
											'variable' => 'territorios',
											'option_value' => 'id',
											'option_text' => 'nome',
											'class' => 'w3 select-territorios',
											'first_option' => '',
											'data-placeholder' => __('Filtrar por territorio')
										));
										?>
									</label>
									<label>
										<?php
										echo ' '.$Form->select(array(
											'options' => $provincias,
											'variable' => 'provincias',
											'class' => 'w3 select-provincias',
											'first_option' => '',
											'data-placeholder' => __('Filtrar por provincia')
										));
										?>
									</label>				

									<label>
										<?php
										echo ' '.$Form->select(array(
											'options' => $concellos,
											'variable' => 'concellos',
											'class' => 'w3 select-concellos',
											'first_option' => '',
											'data-placeholder' => __('Filtrar por concello')
										));
										?>
									</label>

									<label>
										<?php
										echo ' '.$Form->select(array(
											'options' => range('2013', date('Y')),
											'variable' => 'ano',
											'class' => 'w3',
											'first_option' => '',
											'data-placeholder' => __('Filtrar por ano')
										));
										?>
									</label>

									<label>
										<?php
										echo ' '.$Form->select(array(
											'options' => range(1, 3),
											'option_text_as_value' => true,
											'variable' => 'ameaza',
											'class' => 'w4 nivel-ameaza',
											'first_option' => '',
											'data-placeholder' => __('Filtrar por nivel de ameaza')

										));
										?>
									</label>									

									<label>
										<?php
										echo ' '.$Form->select(array(
											'options' => array(
												'' => __('Todas'),
												'1' => __('Só validadas'),
												'0' => __('Sen validar')
											),
											'variable' => 'validada',
											'class' => 'w3',
											'first_option' => '',
											'data-placeholder' => __('Filtrar por validación')

										));
										?>
									</label>

										<button type="submit" class="btn btn-highlight btn-filtro">
											<i class="icon-filter"></i> <?php __e('Filtrar'); ?>
										</button>
								</fieldset>
							</form>
						</header>

						<section class="especies-seleccionadas">
							<ul>

							</ul>
						</section>

						<div class="content-mapa">
							<div class="mapa"></div>
							<div id="mapa-toolbar" class="toolbar-mapa">

								<span class="cargando" id="map-loading-spinner">
									<i class="icon-spinner icon-spin"></i>&nbsp;<?php __e('Cargando'); ?>
								</span>

								<button id="zoom-plus" class="btn zoom">
									<i class="icon-plus"></i>
								</button>

								<button id="zoom-minus" class="btn zoom">
									<i class="icon-minus"></i>
								</button>

								<label>
									<select class="w2" id="map-type">
										<option value="mapa" selected>
											<?php __e('Mapa');?>
										</option>
										<option value="satelite">
											<?php __e('Satelite');?>
										</option>
										<option value="relieve">
											<?php __e('Relieve');?>
										</option>
									</select>
								</label>

								<label>
									<select class="w2" id="datavis-type">
										<option value="puntos">
											<?php __e('Puntos'); ?>
										</option>
										<option value="grid">
											<?php __e('Grella UTM (WGS84)'); ?>
										</option>
									</select>
								</label>
								<section class="toolbar-options">
									<label>
										<span><?php __e('Ver etiquetas'); ?></span>
										<input type="checkbox" name="tipo-mapa" id="toggle-labels"/>
									</label>

									<label>
										<span><?php __e('Ver Livro Vermelho Flora'); ?></span>
										<input type="checkbox" name="toggle-agl" id="toggle-agl"/>
									</label>
								</section>


							</div>

							

							<section class="info-position-mapa">
								<a class="btn right disabled" id="export-grid" href="#"><?php echo __('Exportar grid') ?></a>

								<p>
									<strong><?php __e('Lat/Lng:'); ?></strong>
									<span id="map-latlng"></span>
								</p>
								<p>
									<strong><?php __e('UTM:'); ?></strong>
									<span id="utm-zone"></span>
									<span id="utm-easting"></span>
									<span id="utm-northing"></span>
								</p>
							</section>
						</div>

						<div>
							<h2><?php __e('Listado de avistamentos'); ?></h2>
							<section class="listaxe-avistamentos">
								<p>
									<?php __e('Selecciona unha especie para ver os seus avistamentos'); ?>
								</p>
							</section>
						</div>
					</section>
				</div>
			</section>
		</div>
	</div>
</section>