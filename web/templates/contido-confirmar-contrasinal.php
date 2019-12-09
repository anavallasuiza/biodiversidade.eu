<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Confirma o contrasinal recibido'); ?></h1>

			<nav>
				<a class="btn" href="<?php echo path('entrar'); ?>"><?php __e('Voltar ó acceso'); ?></a>
			</nav>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent log">
			<form action="<?php echo path(); ?>" class="formulario" method="post">
				<input type="email" name="email" value="" class="hidden" />
				<input type="text" name="mail" value="" class="hidden" />
				<input type="hidden" name="referer" value="<?php echo $Vars->var['r'] ? urldecode($Vars->var['r']) : ''; ?>" />

				<input type="text" name="email" id="email" value="" class="oculto" />
				<input type="hidden" name="referer" value="<?php echo $Vars->var['r'] ? urldecode($Vars->var['r']) : ''; ?>" />

				<fieldset>
					<p class="formulario-field hidden"><?php
					echo $Form->email(array(
						'name' => 'email',
						'label_text' => __('Campo falso para evitar bots'),
						'class' => 'required'
					));
					?></p>

					<p class="formulario-field"><?php
					echo $Form->text(array(
						'name' => 'contrasinal_tmp',
						'label_text' => __('O código que recibiches no correo'),
						'required' => 'required'
					));
					?></p>

					<p class="formulario-field"><?php
					echo $Form->password(array(
						'name' => 'contrasinal',
						'label_text' => __('O teu novo contrasinal'),
						'required' => 'required'
					));
					?></p>

					<p class="formulario-field"><?php
					echo $Form->password(array(
						'name' => 'contrasinal_repeat',
						'label_text' => __('Repite o contrasinal'),
						'required' => 'required'
					));
					?></p>

					<div>
						<p class="formulario-buttons"><?php
						echo $Form->button(array(
							'type' => 'submit',
							'id' => 'btn-login',
							'name' => 'phpcan_action',
							'value' => 'confirmar-contrasinal',
							'class' => 'btn btn-highlight',
							'text' => __('Confirmar')
						));
						?>
					</div>
				</fieldset>
			</form>
		</section>
	</div>
</section>
