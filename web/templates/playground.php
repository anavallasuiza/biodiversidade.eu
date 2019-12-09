<?php defined('ANS') or die(); ?>
<section class="content playground">
	<header>
		<div class="wrapper">
			<h1>Lets play!!</h1>
		</div>
	</header>
	
	<div class="content wrapper ly-f1">
		<div id="config"></div>
		<div class="map-placeholder">
			<div id="mapa" class="mapa"></div>
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
                
                <section class="toolbar-options">
                    <label>
                        <span><?php __e('Ver etiquetas'); ?></span>
                        <input type="checkbox" id="toggle-labels"/>
                    </label>
                </section>
                
                <section class="toolbar-options drawing-options">
                    <button id="drawing-default" class="btn pressed">
                        <i class="icon-hand-up"></i> <?php __e('Seleccionar'); ?>
                    </button>
                    <button id="drawing-delete" class="btn" disabled>
                        <i class="icon-remove"></i> <?php __e('Borrar selección'); ?>
                    </button>
                    <span class="separator"></span>
                    <button id="drawing-marker" class="btn">
                        <i class="icon-map-marker"></i> <?php __e('Punto'); ?>
                    </button>
                    <button id="drawing-alert" class="btn">
                        <i class="icon-map-marker"></i> <?php __e('Ameaza'); ?>
                    </button>
                    <button id="drawing-circle" class="btn">
                        <i class="icon-circle-blank"></i> <?php __e('Círculo'); ?>
                    </button>
                    <button id="drawing-rectangle" class="btn">
                        <i class="icon-circle-blank"></i> <?php __e('Rectángulo'); ?>
                    </button>
                    <button id="drawing-line" class="btn">
                        <i class="icon-circle-blank"></i> <?php __e('Linea'); ?>
                    </button>
                    <button id="drawing-polygon" class="btn">
                        <i class="icon-circle-blank"></i> <?php __e('Polígono'); ?>
                    </button>
                    <button id="drawing-polygon-blue" class="btn">
                        <i class="icon-circle-blank"></i> <?php __e('Polígono azul'); ?>
                    </button>
                </section>
            </div>
		</div>
	</div>
</section>