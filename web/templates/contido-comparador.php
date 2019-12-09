<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Comparador de imaxes de %s', $reino['nome']); ?></h1>
		</div>
	</header>
    
	<div class="content wrapper">
		<section class="subcontent">
			<div>
				<ul class="comparador">
					<li>
                        <div class="right">
                            <button id="engadir-especie" class="btn" type="button">
                                <i class="icon-plus"></i> <?php __e('Engadir especie'); ?>
                            </button>
                        </div>
						<ul class="fila-titulo">
                            <?php 
                            $width = 750 / count($imaxes_tipos);
                            foreach($imaxes_tipos as $tipo) {
                            ?>
                            <li data-code="<?php echo $tipo['url']; ?>" style="width: <?php echo $width; ?>px;">
                                <?php echo $tipo['nome']; ?>
                            </li>
                            <?php } ?>
						</ul>
					</li>
					<li>
                        <ul class="listado">
                        <?php foreach($imaxes as $especie) {
                            $Templates->render('aux-fila-comparador.php', array(
                                'tipos' => $imaxes_tipos,
                                'especie' => $especie
                            ));
                        } ?>
                        </ul>
					</li>						
				</ul>
			</div>
            <div id="modal-especie" class="hidden">
                <div>
                    <label for="especie"><?php __e('Seleccione la especie a añadir: '); ?></label>
                    <input type="text" id="especie" class="listaxe-especie-reino" data-reino="<?php echo $reino['url']; ?>"/>
                </div>
                <div>
                    <button type="button" class="btn right modal-aceptar">
                        <?php __e('Aceptar'); ?>
                    </button>
                    
                    <button type="button" class="btn modal-cancelar">
                        <?php __e('Cancelar'); ?>
                    </button>
                </div>
            </div>
		</section>
	</div>
</section>
<div class="overlay-comparador hidden">
    <div class="overlay-background"></div>
    <div class="overlay-items"></div>
    <div class="overlay-toolbar">
        <button type="button" id="cerrar-overlay" class="btn btn-highlight">
            <i class="icon-remove"></i> <?php __e('Cerrar visor'); ?>
        </button>
    </div>
</div>
