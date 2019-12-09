<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php echo __('Equipos de traballo') ?></h1>

			<?php if ($user) { ?>
			<nav>
				<a class="btn" href="<?php echo path('editar', 'equipo'); ?>">
					<i class="icon-plus"></i> <?php __e('Novo equipo'); ?>
				</a>
			</nav>
			<?php } ?>
		</div>	
	</header>

	<div class="content wrapper">
		<section class="subcontent">
			<header>
				<h1><?php __e('Buscar equipo'); ?></h1>
			</header>

			<form class="formulario" method="get">
				<fieldset>
					<p class="formulario-buscar">
						<label><?php echo __('Queres atopar un equipo ou equipo concreto? Proba a buscar polo nome do equipo.'); ?></label>
						<input type="search" name="q" value="<?php echo $Vars->var['q']; ?>" class="no-appearance" />
						<button type="submit" class="btn"><i class="icon-search"></i> <?php echo __('Buscar'); ?></button>
					</p>
				</fieldset>
			</form>
			
			<ul class="listaxe-equipos">
				<?php foreach ($equipos as $cada) { ?>
				<li><?php
					$Templates->render('aux-equipo.php', array(
						'equipo' => $cada
					));
				?></li>
				<?php } ?>
			</ul>
			
			<?php
			$Templates->render('aux-paxinacion.php', array(
				'pagination' => $Data->pagination
			));
			?>		
		</section>
	</div>
</section>
