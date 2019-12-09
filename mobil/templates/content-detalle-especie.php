<div data-role="page" id="page-buscar-especies">
	<div data-role="header" data-add-back-btn="true">
		<a href="#page-home" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php __e('Atrás'); ?></a>
		<?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php __e('Acerca de'); ?></a>
	</div>

	<div data-role="content" class="especie">
		<div class="content-header">
			<h1><?php echo $especie['nome_cientifico']; ?></h1>

			<p>
				<?php echo $especie['reinos']['nome']; ?>,
				<?php echo $especie['clases']['nome']; ?>,
				<?php echo $especie['ordes']['nome']; ?>,
				<?php echo $especie['familias']['nome']; ?>,
				<?php echo $especie['xeneros']['nome']; ?>
			</p>

			<p class="secondary"><?php echo $especie['sinonimos']; ?></p>
		</div>

		<?php if ($especie['imaxe']) { ?>
		<img src="<?php echo $Html->imgSrc($especie['imaxe']['imaxe'], 'resize,200'); ?>" alt="<?php echo $especie['nome']; ?>" />
		<?php } ?>

        <ul class="especie-clasificacion">
            <li><i class="icon-caret-right"></i> <?php echo $especie['grupos']['nome']; ?></li>
            <li><i class="icon-caret-right"></i> <?php echo $especie['clases']['nome']; ?></li>
            <li><i class="icon-caret-right"></i> <?php echo $especie['ordes']['nome']; ?></li>
            <li><i class="icon-caret-right"></i> <?php echo $especie['familias']['nome']; ?></li>
            <li><i class="icon-caret-right"></i> <?php echo $especie['xeneros']['nome']; ?></li>
        </ul>

		<div class="especie-seccion">
            <section>
            	<?php if ($especie['descricion']) { ?>
                <h1><?php __e('Descrición e bioloxía'); ?></h1>
                <div id="descricion" class="texto-ficha">
                    <?php echo $especie['descricion']; ?>
                </div>
                <?php } ?>

                <?php if ($especie['fenoloxia']) { ?>
                <h1><?php __e('Fenoloxía'); ?></h1>
                <div id="fenoloxia" class="texto-ficha">
                    <?php echo $especie['fenoloxia']; ?>
                </div>
                <?php } ?>

                <?php if ($especie['distribucion']) { ?>
                <h1><?php __e('Distribución'); ?></h1>
                <div id="distribucion" class="texto-ficha">
                    <?php echo $especie['distribucion']; ?>
                </div>
                <?php } ?>

                <?php if ($especie['habitat']) { ?>
                <h1><?php __e('Hábitat'); ?></h1>
                <div id="habitat" class="texto-ficha">
                    <?php echo $especie['habitat']; ?>
                </div>
                <?php } ?>
            </section>

            <section>
                <?php if ($especie['poboacion']) { ?>
                <h1><?php __e('Poboación'); ?></h1>
                <div id="poboacion" class="texto-ficha">
                    <?php echo $especie['poboacion']; ?>
                </div>
                <?php } ?>

                <?php if ($especie['ameazas']) { ?>
                <h1><?php __e('Factores de ameaza'); ?></h1>
                <div id="ameazas" class="texto-ficha">
                    <?php echo $especie['ameazas']; ?>
                </div>
                <?php } ?>

                <?php if ($especie['conservacion']) { ?>
                <h1><?php __e('Medidas de conservación'); ?></h1>
                <div id="conservacion" class="texto-ficha">
                    <?php echo $especie['conservacion']; ?>
                </div>
                <?php } ?>
            </section>

            <section>
            	<?php if ($especie['observacions']) { ?>
                <h1><?php __e('Observacions'); ?></h1>
                <div id="observacions" class="texto-ficha">
                    <?php echo $especie['observacions']; ?>
                </div>
                <?php } ?>
            </section>
		</div>

		<a data-role="button" data-icon="arrow-r" data-iconpos="right" href="<?php echo path(array('scene' => 'web'), 'especie', $especie['url']) . get('mobile', 'false'); ?>" rel="external"><?php __e('Ver a ficha completa'); ?></a>
	</div>
</div>
