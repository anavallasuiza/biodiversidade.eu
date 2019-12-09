<?php
defined('ANS') or die();

if (empty($pagination) || ($pagination['total_pages'] < 2)) {
	return '';
}

$p = empty($p) ? 'p' : $p;
$anchor = empty($anchor) ? '' : ('#'.$anchor);
?>

<ul class="paxinacion">
	<li class="primeira"><?php
		if ($pagination['page'] == 1) {
			echo '<span>'.__('Primeira').'</span>';
		} else {
			echo $Html->a(__('Primeira'), path().get($p, 1).$anchor);
		}
	?></li>

	<li><?php
		if (!$pagination['previous']) {
			echo '<span>'.__('Anterior').'</span>';
		} else {
			echo $Html->a(__('Anterior'), path().get($p, $pagination['page'] - 1).$anchor);
		}
	?></li>
	
	<?php
		for ($n = $pagination['first']; $n <= $pagination['last']; $n++) {
			if ($pagination['page'] == $n) {
				echo '<li><span>'.$n.'</span></li>';
			} else {
				echo '<li>'.$Html->a($n, path().get($p, $n).$anchor).'</li>';
			}
		}
	?>
	
	<li><?php
		if (!$pagination['next']) {
			echo '<span>'.__('Seguinte').'</span>';
		} else {
			echo $Html->a(__('Seguinte'), path().get($p, $pagination['page'] + 1).$anchor);
		}
	?></li>

	<li class="ultima"><?php
		if ($pagination['page'] == $pagination['total_pages']) {
			echo '<span>'.__('Última').'</span>';
		} else {
			echo $Html->a(__('Última'), path().get($p, $pagination['total_pages']).$anchor);
		}
	?></li>
</ul>
