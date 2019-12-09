<?php defined('ANS') or die(); ?>

<?php foreach ($taxons as $taxon) { ?>
<li class="<?php echo $texto ? 'selected' : ''; ?>">
	<?php if (!$especies) { ?>
	<a class="todas" href="<?php echo path('catalogo', $taxon['url']); ?>">
		<?php __e('Ver todas'); ?>
	</a>
	<?php } ?>
	<span>
		<i class="icon-caret-right"></i> <?php echo $taxon['nome']; ?>
	</span> 
	<ul>
		<?php foreach ($taxon['xeneros'] as $subtaxon) { ?>
		<li class="xenero <?php echo $especies ? 'request': ''; ?>" data-codigo="<?php echo $subtaxon['url']; ?>">
			<?php if ($especies) { ?>
			<span class="xenero request" data-codigo="<?php echo $subtaxon['url']; ?>">
				<i class="icon-caret-right"></i> <?php echo $subtaxon['nome']; ?>
			</span>
			<ul class="especies-menu-selector"></ul>
			<?php } else { ?>
			<a href="<?php echo path('catalogo', $taxon['url'], $subtaxon['url']); ?>">
				<?php echo $subtaxon['nome']; ?>
			</a>
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
</li>
<?php } ?>