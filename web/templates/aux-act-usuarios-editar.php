<?php defined('ANS') or die(); ?>

<li>
	<article class="act-contido">
		<i class="icon-pencil icon-2x pull-left"></i>

		<header>
			<div class="usuario">
				<?php echo ucfirst($Html->time($log['date'])); ?>

				<p><?php
					if ($log['usuarios_autor']['id'] === $user['id']) {
						__e('Editaches o teu perfil');
					} else {
						if ($amosar_usuario) {
							__e('%s editou o seu perfil', $autor);
						} else {
							__e('Editou o seu perfil');
						}
					}
				?></p>
			</div>
		</header>
	</article>
</li>
