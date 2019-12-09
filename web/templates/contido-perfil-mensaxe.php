<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Perfil de usuario'); ?></h1>
            <span>&gt;</span>
            <h2><?php __e('Enviar mensaxe'); ?></h2>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<div class="subcontent">
			<article class="perfil perfil-permalink">
				<?php
				echo $Html->img(array(
					'src' => $usuario['avatar'],
					'alt' => $usuario['nome']['title'],
					'width' => 200,
					'height' => 200,
					'transform' => 'zoomCrop,200,200',
					'class' => 'usuario perfil-avatar'
				));
				?>

				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/editor.png'),
               		'class' => 'editor'
                ));
	            ?>

				<header style="padding-left: 243px;">
					<h1><?php echo $usuario['nome']['title'].' '.$usuario['apelido1']; ?></h1>
                    
                    <form action="" class="formulario-pisos custom-validation" method="post" style="margin-top: 20px;">
                        <input type="hidden" name="usuario" value="<?= $usuario['id']; ?>" />
                        <div class="ly-f1">
                            <fieldset class="ly-e1">
                                <div class="formulario-field obrigatorio">
                                    <label for="titulo"><?php __e('Mensaxe'); ?></label>
                                    <div>
                                        <?php
                                        echo $Form->textarea(array(
                                            'variable' => 'mensaxe',
                                            'required' => 'required',
                                            'style' => 'height: 99px;'
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <fieldset class="footer">
                            <p class="formulario-buttons">
                                <?php
                                echo $Form->button(array(
                                    'type' => 'submit',
                                    'name' => 'phpcan_action',
                                    'value' => 'mensaxe',
                                    'class' => 'btn btn-highlight',
                                    'text' => __('Enviar')
                                ));
                                ?>

                                <a href="<?php echo path('perfil', $usuario['nome']['url']); ?>" class="btn right">
                                    <?php __e('Cancelar'); ?>
                                </a>
                            </p>
                        </fieldset>
                    </form>
				</header>
			</article>
		</div>
	</div>
</section>
