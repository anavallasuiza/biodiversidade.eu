<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Recuperar o contrasinal'); ?></h1>

			<nav>
				<a class="btn" href="<?php echo path('entrar'); ?>"><?php __e('Voltar ó acceso'); ?></a>
			</nav>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent log">
			<form action="<?php echo path(); ?>" class="formulario" method="post">
				<input type="email" name="email" value="" class="hidden" />
				<input type="text" name="mail" value="" class="oculto" />
				<input type="hidden" name="referer" value="<?php echo $Vars->var['r'] ? urldecode($Vars->var['r']) : ''; ?>" />

				<fieldset>
					<p class="formulario-field"><?php
					echo $Form->email(array(
						'name' => 'usuario',
						'label_text' => __('Indícanos o teu email e enviarémosche un correo coas indicacións para recuperar o teu contrasinal'),
						'placeholder' => __('exemplo@email.com'),
						'required' => 'required'
					));
					?></p>

					<p class="formulario-field hidden"><?php
					echo $Form->email(array(
						'name' => 'email',
						'label_text' => __('Campo falso para evitar bots'),
						'class' => 'required'
					));
					?></p>

					<div>
						<p class="formulario-buttons"><?php
						echo $Form->button(array(
							'type' => 'submit',
							'id' => 'btn-login',
							'name' => 'phpcan_action',
							'value' => 'recuperar-contrasinal',
							'class' => 'btn btn-highlight',
							'text' => __('Recuperar')
						));
						?>
					</div>
				</fieldset>
			</form>
		</section>
	</div>
</section>
