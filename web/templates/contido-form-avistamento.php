<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('avistamentos'); ?>"><?php __e('Observacións'); ?></a></h1>
			<span>&gt;</span>
			<h2><?php echo $avistamento ? __('Edición do avistamento') : __('Novo avistamento'); ?></h2>
		</div>
	</header>

	<?php $Templates->render('aux-form-validation.php'); ?>

	<div class="content wrapper">
		<form action="<?php echo path(); ?>" class="formulario-pisos custom-validation" method="post" enctype="multipart/form-data">
            <input type="hidden" name="grupo" value="<?php echo $Vars->var['grupo']; ?>" />

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php __e('Localización'); ?></h1>

					<div id="avistamento-territorio" class="formulario-field">
                        <label for="territorio"><?php __e('Territorio'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($territorios, array(
                                'variable' => 'territorio',
                                'option_value' => 'url',
                                'option_text' => 'nome',
                                'id' => 'territorio',
                                'class' => 'w3 select-territorios',
                                'first_option' => '',
                                'data-placeholder' => __('Territorio'),
                                'data-selected' => $Vars->var['territorio'],
                                'data-child' => 'select.select-provincias',
                                'data-anchor' => 'avistamento-territorio'
                            ));
                            ?>
                        </div>
					</div>

					<div id="avistamento-provincia" class="formulario-field">
                        <label for="provincia"><?php __e('Provincia'); ?></label>
                        <div>
                            <?php
                            echo $Form->select(array(
                                'variable' => 'provincia',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'id' => 'provincia',
                                'class' => 'w3 select-provincias',
                                'first_option' => '',
                                'data-placeholder' => __('Provincia'),
                                'data-selected' => $Vars->var['provincia'],
                                'data-child' => 'select.select-concellos',
                                'data-anchor' => 'avistamento-provincia',
                                //'required' => true
                            ));
                            ?>
                        </div>
					</div>

					<div id="avistamento-concellos" class="formulario-field">
                        <label for="concello"><?php __e('Concello'); ?></label>
                        <div>
                            <?php
                            echo $Form->select(array(
                                'variable' => 'concello',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'id' => 'concello',
                                'class' => 'w3 select-concellos',
                                'first_option' => '',
                                'data-placeholder' => __('Concello'),
                                'data-selected' => $Vars->var['concello'],
                                'data-anchor' => 'avistamento-concellos',
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="localidade"><?php __e('Localidade'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'avistamentos[localidade]',
                                'id' => 'localidade',
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="nome-zona"><?php __e('Nome da zona'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'avistamentos[nome_zona]',
                                'id' => 'nome-zona',
                            ));
                            ?>
                        </div>
					</div>

					<div id="avistamento-xeolocalizacion" class="formulario-field-bloque obrigatorio">
						<label id="xeolocalizacions"><?php __e('Xeolocalización'); ?></label>

						<div>
							<section class="point-list">
								<header>
									<h1><?php __e('Listado de xeolocalizacións'); ?></h1>
								</header>

								<ul class="membros">
									<?php
									if ($avistamento) {
										foreach($avistamento['puntos'] as $i => $punto) {
									?>
									<li class="point-item" data-id="<?php echo $punto['id']; ?>">
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][action]" class="point-action" value=""/>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][id]" class="point-id" value="<?php echo $punto['id']; ?>"/>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][tipo]" class="point-type" value="<?php echo $punto['tipo']; ?>"/>
										<?php
										if ($punto['tipo'] === 'mgrs') {
											$texto = $punto['mgrs'];
										?>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][mgrs]" class="point-mgrs" value="<?php echo $punto['mgrs']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][altitude]" class="point-mgrs-altitude" value="<?php echo $punto['altitude']; ?>"/>
										<?php
										} else if ($punto['tipo'] === 'utm') {
											$texto = $punto['utm_fuso'] . ($punto['utm_sur'] ? 'S': 'N') . ' ' . $punto['utm_x'] . ' ' . $punto['utm_y'];
										?>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][utm_fuso]" class="point-utm-fuso" value="<?php echo $punto['utm_fuso']; ?>"/>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][sur]" class="point-utm-sur" value="<?php echo $punto['utm_sur'] ? '1' : ''; ?>"/>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][utm_x]" class="point-utm-x" value="<?php echo $punto['utm_x']; ?>"/>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][utm_y]" class="point-utm-y" value="<?php echo $punto['utm_y']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][altitude]" class="point-utm-altitude" value="<?php echo $punto['altitude']; ?>"/>
										<?php
										} else if ($punto['tipo'] === 'file') {
											$texto = __('Arquivo');
										?>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][file]" class="point-file" value="1"/>
										<?php
										} else  {
											$texto = $punto['latitude'] . ' ' . $punto['lonxitude'];
										?>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][latitude]" class="point-latitude" value="<?php echo $punto['latitude']; ?>"/>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][lonxitude]" class="point-longitude" value="<?php echo $punto['lonxitude']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][altitude]" class="point-altitude" value="<?php echo $punto['altitude']; ?>"/>
										<?php } ?>
										<input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][datum]" class="point-datum" value="<?php echo $punto['datums']['url']; ?>"/>
										<span><?php echo ucfirst($punto['tipo']); ?> <?php echo $punto['datums']['nome']; ?> - <?php echo $texto; ?></span>
										<button type="button" class="btn remove-point"><i class="icon-trash"></i></button>
										<button type="button" class="btn restore-point hidden"><i class="icon-remove"></i></button>
									</li>
									<?php
										}
									} else if ($notaCampo) {
                                        foreach ($notaCampo['puntos'] as $i => $punto) {
									?>
                                    <li class="point-item" data-id="<?php echo $punto['id']; ?>">
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][action]" class="point-action" value="new"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][id]" class="point-id" value="<?php echo $punto['id']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][tipo]" class="point-type" value="<?php echo $punto['tipo']; ?>"/>
                                        <?php
                                        if ($punto['tipo'] === 'mgrs') {
                                            $texto = $punto['mgrs'];
                                        ?>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][mgrs]" class="point-mgrs" value="<?php echo $punto['mgrs']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][altitude]" class="point-mgrs-altitude" value="<?php echo $punto['altitude']; ?>"/>
                                        <?php
                                        } else if ($punto['tipo'] === 'utm') {
                                            $texto = $punto['utm_fuso'] . ($punto['utm_sur'] ? 'S': 'N') . ' ' . $punto['utm_x'] . ' ' . $punto['utm_y'];
                                        ?>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][utm_fuso]" class="point-utm-fuso" value="<?php echo $punto['utm_fuso']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][sur]" class="point-utm-sur" value="<?php echo $punto['utm_sur'] ? '1' : ''; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][utm_x]" class="point-utm-x" value="<?php echo $punto['utm_x']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][utm_y]" class="point-utm-y" value="<?php echo $punto['utm_y']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][altitude]" class="point-utm-altitude" value="<?php echo $punto['altitude']; ?>"/>
                                        <?php
                                        } else if ($punto['tipo'] === 'file') {
                                            $texto = __('Arquivo');
                                        ?>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][file]" class="point-file" value="1"/>
                                        <?php
                                        } else  {
                                            $texto = $punto['latitude'] . ' ' . $punto['lonxitude'];
                                        ?>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][latitude]" class="point-latitude" value="<?php echo $punto['latitude']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][lonxitude]" class="point-longitude" value="<?php echo $punto['lonxitude']; ?>"/>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][altitude]" class="point-altitude" value="<?php echo $punto['altitude']; ?>"/>
                                        <?php } ?>
                                        <input type="hidden" name="xeolocalizacion[<?php echo $i; ?>][datum]" class="point-datum" value="<?php echo $punto['datums']['url']; ?>"/>
                                        <span><?php echo ucfirst($punto['tipo']); ?> <?php echo $punto['datums']['nome']; ?> - <?php echo $texto; ?></span>
                                        <button type="button" class="btn remove-point"><i class="icon-trash"></i></button>
                                        <button type="button" class="btn restore-point hidden"><i class="icon-remove"></i></button>
                                    </li>
                                    <?php
                                        }
                                    }
                                    ?>

									<li class="novo-punto">
										<button id="engadir-punto" class="btn">
											<i class="icon-plus"></i> <?php __e('Engadir');  ?>
										</button>
									</li>
								</ul>
							</section>

							<section id="form-edicion-puntos" class="editar-puntos hidden">
								<header>
									<h1><?php __e('Cear/Editar xeolocalización'); ?></h1>
								</header>
								<div id="editar-puntos-errores" class="validation-errors hidden">
									<div>
										<p><?php __e('Hai erros nos datos dos puntos:'); ?></p>
										<ul class="errors"></ul>
									</div>
								</div>
								<div class="formulario-punto tabs">
									<ul>
										<li>
											<label class="tab" data-type="latlong" data-selector="#latlong">
												<?php __e('Lat/Lng'); ?>
												<input type="radio" class="hidden" name="xeolocalizacion_tipo" value="latlong" />
											</label>
										</li>
										<li>
											<label class="tab" data-type="utm" data-selector="#utm">
												<?php __e('UTM'); ?> <?php __e('(puntos)'); ?>
												<input type="radio" class="hidden" name="xeolocalizacion_tipo" value="utm" />
											</label>
										</li>
										<li>
											<label class="tab selected" data-type="mgrs" data-selector="#mgrs">
												<?php __e('MGRS'); ?> <?php __e('(cuadrículas de 1km2 e 10km2)'); ?>
												<input type="radio" class="hidden" name="xeolocalizacion_tipo" value="mgrs" />
											</label>
										</li>
									</ul>

                                    <div class="content-mapa">
                                        <div class="map"></div>
                                    </div>

									<section id="latlong">
										<div id="mapa-toolbar-top" class="toolbar-mapa">

                                            <button id="fullscreen" type="button" class="btn fullscreen right">
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

											<section class="toolbar-options">
												<label>
													<span><?php __e('Ver etiquetas'); ?></span>
													<input type="checkbox" id="toggle-labels"/>
												</label>
											</section>

                                            <button type="button" class="btn xeo hidden" id="xeolocalizame">
                                                <i class="icon-screenshot"></i> <?php __e('Xeolocalizarme'); ?>
                                            </button>
										</div>

										<p class="formulario-field obrigatorio">
											<?php
											echo $Form->text(array(
												'variable' => 'puntos[latitude]',
												'id' => 'latitude',
												'name' => 'latitude',
												'label_text' => __('Latitude'),
												/*'min' => '-90',
												'max' => '90',
												'step' => '0.00000000000001',*/
												'placeholder' => __('p.e.: 41.9839942'),
												'required' => 'required',
												'disabled' => 'disabled'
											));
											?>
										</p>

										<p class="formulario-field obrigatorio">
											<?php
											echo $Form->text(array(
												'variable' => 'puntos[lonxitude]',
												'id' => 'lonxitude',
												'name' => 'lonxitude',
												'label_text' => __('Lonxitude'),
												/*'min' => '-180',
												'max' => '180',
												'step' => '0.00000000000001',*/
												'placeholder' => __('p.e.: -8.9839942'),
												'required' => 'required',
												'disabled' => 'disabled'
											));
											?>
										</p>

                                        <p class="formulario-field">
                                            <?php
                                            echo $Form->number(array(
                                                'variable' => 'puntos[altitude]',
                                                'id' => 'altitude',
                                                'name' => 'altitude',
                                                'label_text' => __('Altitude'),
                                                'placeholder' => __('p.e.: 135')
                                            ));
                                            ?>
                                        </p>

										<p class="formulario-field">
											<label><?php __e('Datum'); ?></label>
											<span><?php echo "WGS84"; ?></span>
											<input type="hidden" id="latlong_datum" value="wgs84"/>
										</p>

										<div class="formulario-field">
											<small><?php __e('Neste caso o datum será sempre WGS84, que é practicamente equivalente a ETRS89.'); ?></small>
										</div>

                                        <p class="formulario-field">
                                            <button class="right btn use-address" type="button" disabled><?php __e('Empregar dirección'); ?></button>
                                            <label><?php __e("Dirección aproximada"); ?></label>
                                            <span class="direccion-aproximada"></span>
                                        </p>
									</section>

									<section id="utm">
										<p id="select-datum" class="formulario-field obrigatorio">
											<?php
											echo $Form->select($datums, array(
												'variable' => 'puntos[datums]',
												'id' => 'utm_datum',
												'first_option' => '',
												'option_value' => 'url',
												'option_text' => 'nome',
												'label_text' => __('Datum'),
												'class' => 'w3',
												'data-placeholder' => __('Datum'),
												'data-anchor' => 'select-datum',
												'required' => 'required',
												'disabled' => 'disabled'
											));
											?>
										</p>

										<p class="formulario-field obrigatorio">
											<?php
											echo $Form->number(array(
												'variable' => 'puntos[utm_fuso]',
												'id' => 'utm_fuso',
                                                'class' => 'w2',
												'label_text' => __('Fuso'),
												'placeholder' => __('p.e.: 29'),
												'required' => 'required',
												'disabled' => 'disabled'
											));
											?>
										</p>

										<p class="formulario-field obrigatorio">
											<?php
											echo $Form->checkbox(array(
												'variable' => 'puntos[utm_sur]',
												'id' => 'utm_sur',
												'label_text' => __('Hemisferio sur')
											));
											?>
										</p>

										<p class="formulario-field obrigatorio">
											<?php
											echo $Form->number(array(
												'variable' => 'puntos[utm_x]',
												'id' => 'utm_x',
												'label_text' => __('X'),
												'min' => '160000',
												'max' => '840000',
												'step' => 1,
                                                'class' => 'w2',
												'placeholder' => __('p.e.: 250000'),
												'required' => 'required',
												'disabled' => 'disabled'
											));
											?>
										</p>

										<p class="formulario-field obrigatorio">
											<?php
											echo $Form->number(array(
												'variable' => 'puntos[utm_y]',
												'id' => 'utm_y',
												'label_text' => __('Y'),
												'min' => '0',
												'max' => '10000000',
												'step' => 1,
                                                'class' => 'w2',
												'placeholder' => __('p.e.: 580000'),
												'required' => 'required',
												'disabled' => 'disabled'
											));
											?>
										</p>

                                        <p class="formulario-field">
                                            <?php
                                            echo $Form->number(array(
                                                'variable' => 'puntos[altitude]',
                                                'id' => 'utm_altitude',
                                                'name' => 'altitude',
                                                'label_text' => __('Altitude'),
                                                'placeholder' => __('p.e.: 135')
                                            ));
                                            ?>
                                        </p>

                                        <p class="formulario-field">
                                            <button class="right btn use-address" type="button" disabled><?php __e('Empregar dirección'); ?></button>
                                            <label><?php __e("Dirección aproximada"); ?></label>
                                            <span class="direccion-aproximada"></span>
                                        </p>

									</section>

									<section id="mgrs">
										<p id="select-mgrs" class="formulario-field obrigatorio">
											<?php
											echo $Form->select($datums, array(
												'variable' => 'puntos[datums]',
												'id' => 'mgrs_datum',
												'first_option' => '',
												'option_value' => 'url',
												'option_text' => 'nome',
												'label_text' => __('Datum'),
												'class' => 'w3',
												'data-placeholder' => __('Debe seleccionar un datum'),
												'data-anchor' => 'select-datum',
												'required' => 'required',
												'disabled' => 'disabled'
											));
											?>
										</p>

										<p class="formulario-field obrigatorio">
											<?php
											echo $Form->text(array(
												'variable' => 'puntos[mgrs]',
												'id' => 'mgrs_coords',
												'label_text' => __('Mgrs'),
												'pattern' => '^([0-9]{1,2}[A-Z])([A-Z])([A-Z])([0-9]*)([A-Z])?$',
												'placeholder' => __('^([0-9]{1,2}[A-Z])([A-Z])([A-Z])([0-9]*)([A-Z])?$ - p.e.: 29TNH082101'),
												'required' => 'required',
												'disabled' => 'disabled'
											));
											?>
										</p>

                                        <p class="formulario-field">
                                            <?php
                                            echo $Form->number(array(
                                                'variable' => 'puntos[altitude]',
                                                'id' => 'mgrs_altitude',
                                                'name' => 'altitude',
                                                'label_text' => __('Altitude'),
                                                'placeholder' => __('p.e.: 135')
                                            ));
                                            ?>
                                        </p>

                                        <p class="formulario-field">
                                            <button class="right btn use-address" type="button" disabled><?php __e('Empregar dirección'); ?></button>
                                            <label><?php __e("Dirección aproximada"); ?></label>
                                            <span class="direccion-aproximada"></span>
                                        </p>
									</section>
								</div>
								<nav>
									<button class="btn guardar-punto" type="button">
										<?php __e('Guardar'); ?>
									</button>
									<button class="btn cancelar-punto" type="button">
										<?php __e('Cancelar'); ?>
									</button>
								</nav>
							</section>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-localizacion'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php __e('Especie'); ?></h1>

					<div id="selector-especie" class="formulario-field obrigatorio">
                        <label for="especie"><?php __e('Especie'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'especies[url]',
                                'placeholder' => __('Especie'),
                                'class' => 'listaxe-especies w3',
                                'id' => 'especie',
                                'data-selected' => $Vars->var['especies']['url'],
                                'data-text' => $Vars->var['especies']['nome'],
                                'data-anchor' => 'selector-especie'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="especie-desconhecida"><?php __e('Non sei exactamente o que é'); ?></label>
                        <div>
                            <?php
                            echo $Form->checkbox(array(
                                'variable' => 'desconecida',
                                'id' => 'especie-desconhecida',
                                'value' => '1',
                                'checked' => (!$avistamento ? false : ($Vars->var['especies']['url'] ? false : true)),
                                'force' => false
                            ));
                            ?>
                        </div>
					</div>

					<div id="nome-desconhecida" class="formulario-field obrigatorio">
                        <label for="especie_desconhecida"><?php __e('Creo que é'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'avistamentos[posible]',
                                'id' => 'especie_desconhecida',
                                'data-anchor' => 'nome-desconhecida'
                            ));
                            ?>
                        </div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-especie'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<?php
					$Templates->render('aux-form-imaxes.php', array(
						'imaxes' => $imaxes,
						'licenzas' => $licenzas,
						'imaxes_tipos' => $imaxes_tipos
					));
					?>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-imaxes'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><?php __e('Outros'); ?></h1>

					<div class="formulario-field">
						<label><?php __e('Observacións'); ?></label>
                        <div>
                            <?php
                            $valueObservacions = null;
                            if ($notaCampo) {
                                $valueObservacions = $notaCampo['texto'];
                            }
                            echo $Form->textarea(array(
                                'variable' => 'avistamentos[observacions]',
                                'value' => $valueObservacions
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">

                        <label for="especia-aloctona"><?php __e('Especie alóctona'); ?></label>
                        <div>
                            <?php
							echo $Form->checkbox(array(
								'variable' => 'avistamentos[invasora]',
								'value' => '1',
                                'id' => 'especie-aloctona',
								'checked' => ($Vars->var['avistamentos']['autoctona'] ? true : false),
								'force' => false
							));
							?>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <label for="especie-invasora"><?php __e('Especie invasora'); ?></label>
						<div>
							<?php
							echo $Form->checkbox(array(
								'variable' => 'avistamentos[invasora]',
								'value' => '1',
								'id' => 'especie-invasora',
								'checked' => ($Vars->var['avistamentos']['invasora'] ? true : false),
								'force' => false
							));
							?>
						</div>
					</div>

					<div id="referencias-tipos" class="formulario-field obrigatorio">
                        <label for="referencias-tipos"><?php __e('Fontes de datos'); ?></label>

                        <div>
                            <?php
                            echo $Form->select($referencias, array(
                                'variable' => 'referencias_tipos[id]',
                                'option_value' => 'id',
                                'option_text' => 'nome',
                                'id' => 'referencias-tipos',
                                'class' => 'selector-despregable',
                                'multiple' => 'multiple',
                                'required' => 'required',
                                'data-values' => $Vars->var['referencias_tipos'],
                                'data-placeholder' => __('Fonte de datos')
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="avistamento-referencia"><?php __e('Referencia'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'avistamentos[referencia]',
                                'id' => 'avistamento-referencia'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="outros-observadores"><?php __e('Outros observadores'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'avistamentos[outros_observadores]',
                                'id' => 'outros-observadores'
                            ));
                            ?>
                        </div>
					</div>

					<div id="avistamento-data-observacion" class="formulario-field">
                        <label for="data-observacion"><?php __e('Data avistamento'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'variable' => 'avistamentos[data_observacion]',
                                'id' => 'data-observacion',
                                'placeholder' => date('d-m-Y H:i P'),
                                'class' => 'datetimepicker',
                                'data-anchor' => 'avistamento-data-observacion'
                            ));
                            ?>
                        </div>
					</div>

					<div class="formulario-field">
                        <label for="proxectos-relacionados"><?php __e('Relacionar con algún dos meus proxectos'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($proxectos, array(
                                'variable' => 'proxectos[id]',
                                'option_value' => 'id',
                                'option_text' => 'titulo',
                                'multiple' => 'multiple',
                                'id' => 'proxectos-relacionados',
                                'class' => 'selector-despregable',
                                'data-values' => $Vars->var['proxectos']
                            ));
                            ?>
                        </div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-outros'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><a href="#fields-habitat" class="expand">+ <?php __e('Hábitat'); ?></a></h1>

					<div id="fields-habitat" class="hidden">
						<div class="formulario-field">
                            <label for="habitats-tipos"><?php __e('Tipo de hábitat'); ?></label>
                            <div>
                                <?php
                                echo $Form->select($habitats, array(
                                    'variable' => 'habitats_tipos[id]',
                                    'option_value' => 'id',
                                    'option_text' => 'nome',
                                    'id' => 'habitats-tipos',
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field">
                            <label for="estado-conservacion"><?php __e('Estado de conservación'); ?></label>
                            <div>
                                <?php
                                echo $Form->select($enum['estado_conservacion'], array(
                                    'variable' => 'avistamentos[estado_conservacion]',
                                    'id' => 'estado-conservacion',
                                    'option_text_as_value' => true,
                                    'gettext' => true,
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field">
                            <label for="abundancia"><?php __e('Abundancia da especie'); ?></label>
                            <div>
                                <?php
                                echo $Form->select($enum['abundancia'], array(
                                    'variable' => 'avistamentos[abundancia]',
                                    'id' => 'abundancia',
                                    'option_text_as_value' => true,
                                    'gettext' => true,
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field">
                            <label for="distribucion"><?php __e('Distribución da especie'); ?></label>
                            <div>
                                <?php
                                echo $Form->select($enum['distribucion'], array(
                                    'variable' => 'avistamentos[distribucion]',
                                    'id' => 'distribucion',
                                    'option_text_as_value' => true,
                                    'gettext' => true,
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
						</div>

                        <?php if (in_array($Vars->var['grupo'], array('plantas', 'algas', 'fungos'))) { ?>

						<div class="formulario-field">
                            <label for="fenoloxia-individuos"><?php __e('Fenoloxía do individuos'); ?></label>

                            <div>
                                <?php
                                echo $Form->select($enum['fenoloxia_individuos'], array(
                                    'variable' => 'avistamentos[fenoloxia_individuos]',
                                    'id' => 'fenoloxia-individuos',
                                    'option_text_as_value' => true,
                                    'gettext' => true,
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
						</div>

                        <?php } else { ?>

                        <div class="formulario-field">
                            <label for="sexo"><?php __e('Sexo'); ?></label>

                            <div>
                                <?php
                                echo $Form->select($enum['sexo'], array(
                                    'variable' => 'avistamentos[sexo]',
                                    'id' => 'sexo',
                                    'option_text_as_value' => true,
                                    'gettext' => true,
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="formulario-field">
                            <label for="fase"><?php __e('Fase ou estadío'); ?></label>

                            <div>
                                <?php
                                echo $Form->select($enum['fase'], array(
                                    'variable' => 'avistamentos[fase]',
                                    'id' => 'fase',
                                    'option_text_as_value' => true,
                                    'gettext' => true,
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="formulario-field">
                            <label for="migracion"><?php __e('Comportamento Migratorio'); ?></label>

                            <div>
                                <?php
                                echo $Form->select($enum['migracion'], array(
                                    'variable' => 'avistamentos[migracion]',
                                    'id' => 'migracion',
                                    'option_text_as_value' => true,
                                    'gettext' => true,
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="formulario-field">
                            <label for="climatoloxia"><?php __e('Condicións climatolóxicas'); ?></label>
                            <div>
                                <?php
                                echo $Form->text(array(
                                    'variable' => 'avistamentos[climatoloxia]',
                                    'id' => 'climatoloxia'
                                ));
                                ?>
                            </div>
                        </div>

                        <?php } ?>

						<div class="formulario-field">
                            <label for="substrato_xeoloxico"><?php __e('Sustrato xeolóxico'); ?></label>
                            <div>
                                <?php
                                echo $Form->text(array(
                                    'variable' => 'avistamentos[substrato_xeoloxico]',
                                    'id' => 'substrato_xeoloxico'
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field">
                            <label for="especies-acompanhantes"><?php __e('Especies acompañantes'); ?></label>
                            <div>
                                <?php
                                echo $Form->text(array(
                                    'variable' => 'acompanhantes',
                                    'id' => 'especies-acompanhantes',
                                    'placeholder' => __('Escriba o nome das especies acompañantes'),
                                    'class' => 'listaxe-especies acompanantes',
                                    'data-multiple' => true,
                                    'data-values' => preg_replace('/"/i', '\'', json_encode($acompanhantes))
                                    //$avistamento['especies']['url'] // ['nome']
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field">
                            <label for="uso_solo"><?php __e('Uso do solo'); ?></label>
                            <div>
                                <?php
                                echo $Form->text(array(
                                    'variable' => 'avistamentos[uso_solo]',
                                    'id' => 'uso_solo'
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field-cols">
							<label><?php __e('Solo actualmente activo'); ?></label>

							<div class="radio-group">
								<label>
									<?php __e('Si'); ?>
									<input name="avistamentos[uso_activo]" type="radio" value="1" />
								</label>

								<label>
									<?php __e('Non'); ?>
									<input name="avistamentos[uso_activo]" type="radio" value="0" />
								</label>
							</div>
						</div>

						<div class="formulario-field">
                            <label for="xestion_ambiental"><?php __e('Xestión ambiental'); ?></label>
                            <div>
                                <?php
                                echo $Form->select($enum['xestion_ambiental'], array(
                                    'variable' => 'avistamentos[xestion_ambiental]',
                                    'id' => 'xestion_ambiental',
                                    'option_text_as_value' => true,
                                    'gettext' => true,
                                    'class' => 'w3',
                                    'first_option' => '',
                                    'data-placeholder' => __('Escolle unha opción')
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field">
                            <label for="ameazas-tipos-nivel1"><?php __e('Ameazas de nivel 1'); ?></label>
                            <div>
                                <?php
                                echo $Form->select($ameazas, array(
                                    'variable' => 'ameazas_tipos_nivel1[id]',
                                    'option_value' => 'id',
                                    'option_text' => 'nome',
                                    'multiple' => 'multiple',
                                    'id' => 'ameazas-tipos-nivel1',
                                    'class' => 'selector-despregable',
                                    'data-values' => $Vars->var['ameazas_tipos_nivel1']
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field">
                            <label for="ameazas-tipos-nivel2"><?php __e('Ameazas de nivel 2'); ?></label>
                            <div>
                                <?php
                                echo $Form->select($ameazas, array(
                                    'variable' => 'ameazas_tipos_nivel2[id]',
                                    'option_value' => 'id',
                                    'option_text' => 'nome',
                                    'multiple' => 'multiple',
                                    'id' => 'ameazas-tipos-nivel2',
                                    'class' => 'selector-despregable',
                                    'data-values' => $Vars->var['ameazas_tipos_nivel2']
                                ));
                                ?>
                            </div>
						</div>

						<div class="formulario-field">
                            <label for="observacions_conservacion"><?php __e('Observacións e recomendacións de conservación'); ?></label>
                            <div>
                                <?php
                                echo $Form->textarea(array(
                                    'variable' => 'avistamentos[observacions_conservacion]',
                                    'id' => 'observacions_conservacion'
                                ));
                                ?>
                            </div>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-habitat'); ?></p>
				</section>
			</div>

			<div class="ly-f1">
				<fieldset class="ly-e1">
					<h1><a href="#fields-avanzadas" class="expand">+ <?php __e('Opcións avanzadas'); ?></a></h1>

					<div id="fields-avanzadas" class="hidden">
						<div class="tabs">
							<ul>
								<li><a href="#xermoplasma"><?php __e('Xermoplasma'); ?></a></li>
								<li><a href="#coroloxia"><?php __e('Coroloxía'); ?></a></li>
							</ul>

							<section id="xermoplasma">
								<?php include ($Templates->file('aux-form-xermoplasma.php')); ?>
							</section>

							<section id="coroloxia">
								<?php include ($Templates->file('aux-form-coroloxia.php')); ?>
							</section>
						</div>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-avanzado'); ?></p>
				</section>
			</div>

			<?php if ($Acl->check('action', 'avistamento-validar')) { ?>
			<div class="ly-f1">
				<fieldset class="ly-e1">
					<div class="formulario-field-cols">

						<p class="radio-group">
							<?php
							echo $Form->checkbox(array(
								'variable' => 'avistamentos[validado]',
								'value' => '1',
								'label_text' => __('Validar'),
								'checked' => ($Vars->var['avistamentos']['validado'] ? true : false),
								'force' => false
							));
							?>
						</p>
					</div>
				</fieldset>

				<section class="erros-axuda ly-e2">
					<p><?php __e('axuda-formulario-avistamentos-validar'); ?></p>
				</section>
			</div>
			<?php } ?>

			<fieldset class="footer">
				<p class="formulario-buttons">
                    <?php if ($avistamento['url'] && $Acl->check('action', 'avistamento-eliminar')) { ?>
                    <a class="btn eliminar" href="<?php echo path('avistamento', $avistamento['url']).get('phpcan_action', 'avistamento-eliminar'); ?>" data-confirm="<?php __e('¿Está seguro de que desexa borrar este contido? Ten en conta que esta acción non se pode desfacer.'); ?>">
                        <i class="icon-trash"></i>
                        <?php __e('Eliminar'); ?>
                    </a>
                    <?php } ?>

                    <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'name' => 'phpcan_action',
                        'value' => 'avistamento-gardar',
                        'class' => 'btn btn-highlight',
                        'text' => ('<i class="icon-save"></i> '.__('Gardar')),
                        'id' => 'button-gardar'
                    ));
                    ?>

					<a href="<?php echo $avistamento ? path('avistamento', $avistamento['url']) : path('avistamentos'); ?>" class="btn right">
						<i class="icon-remove"></i> <?php __e('Cancelar'); ?>
					</a>
                </p>
			</fieldset>
		</form>
	</div>
</section>

<?php if ($Vars->var['avistamentos']) { ?>
<script>
$(document).ready(function () {
	$('select.select-territorios').trigger('change');
});
</script>
<?php } ?>
