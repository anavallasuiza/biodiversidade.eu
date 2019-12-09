<?php defined('ANS') or die(); ?>

<?php if (empty($avistamentos)) { ?>
<p><?php __e('Non temos ningún avistamento para as especies seleccionadas'); ?></p>
<?php return true; } ?>

<div class="content">
	<div class="item-listaxe" data-page="<?php echo 1; ?>">
		<span class="next" data-anssliderindex="+1">
			<i class="icon-angle-right"></i>
		</span>

		<span class="previous" data-anssliderindex="-1">
			<i class="icon-angle-left"></i>
		</span>

		<div class="listaxe">
			<div>
				<?php $Templates->render('aux-avistamento-mini-area.php', $avistamentos); ?>
			</div>
		</div>
	</div>	
</div>
