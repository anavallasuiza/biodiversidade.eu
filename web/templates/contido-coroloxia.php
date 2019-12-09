<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><a href="<?php echo path('avistamentos'); ?>"><?php __e('Avistamentos'); ?></a></h1>
			<span>&gt;</span>
			<h2><a href="<?php echo path('avistamento'); ?>"><?php __e('Avistamento'); ?></a></h2>			
			<span>&gt;</span>
			<h2><?php __e('Coroloxía'); ?></h2>

			<nav>
				<a class="btn" href="#"><i class="icon-plus"></i>
					<?php __e('Novo avistamento'); ?></a>
			</nav>
		</div>
	</header>

	<div class="content wrapper ly-f1">
		<section class="ly-e1">
			<article class="especie-avistamento especie-avistamento-permalink">
				<header>
					<h1>Nome da especie</h1>
					<h2>Nome local da especie</h2>
				</header>

				<section class="datos-ficha">
					<ul class="avistamento-informacion">
						<li><strong><?php __e('Distancia umbral entre poboacións:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Definición do individuo:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Voucher herbario:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Nº:'); ?></strong> Lorem ipsum</li>
					</ul>
				</section>

				<section class="datos-ficha">
					<h1><?php __e('Polígonos que limitan a poboación'); ?></h1>
					<h2><?php __e('Área de presenza'); ?></h2>
					<ul class="avistamento-informacion">
						<li><strong><?php __e('Número de exemplares:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Tipo de censo empregado:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Superficie ocupación:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Densidade (individuos por m2:)'); ?></strong> Lorem ipsum</li>
						<li><a href="#"><i class="icon-picture"></i>
							<?php __e('Arquivo shapefile'); ?></a>
						</li>
					</ul>
					<h2><?php __e('Área de prioritaria'); ?></h2>
					<ul class="avistamento-informacion">
						<li><a href="#"><i class="icon-picture"></i>
							<?php __e('Arquivo shapefile'); ?></a>
						</li>
					</ul>					

					<h2><?php __e('Área de potencial'); ?></h2>
					<ul class="avistamento-informacion">
						<li><a href="#"><i class="icon-picture"></i>
							<?php __e('Arquivo shapefile'); ?></a>
						</li>
					</ul>
				</section>									

				<section id="especies" class="datos-ficha">
					<h1><?php __e('Estima por cuadrículas UTM 500 x 500 m'); ?></h1>
					<h2><?php __e('Cuadrícula UTM (centro da cuadrícula de 500 m2'); ?></h2>
					<ul class="avistamento-informacion">
						<li><strong><?php __e('Superficie mostreada (m2):'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Número de individuos contados:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Superficie potencial do hábitat da cuadrícula:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Densidade:'); ?></strong> Lorem ipsum</li>
						<li><strong><?php __e('Nº de individuos estimados:'); ?></strong> Lorem ipsum</li>
					</ul>
				</section>

				<section class="datos-ficha">
					<h1><?php __e('Observacións'); ?></h1>
					<div class="avistamento-intro">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>
				</section>
			</article>
		</section>
	</div>
</section>