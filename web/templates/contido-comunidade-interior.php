<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Perfil Comunidade'); ?></h1>

			<nav>
				<?php if ($user) { ?>

				<?php if ($Acl->check('action', 'comunidade-editar')) { ?>

				<a class="btn" href="<?php echo path('editar', 'comunidade', $comunidade['url']); ?>">
					<i class="icon-edit"></i> <?php __e('Editar esta ficha'); ?>
				</a>

				<?php } ?>

				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<li>
							<a class="modal-ajax" href="<?php echo path('editar', 'comunidade'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova ficha'); ?>
							</a>
						</li>
					</ul>
				</div>

				<?php } else { ?>

				<div class="btn-group">
					<button class="btn">
						<i class="icon-plus"></i> <?php __e('Crear'); ?> <span class="caret"></span>
					</button>

					<ul>
						<li>
							<a class="modal-ajax" href="<?php echo path('entrar'); ?>">
								<i class="icon-plus"></i> <?php __e('Nova ficha'); ?>
							</a>
						</li>
					</ul>
				</div>

				<?php } ?>
			</nav>
		</div>
	</header>

	<div class="content wrapper ly-f1">
    	<section class="subcontent">
	        <a class="btn-link" href="<?php echo path('comunidade'); ?>">
	            <i class="icon-arrow-left"></i>
	            <?php __e('Voltar'); ?>
	        </a>

			<article class="ficha-comunidade permalink">
				<header>
					<figure>
						<?php
						echo $Html->img(array(
							'src' => $comunidade['logo'],
							'alt' => $comunidade['nome'],
							'class' => 'miniatura',
							'transform' => 'resize,250,',
							'class' => 'perfil-logo'
						));
						?>
					</figure>

					<h1><?php echo $comunidade['nome']; ?></h1>

					<ul class="datos">
						<?php if ($comunidade['web']) { ?>
						<li><?php __e('Web'); ?>: <a href="<?php echo $comunidade['web']; ?>" target="_blank"><?php echo $comunidade['web']; ?></a> |</li> 
						<?php } if ($comunidade['telefono']) { ?>
						<li><?php __e('Teléfono'); ?>: <a href="tel:<?php echo $comunidade['telefono']; ?>"><?php echo $comunidade['telefono']; ?></a> |</li> 
						<?php } if ($comunidade['correo']) { ?>
						<li><?php __e('Correo electrónico'); ?>: <a href="mailto:<?php echo $comunidade['correo']; ?>"><?php echo $comunidade['correo']; ?></a></li> 
						<?php } ?>
					</ul>
				</header>

				<div class="descricion">
					<?php echo $comunidade['texto']; ?>
				</div>
			</article>
	    </section> 
    </div>
</section>
