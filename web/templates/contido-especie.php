<?php defined('ANS') or die(); ?>

<form id="editar-especie" method="post" enctype="multipart/form-data" data-url="<?php echo $especie['url']; ?>">
	<section class="content">
		<header>
			<div class="wrapper">
				<h1><?php __e('Catálogo'); ?></h1>

				<?php if ($user) { ?>
				<nav>
					<a class="btn" href="<?php echo get(array('phpcan_action' => 'vixiar')); ?>">
						<i class="icon-eye-open"></i> <?php echo $especie['vixiar'] ? __('Deixar de seguir') : __('Vixiar especie'); ?>
					</a>

                    <div class="btn-group">
						<button class="btn">
							<i class="icon-share-alt"></i> <?php __e('Exportar a CSV'); ?> <span class="caret"></span>
						</button>
                        <ul>
							<li>
								<a href="?phpcan_exit_mode=csv">
                                    <i class="icon-share-alt"></i> <?php __e('Datos da especie'); ?>
                                </a>
							</li>
							<li>
								<a href="?phpcan_exit_mode=csv&amp;tipo=observacions">
                                    <i class="icon-share-alt"></i> <?php __e('Observacions da especie'); ?>
                                </a>
							</li>
                        </ul>
                    </div>

					<div class="btn-group">
						<button class="btn">
							<i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
						</button>

						<ul>
							<li>
								<a href="<?php echo path('editar', 'avistamento').get('especie', $especie['url']); ?>">
									<i class="icon-plus"></i> <?php __e('Novo avistamento'); ?>
								</a>
							</li>
							<li>
								<a href="<?php echo path('editar', 'ameaza').get('especie', $especie['url']); ?>">
									<i class="icon-plus"></i> <?php __e('Nova ameaza'); ?>
								</a>
							</li>
                            <li>
								<a href="<?php echo path('editar', 'especie'); ?>">
									<i class="icon-plus"></i> <?php __e('Nova especie'); ?>
								</a>
							</li>

							<?php if ($Acl->check('action', 'especie-editar')) { ?>
			                <li>
								<a href="#" id="boton-editar" name="boton-editar-especie" data-edit="<?php __e('Editar'); ?>" data-cancel="<?php __e('Cancelar'); ?>" data-icon="icon-remove">
									<i class="icon-pencil"></i>
									<tag id="btn-text-start"><?php __e('Editar'); ?></tag>
									<tag id="btn-text-end" class="hidden"><?php __e('Cancelar edición'); ?></tag>
								</a>
							</li>
							<?php } ?>

			                <?php if ($Acl->check('action', 'especie-eliminar')) { ?>
							<li>
			                    <a href="<?php echo path() . get('phpcan_action', 'especie-eliminar'); ?>" data-confirm="<?php __e('Si elimina unha especie eliminaranse todas as súas observacións e amenazas. ¿Está seguro de que desexa continuar?'); ?>">
									<i class="icon-trash"></i> <?php __e('Eliminar'); ?>
								</a>
			                </li>
			                <?php } ?>
						</ul>
					</div>

                    <a href="#" id="boton-gardar" name="boton-gardar-especie" class="hidden btn btn-highlight boton-gardar">
						<i class="icon-save"></i> <?php __e('Gardar'); ?>
					</a>

					<?php if ($especie['bloqueada']) { ?>
                    <span class="especie-bloqueada">
						<i class="icon-info"></i> <?php __e('Especie coa edición bloqueada'); ?>
					</span>
					<?php } ?>
				</nav>
				<?php } ?>
			</div>
		</header>
        
        <?php $Templates->render('aux-form-validation.php'); ?>

        <?php include($Templates->file('aux-especie-ficha.php')); ?>
	</section>
</form>

<div class="content wrapper ly-f1">
    <section class="no-margin-top ly-e1">
	    <?php
	    $Templates->render('aux-comentarios.php', array(
	        'comentarios' => $comentarios
	    ));
	    ?>
	</section>
</div>

