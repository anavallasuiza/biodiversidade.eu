<?php defined('ANS') or die(); ?>

<ul class="especies">
<?php
foreach ($especies as $especie) {
	$Templates->render('aux-especie.php', array('especie' => $especie));
}
?>
</ul>

<?php $Templates->render('aux-paxinacion.php', array('pagination' => $Data->pagination)); ?>