<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('catalogo'); ?>"><?php echo __('Catálogo'); ?></a></h1>
			<span>&gt;</span>
			<h2>Familia</h2>
			<nav>
				<button class="btn">Nova especie</button>
			</nav>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent">
			<header>
				<form class="subcontent-filter">
					<fieldset>
						<label><?php __e('Por nivel de ameaza'); ?> <select><option>---</option></select></label>
					</fieldset>
				</form>
			</header>		
		</section>
		<div class="ly-e2">
			<section class="subcontent ly-row">
				<header>
					<h1>Nome da familia</h1>
				</header>
				<div>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</div>
				<a class="btn-link" href="#"><?php echo __('Saber máis') ?></a>

			</section>

			<section class="subcontent">
				<ul class="especies">
					<?php for ($n=0; $n<8; $n++) {
						$Templates->render('aux-especie.php');
					} ?>
				</ul>

				<?php
				$Templates ->render('aux-paxinacion.php', array(
					'pagination' => $Data->pagination
				));
				?>
			</section>
		</div>
	</div>
</section>
