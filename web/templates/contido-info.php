<?php defined('ANS') or die(); ?>

<section class="content">
	<div class="content wrapper ly-f1">
		<section class="subcontent ly-e1">
            <?php if ($texto['url'] === 'importacion') { ?>
            <a class="btn-link" href="javascript:void(0);" onclick="history.back();">
                <i class="icon-arrow-left"></i>
                <?php echo __('Voltar'); ?>
            </a>
            <?php } ?>
            
			<article class="texto texto-permalink">
				<header>
					<h1><?php echo $texto['titulo']; ?></h1>
				</header>

				<?php echo $texto['texto']; ?>
			</article>
		</section>

		<aside class="ly-e2">
			<?php if ($menu_lateral): ?>
			<ul class="menu-lateral">
				<?php foreach ($menu_lateral as $value): ?>
				<li<?php echo $value['url'] === $texto['url'] ? ' class="selected"' : ''; ?>>
					<a href="<?php echo path(true, $value['url']); ?>"><?php echo $value['titulo'] ?></a>
				</li>	
				<?php endforeach ?>
                
                <?php if ($texto['menu'] == 1) { ?>
                <li>
                    <a href="<?php echo path('editores'); ?>">
                        <?php __e('Editores da web'); ?>
                    </a>
                </li>
                <?php } ?>
			</ul>
			<?php endif ?>
		</aside>
	</div>
</section>
