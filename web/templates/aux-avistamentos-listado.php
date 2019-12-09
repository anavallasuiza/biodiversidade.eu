<?php defined('ANS') or die(); ?>

<?php if (empty($avistamentos)) { ?>
<p><?php __e('Non temos ningún avistamento para as especies seleccionadas'); ?></p>
<?php return true; } ?>

<?php if ($Vars->var['template'] === 'list') { ?>
<div class="ly-11">
	<?php $metade = ceil(count($avistamentos) / 2); ?>

	<ul class="listaxe ly-e1">
		<?php
		foreach ($avistamentos as $num => $avistamento) {
			echo $Html->each('</ul><ul class="listaxe ly-e2">', $metade, $num - 1);

			$Templates->render('aux-avistamento.php', array(
				'mapa' => true,
				'avistamento' => $avistamento
			));
		}
		?>
	</ul>
</div>
<?php exit; } ?>

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
				<?php $Templates->render('aux-avistamento-mini.php', $avistamentos); ?>
			</div>
		</div>
	</div>	
</div>
