<?php defined('ANS') or die(); ?>

<?php $Datetime = getDatetimeObject(); ?>

<li>
	<article class="actividade">
		<header>
			<time>
				<span class="dia"><?php echo date('d', strtotime($evento['data'])); ?></span>
				<span class="mes"><?php echo $Datetime->getMonth(date('m', strtotime($evento['data'])), true); ?></span>
			</time>

			<h1><a href="<?php echo path('evento', $evento['url']); ?>"><?php echo $evento['titulo']; ?></a></h1>

			<p class="actividade-lugar">
				<?php echo $evento['lugar']; ?>

    			<?php if ($evento['comentarios']) { ?>
                | <?php echo (count($evento['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($evento['comentarios'])); ?>
                <?php } ?>
			</p>
		</header>
	</article>
</li>
