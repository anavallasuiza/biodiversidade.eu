<?php defined('ANS') or die(); ?>

<li>
	<article class="proxecto-actividade">
		<header>
			<h1>
				<?php if ($meu || $proxecto['aberto']) { ?>
				<a href="<?php echo path('proxecto', $proxecto['url']); ?>"><?php echo $proxecto['titulo']; ?></a>
				<?php } else { ?>
				<span><?php echo $proxecto['titulo']; ?></span>
				<?php } ?>

				&nbsp;
				<?php if ($proxecto['aberto']) { ?>
				<i class="icon-unlock" title="<?php __e('Proxecto aberto'); ?>"></i>
				<?php } else { ?>
				<i class="icon-lock" title="<?php __e('Proxecto privado'); ?>"></i>
				<?php } ?>
			</h1>
		</header>

		<p><?php echo $proxecto['texto']; ?></p>

		<?php if ($meu && $proxecto['logs']) { ?>
		<footer>
			<time><?php echo ucfirst($Html->time($proxecto['logs']['date'])); ?></time>
		</footer>

		<div class="actividade">
			<?php
			$Templates->render('aux-log.php', array(
				'log' => $proxecto['logs']
			));
			?>
		</div>
		<?php } ?>

		<?php if (empty($meu) && $user && empty($proxecto['usuarios_solicitude'])) { ?>
		<a href="?phpcan_action=proxecto-solicitude&amp;proxecto=<?php echo $proxecto['url']; ?>"><?php __e('Solicitar participación'); ?></a>
		<?php } ?>
	</article>
</li>
