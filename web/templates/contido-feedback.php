<?php defined('ANS') or die(); ?>
<section class="feedback">
	<header>
		<h1><?php echo __e('Formulario de feedback'); ?></h1>
	</header>

	<form action="<?php echo path() . ':feedback'?>" method="post">
		<p class="tipo">
			<label data-related="contido" class="selected" for="radio-contido">
				<?php __e('Contido')?><input type="radio" id="radio-contido" name="tipo" checked="checked" value="contido" />
			</label>

			<label data-related="erros" class="" for="radio-web">
				<?php __e('Erros')?><input type="radio" id="radio-web" name="tipo" value="erros" />
			</label>
		</p>
	
		<fieldset>
			<p class="email">
				<label for="email"><?php __e('Email-feedback'); ?>:</label>
				<input id="email" name="feedback[email]" type="email" required/>
			</p>
			
			<p class="texto">
				<label for="texto"><?php __e('Texto-feedback'); ?>:</label>
				<textarea id="texto" name="feedback[texto]" required></textarea>
			</p>
			
			<input type="email" name="email" class="email"/>
		</fieldset>
		
		<div class="button">
			<button type="submit"><?php __e('Enviar'); ?></button>
		</p>
	</form>
	
	<div class="loading hidden">
		<?php __e('Enviado...'); ?>
	</div>
</section>
