<?php
defined('ANS') or die();

$limit = 10;

if (!$length) {
	return '';
}
?>
<ul class="paxinacion paxinacion-cliente <?php echo $length <= 1 ? 'hidden' : ''; ?>" data-list="<?php echo $list; ?>">
	<li class="primeira">	
		<a href="#" data-page="1">
			<?php __e('Primeira'); ?>
		</a>
	</li>
	<li>
		<a href="#" data-page="prev">
			<?php __e('Anterior'); ?>
		</a>
	</li>
    
	<?php for ($i = 1; $i <= $length; $i++) { ?>
	<li>
		<a href="#" data-page="<?php echo $i; ?>">
			<?php echo $i; ?>
		</a>
	</li>	
	<?php } ?>
    
	<li>
		<a href="#" data-page="next">
			<?php __e('Seguinte'); ?>
		</a>
	</li>

	<li class="ultima">
		<a href="#" data-page="<?php echo $length; ?>">
			<?php __e('Última'); ?>
		</a>
	</li>
</ul>
