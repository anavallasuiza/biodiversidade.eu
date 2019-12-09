<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Ameazas e iniciativas de conservación'); ?></h1>

			<nav>
                <a class="btn icon-rss" href="<?php echo path('rss', 'ameazas'); ?>"><?php __e('Subscríbete'); ?></a>

                <div class="btn-group">
                    <button class="btn">
                        <i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
                    </button>

                    <ul>
                        <?php if ($user) { ?>

						<li>
							<a href="<?php echo path('editar', 'ameaza'); ?>">
    							<i class="icon-plus"></i> <?php echo __('Nova ameaza'); ?>
							</a>
						</li>
                        <li>
                            <a href="<?php echo path('editar', 'iniciativa'); ?>">
                                <i class="icon-plus"></i> <?php echo __('Nova iniciativa de conservación'); ?>
                            </a>
                        </li>

                        <?php } else { ?>

                        <li>
                            <a class="modal-ajax" href="<?php echo path('entrar'); ?>">
                                <i class="icon-plus"></i> <?php __e('Nova ameaza'); ?>
                            </a>
                        </li>
                        <li>
                            <a class="modal-ajax" href="<?php echo path('entrar'); ?>">
                                <i class="icon-plus"></i> <?php __e('Nova iniciativa de conservación'); ?>
                            </a>
                        </li>

                        <?php } ?>
                    </ul>
                </div>
			</nav>
		</div>
	</header>

	<div class="content wrapper tabs-ameazas">
		<div class="tabs">
			<ul>
				<li>
					<a href="#ameazas" data-selector=".tab-ameazas"><?php echo __('Ameazas pestana'); ?></a>
                </li>
                <li>
                    <a href="#iniciativas" data-selector=".tab-iniciativas"><?php echo __('Iniciativas pestana'); ?></a>
                </li>
			</ul>

			<div class="tab-ameazas tab-iniciativas" id="mapa">
				<div class="content-mapa  content-mapa-ameazas">
					<div class="mapa"></div>
				</div>

                <div class="map-controls hidden">
                    <!-- Spinner -->
                    <span class="cargando hidden" id="map-loading-spinner">
                        <i class="icon-spinner icon-spin"></i>&nbsp;<?php __e('Cargando'); ?>
                    </span>
                    
                    <!-- Controls -->
                    <div id="mapa-toolbar-top">
                        <!-- Controles del mapa -->
                        <div class="controles-mapa toolbar-mapa">
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
                            
                            <section class="toolbar-options">
                                <label>
                                    <span><?php __e('Ver etiquetas'); ?></span>
                                    <input type="checkbox" id="toggle-labels"/>
                                </label>
                            </section>
                        </div>
                    </div>
                </div>
			</div>

			<section class="tab-item tab-ameazas subcontent">
                <header>
                    <form action="<?php echo path(); ?>" class="subcontent-filter" method="get">
                        <fieldset>
                            <?php
                            echo $Form->select(array(
                                'options' => $ameazas_tipos,
                                'variable' => 'ameaza_tipo',
                                'option_value' => 'id',
                                'option_text' => 'nome',
                                'class' => 'w3',
                                'first_option' => '',
                                'data-placeholder' => __('Tipo de ameaza')
                            ));

                            echo $Form->select(getNiveisAmeazas('ameazas'), array(
                                'variable' => 'nivel',
                                'class' => 'w3 nivel-ameaza',
                                'first_option' => '',
                                'data-placeholder' => __('Nivel de ameaza')
                            ));

                            echo $Form->select($territorios, array(
                                'variable' => 'territorio',
                                'option_value' => 'url',
                                'option_text' => 'nome',
                                'class' => 'w3 select-territorios',
                                'first_option' => '',
                                'data-placeholder' => __('Territorio'),
                                'data-selected' => $Vars->var['territorio'],
                                'data-child' => 'select.select-provincias'
                            ));

                            echo $Form->select(array(
                                'variable' => 'provincia',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'class' => 'w3 select-provincias',
                                'first_option' => '',
                                'data-placeholder' => __('Provincia'),
                                'data-selected' => $Vars->var['provincia'],
                                'data-child' => 'select.select-concellos'
                            ));

                            echo $Form->select(array(
                                'variable' => 'concello',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'class' => 'w3 select-concellos',
                                'first_option' => '',
                                'data-placeholder' => __('Concello'),
                                'data-selected' => $Vars->var['concello'],
                            ));

                            echo $Form->text(array(
                                'variable' => 'especie',
                                'placeholder' => __('Especie'),
                                'class' => 'listaxe-especies w3',
                                'id' => 'especie',
                                'data-selected' => $especie['url'],
                                'data-text' => $especie['nome'],
                                'data-anchor' => 'selector-especie'
                            ));
                            

                            echo $Form->select(array(
                                'options' => array(
                                    '1' => __('Activas'),
                                    '0' => __('Desactivas')
                                ),
                                'variable' => 'estado',
                                'class' => 'w2',
                                'first_option' => '',
                                'data-placeholder' => __('Estado')

                            ));
                            ?>
                            
                            <select name="ano" class="w1" data-placeholder="<?php __e('Ano'); ?>">
                                <option></option>
                                <?php foreach(range('2013', date('Y')) as $anho) { ?>
                                <option value="<?php echo $anho; ?>" <?php echo ($Vars->var['ano'] === $anho) ? 'selected': ''; ?>>
                                    <?php echo $anho; ?>
                                </option>
                                <?php } ?>
                            </select>

                            <button type="submit" class="btn btn-highlight"><i class="icon-filter"></i> <?php __e('Filtrar'); ?></button>
                        </fieldset>
                    </form>
                </header>

				<?php if ($ameazas) { ?>
                <?php 
                $kmls = array(
                    'http://abertos.xunta.es/catalogo/cultura-ocio-deporte/-/dataset/0305/teatros-auditorios/002/descarga-directa-ficheiro.kml',
                    'http://abertos.xunta.es/catalogo/territorio-vivienda-transporte/-/dataset/0304/estacions-inspeccion-tecnica-vehiculos/002/descarga-directa-ficheiro.kml'
                );
                ?>

				<div class="listado-ameazas paxinado content" id="listado-ameazas">
				<?php foreach($ameazas as $i => $paxina) { ?>
					<div class="item-listaxe ly-11" data-page="<?php echo $i + 1; ?>">
						<?php foreach($paxina as $i => $columna) { ?>
						<ul class="listaxe ly-e<?php echo $i + 1; ?>">
							<?php foreach($columna as $j => $ameaza) {
                                $kml = null;
                                if (count($kmls) > $j) {
                                    $kml = $kmls[$j];
                                }

								$Templates->render('aux-ameaza.php', array(
									'ameaza' => $ameaza,
                                    'kml' => $kml
								));
							} ?>
						</ul>
						<?php } ?>
					</div>
				<?php } ?>
				</div>

				<?php
				$Templates ->render('aux-paxinacion-cliente.php', array(
					'length' => count($ameazas)
				));
				?>

				<?php } else { ?>

				<div class="alert alert-info">
					<?php __e('Non se atoparon ameazas con eses filtros.'); ?>
				</div>

				<?php } ?>
			</section>

            <section class="tab-item tab-iniciativas subcontent">
                <header>
                    <form action="<?php echo path(); ?>#iniciativas" class="subcontent-filter" method="get">
                        <fieldset>
                            <?php
                            echo $Form->select(array(
                                'options' => $iniciativas_tipos,
                                'variable' => 'iniciativa_tipo',
                                'option_value' => 'id',
                                'option_text' => 'nome',
                                'class' => 'w3',
                                'first_option' => '',
                                'data-placeholder' => __('Tipo de iniciativa')
                            ));

                            echo $Form->select($territorios, array(
                                'variable' => 'territorio',
                                'option_value' => 'url',
                                'option_text' => 'nome',
                                'class' => 'w3 select-territorios',
                                'first_option' => '',
                                'data-placeholder' => __('Territorio'),
                                'data-selected' => $Vars->var['territorio'],
                                'data-child' => 'select.select-provincias'
                            ));

                            echo $Form->select(array(
                                'variable' => 'provincia',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'class' => 'w3 select-provincias',
                                'first_option' => '',
                                'data-placeholder' => __('Provincia'),
                                'data-selected' => $Vars->var['provincia'],
                                'data-child' => 'select.select-concellos'
                            ));

                            echo $Form->select(array(
                                'variable' => 'concello',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'class' => 'w3 select-concellos',
                                'first_option' => '',
                                'data-placeholder' => __('Concello'),
                                'data-selected' => $Vars->var['concello'],
                            ));

                            echo $Form->select(array(
                                'options' => array(
                                    '1' => __('Activas'),
                                    '0' => __('Desactivas')
                                ),
                                'variable' => 'estado',
                                'class' => 'w2',
                                'first_option' => '',
                                'data-placeholder' => __('Estado')
                            ));
                            ?>
                            
                            <select name="ano" class="w1" data-placeholder="<?php __e('Ano'); ?>">
                                <option></option>
                                <?php foreach(range('2013', date('Y')) as $ano) { ?>
                                <option value="<?php echo $ano; ?>" <?php echo ($Vars->var['ano'] === $ano) ? 'selected': ''; ?>>
                                    <?php echo $ano; ?>
                                </option>
                                <?php } ?>
                            </select>

                            <button type="submit" class="btn btn-highlight"><i class="icon-filter"></i> <?php __e('Filtrar'); ?></button>
                        </fieldset>
                    </form>
                </header>

                <?php if ($iniciativas) { ?>

                <div class="listado-iniciativas paxinado content" id="listado-iniciativas">
                <?php foreach($iniciativas as $i => $paxina) { ?>
                    <div class="item-listaxe ly-11" data-page="<?php echo $i + 1; ?>">
                        <?php foreach($paxina as $i => $columna) { ?>
                        <ul class="listaxe ly-e<?php echo $i + 1; ?>">
                            <?php foreach($columna as $j => $iniciativa) {
                                $Templates->render('aux-iniciativa.php', array(
                                    'iniciativa' => $iniciativa
                                ));
                            } ?>
                        </ul>
                        <?php } ?>
                    </div>
                <?php } ?>
                </div>

                <?php
                $Templates ->render('aux-paxinacion-cliente.php', array(
                    'length' => count($iniciativas)
                ));
                ?>

                <?php } else { ?>

                <div class="alert alert-info">
                    <?php __e('Non se atoparon iniciativas con eses filtros.'); ?>
                </div>

                <?php } ?>
            </section>
		</div>
	</div>
</section>
