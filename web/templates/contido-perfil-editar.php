<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Perfil de usuario'); ?></h1>
		</div>
	</header>
    
    <?php $Templates->render('aux-form-validation.php'); ?>

	<div class="content wrapper ly-f1">
		<article class="perfil perfil-permalink">
			<?php
			echo $Html->img(array(
				'src' => $user['avatar'],
				'alt' => $user['nome']['title'],
				'width' => 200,
				'height' => 200,
				'transform' => 'zoomCrop,200,200',
				'class' => 'usuario perfil-avatar'
			));
			?>

			<section class="form-content">
				<header>
					<h1><?php echo __e('Os meus datos'); ?></h1>
				</header>

				<form action="<?php echo path(); ?>" class="formulario form-perfil custom-validation" method="post" enctype="multipart/form-data">
					<fieldset>
						<p class="formulario-field"><?php
							echo $Form->email(array(
								'variable' => 'usuarios[usuario]',
								'label_text' => __('O email que usarás como usuario'),
								'required' => 'required'
							));
						?></p>

						<p class="formulario-field"><?php
							echo $Form->password(array(
								'variable' => 'usuarios[contrasinal]',
								'label_text' => (__('Contrasinal').'<small>'.__('cúbreo só se desexas cambialo').'</small>')
							));
						?></p>

						<p class="formulario-field"><?php
							echo $Form->password(array(
								'variable' => 'usuarios[contrasinal_repeat]',
								'label_text' => __('Repite o Contrasinal')
							));
						?></p>

						<p class="formulario-field input-3"><?php
							echo $Form->text(array(
								'variable' => 'usuarios[nome]',
								'label_text' => __('O teu nome completo'),
								'placeholder' => __('Nome'),
								'required' => 'required'
							));

							echo $Form->text(array(
								'variable' => 'usuarios[apelido1]',
								'placeholder' => __('Primeiro apelido')
							));

							echo $Form->text(array(
								'variable' => 'usuarios[apelido2]',
								'placeholder' => __('Segundo apelido')
							));
						?></p>
                        
                        <p class="formulario-field"><?php
							echo $Form->text(array(
								'variable' => 'usuarios[especialidade]',
								'label_text' => __('Especialidade ou especialidades')
							));
						?></p>

						<p class="formulario-field"><?php
							echo $Form->textarea(array(
								'variable' => 'usuarios[bio]',
								'label_text' => __('Cóntanos algo sobre ti'),
							));
						?></p>

						<p class="formulario-field input-3"><?php
							echo $Form->text(array(
								'variable' => 'usuarios[facebook]',
								'label_text' => __('Tes contas en redes sociais?'),
								'placeholder' => __('O teu perfil de fácebook')
							));

							echo $Form->text(array(
								'variable' => 'usuarios[twitter]',
								'placeholder' => __('A túa conta de twitter')
							));

							echo $Form->text(array(
								'variable' => 'usuarios[linkedin]',
								'placeholder' => __('Ou a de Linkedin')
							));
						?></p>

						<p class="formulario-field"><?php
							echo $Form->checkbox(array(
								'variable' => 'usuarios[notificacions]',
								'value' => 1,
								'checked' => ($user['notificacions'] ? 'checked' : ''),
								'label_text' => __('Permitir notificacións por correo electrónico'),
							));
						?></p>

						<?php if ($Acl->check('action', 'role-editor')) { ?>
						<p class="formulario-field"><?php
							echo $Form->checkbox(array(
								'variable' => 'usuarios[notificacions_editor]',
								'value' => 1,
								'checked' => ($user['notificacions_editor'] ? 'checked' : ''),
								'label_text' => __('Recibir notificacións como editor'),
							));
						?></p>
						<?php } ?>


						<p class="formulario-field"><?php
							echo $Form->file(array(
								'variable' => 'usuarios[avatar]',
								'label_text' => __('Sube unha imaxe de perfil'),
                                'data-text' => basename($user['avatar']),
                                'data-value' => ($user['avatar'] ? fileWeb('uploads|'  .$user['avatar'], false, true) : '')
							));
						?></p>

						<div>
							<p class="formulario-buttons">
			                    <a href="<?php echo path('perfil'); ?>" class="btn right">
			                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
			                    </a>

								<?php
									echo $Form->button(array(
										'type' => 'submit',
										'id' => 'btn-login',
										'variable' => 'phpcan_action',
										'value' => 'perfil-gardar',
										'class' => 'btn btn-highlight',
										'text' => __('Gardar os cambios')
									));
								?>
							</p>
						</div>
					</fieldset>
				</form>
			</section>
		</article>
	</div>
</section>
