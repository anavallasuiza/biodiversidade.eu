<div data-role="page">
	<div data-role="header">
		<h1><?php echo __('Benvida/o') ?></h1>
		<a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
	</div>

	<div data-role="content">
		<div class="content-header">
			<?php echo $Html->img('templates|img/logo-biodiv.png') ?>
			
			<h1><?php echo __('Iniciar sesion'); ?></h1>
		</div>


		<form action="<?php echo path('') . '?' . time(); ?>" method="post" data-ajax="false">
			<input type="hidden" name="referer" value="<?php echo $Vars->var['referer'] ?: getenv('HTTP_REFERER'); ?>" />

			<label for="login-mail"><?php echo __('O teu correo'); ?></label>
			<input type="email" id="login-mail" name="usuario" required />

			<label for="login-password"><?php echo __('Contrasinal'); ?></label>
			<input type="password" id="login-password" name="contrasinal" required />

			<button type="submit" data-icon="arrow-r" data-inline="true"><?php __e('Entrar'); ?></button>
            
            <input type="hidden" name="phpcan_action" value="acceso"/>
		</form>
	</div>
</div>
