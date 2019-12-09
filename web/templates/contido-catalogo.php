<?php defined('ANS') or die(); ?>

<section class="content catalogo">
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
					<a href="<?php echo path('catalogo', 'mapa'); ?>"><?php echo __('Mapa catálogo'); ?></a>
				</li>
				<li>
					<a href="<?php echo path(); ?>" class="selected"><?php echo __('Especies pestana'); ?></a>
				</li>
                <li>
					<a href="<?php echo path('catalogo', 'areas'); ?>"><?php echo __('Ferramenta areas'); ?></a>
				</li>
				<li class="pestana-right">
					<a href="<?php echo path('catalogo', 'sen-identificar'); ?>"><i class="icon-pencil"></i> <?php echo __('Sen identificar'); ?></a>
				</li>
			</ul>

			<section id="catalogo" class="ly-1f">
				<section class="subcontent ly-e1">
					<div class="columnas seleccion-especie">
						<div class="row row-buscador formulario">
							<label>
								<i class="icon-spinner icon-spin cargando"></i>
								<input type="search" id="texto-buscar" name="texto" value="" class="no-appearance" placeholder="<?php __e('Buscar especie'); ?>">
							</label>
						</div>

						<div class="row-listado">
							<div class="panel-content">
								<section class="content-tree">
									<div class="formulario hidden">
										<fieldset>
											<p class="formulario-filtrar">
												<input type="checkbox" id="con-avistamentos" name="con-avistamentos" value="1">
												<label for="con-avistamentos"><?php __e('Ver so especies con observacións'); ?></label>
											</p>
										</fieldset>
									</div>

									<div class="especies-seleccionadas">
										<h2><?php __e('Especies seleccionadas'); ?></h2>

										<section class="lista-especies-seleccionadas">
											<p class="sen-especies <?php echo $especie ? 'hidden': ''; ?>">
												<?php __e('Non hai ningun xénero nin familia seleccionada'); ?>
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
										<?php foreach ($grupos as $grupo) { ?>
										<li>
											<span class="grupo request <?php echo $grupo['avistamentos'] ? 'con-avistamientos' : ''; ?>" data-codigo="<?php echo $grupo['url']; ?>" data-url="<?php echo path('get-listado-clases') . get('catalogo', '1'); ?>">
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
				</section>

				<div class="ly-e2">
					<section class="subcontent ly-row">
						<header>
							<h1><?php __e('Buscar especies'); ?></h1>
						</header>

						<form action="<?php echo path('catalogo'); ?>" class="formulario form-especie">
							<fieldset>
								
								<div>
									<input type="search" id="texto-especies" name="busca" value="<?php echo $Vars->var['busca']; ?>" class="no-appearance" placeholder="<?php __e('Por nome científico ou vernacular'); ?>" autofocus>

									<select class="nivel-ameaza w3" data-placeholder="<?php __e('Nivel de ameaza'); ?>">
										<option></option>
										<option value="0"><?php __e('Non ameazada'); ?></option>
                                        <?php foreach (getNiveisAmeazas('especies') as $indice => $nivel) { ?>
										<option value="<?php echo $indice; ?>"><?php echo $nivel; ?></option>
                                        <?php } ?>
									</select>
								
									<button type="submit" id="texto-boton" class="btn"><i class="icon-filter"></i> <?php echo __('Filtrar'); ?></button>
								</div>
							</fieldset>
						</form>
					</section>

					<section class="subcontent">

						<div class="listado-especies">
							<div></div>
							<div class="especies-overlay">
								<i class="icon-spinner icon-spin"></i> <?php __e('Cargando'); ?>
								<div class="background"></div>
							</div>
						</div>

						<?php if ($especies) { ?>

						<ul class="especies">
							<?php
							foreach ($especies as $especie) {
								$Templates->render('aux-especie.php', array(
									'especie' => $especie
								));
							}
							?>
						</ul>

						<?php
						$Templates->render('aux-paxinacion.php', array(
							'pagination' => $Data->pagination
						));
						?>

						<?php } ?>
					</section>
				</div>
			</section>
		</div>
	</div>
</section>
