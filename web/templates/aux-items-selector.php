<?php defined('ANS') or die(); ?>

<?php foreach ($items as $item) { ?>
<li>
	<?php if ($type === 'familias' && $catalogo) { ?>
	<a class="todas" href="<?php echo path('catalogo', $item['url']); ?>">
		<?php __e('Ver todas'); ?>
	</a>
	<?php } ?>
	<span class="<?php echo $type; ?> request <?php echo $item['avistamentos'] ? 'con-avistamientos' : ''; ?> <?php echo !$url ? 'custom': ''; ?>" data-codigo="<?php echo $item['url']; ?>" data-name="<?php echo $item['nome']; ?>" <?php echo $url ? 'data-url="' . $url . '"' : ''; ?>>
		<?php if ($url) { ?>
		<i class="icon-caret-right"></i>
		<?php } ?> <?php echo $item['nome']; ?>
	</span>

	<?php if ($url) { ?>
	<ul class="<?php echo $ulClass; ?>"></ul>
	<?php } ?>
</li>
<?php } ?>