<?php defined('ANS') or die(); ?>

<ul class="avistamento-informacion">
	<?php if ($avistamento['distancia_umbral']) { ?>
	<li><strong><?php __e('Distancia umbral entre poboacións'); ?>:</strong> <?php echo $avistamento['distancia_umbral']; ?></li>
	<?php } ?>

	<?php if ($avistamento['definicion_individuo']) { ?>
	<li><strong><?php __e('Definición do individuo'); ?>:</strong> <?php echo $avistamento['definicion_individuo']; ?></li>
	<?php } ?>

	<?php if ($avistamento['coroloxia_herbario']) { ?>
	<li><strong><?php __e('Voucher herbario'); ?>:</strong> <?php echo $avistamento['coroloxia_herbario'] ? __('Sí') : __('Non'); ?></li>
	<?php } ?>

	<?php if ($avistamento['coroloxia_herbario_numero']) { ?>
	<li><strong><?php __e('Nº'); ?>:</strong> <?php echo $avistamento['coroloxia_herbario_numero']; ?></li>
	<?php } ?>
</ul>

<h2><?php __e('Polígonos que limitan a poboación') ?></h2>

<h3><?php __e('Área de presenza') ?></h3>

<ul class="avistamento-informacion">
	<?php if ($avistamento['numero_exemplares']) { ?>
	<li><strong><?php __e('Nº de exemplares'); ?>:</strong> <?php echo $avistamento['numero_exemplares']; ?></li>
	<?php } ?>

	<?php if ($avistamento['area_presencia_shapefile']) { ?>
	<li><strong><?php __e('Arquivos Shapefile'); ?>:</strong> <?php echo $Html->a(__('Descargar'), $avistamento['area_presencia_shapefile']); ?></li>
	<?php } ?>

	<?php if ($avistamento['tipo_censo']) { ?>
	<li><strong><?php __e('Tipo de censo empregado'); ?>:</strong> <?php echo ($avistamento['tipo_censo'] === 'directo') ? __('Censo directo') : __('Censo por estima'); ?></li>
	<?php } ?>

	<?php if ($avistamento['superficie_ocupacion']) { ?>
	<li><strong><?php __e('Superficie de ocupación'); ?>:</strong> <?php echo $avistamento['superficie_ocupacion']; ?></li>
	<?php } ?>

	<?php if ($avistamento['densidade_ocupacion']) { ?>
	<li><strong><?php __e('Densidade (individuos por m2)'); ?>:</strong> <?php echo $avistamento['densidade_ocupacion']; ?></li>
	<?php } ?>
</ul>

<?php if ($avistamento['area_prioritaria']) { ?>
<h3><?php echo __('Área de prioritaria') ?></h3>

<ul class="avistamento-informacion">
	<li><strong><?php __e('Arquivos Shapefile'); ?>:</strong> <?php echo $Html->a(__('Descargar'), $avistamento['area_prioritaria']); ?></li>
</ul>
<?php } ?>

<?php if ($avistamento['area_potencial']) { ?>
<h2><?php echo __('Área de potencial') ?></h2>

<ul class="avistamento-informacion">
	<li><strong><?php __e('Arquivos Shapefile'); ?>:</strong> <?php echo $Html->a(__('Descargar'), $avistamento['area_potencial']); ?></li>
</ul>
<?php } ?>

<h2><?php echo __('Estima por cuadrículas UTM 500 x 500 m'); ?></h2>

<h3><?php echo __('Cuadrícula UTM (centro da cuadrícula de 500 m2)'); ?></h3>

<ul class="avistamento-informacion">
	<?php if ($avistamento['superficie_mostreada']) { ?>
	<li><strong><?php __e('Superficie mostreada (m2)'); ?>:</strong> <?php echo $avistamento['superficie_mostreada']; ?></li>
	<?php } ?>

	<?php if ($avistamento['individuos_contados']) { ?>
	<li><strong><?php __e('Número de individuos contados'); ?>:</strong> <?php echo $avistamento['individuos_contados']; ?></li>
	<?php } ?>

	<?php if ($avistamento['superficie_potencial']) { ?>
	<li><strong><?php __e('Superficie potencial do hábitat da cuadrícula'); ?>:</strong> <?php echo $avistamento['superficie_potencial']; ?></li>
	<?php } ?>

	<?php if ($avistamento['densidade']) { ?>
	<li><strong><?php __e('Densidade'); ?>:</strong> <?php echo $avistamento['densidade']; ?></li>
	<?php } ?>

	<?php if ($avistamento['individuos_estimados']) { ?>
	<li><strong><?php __e('Nº de individuos estimados'); ?>:</strong> <?php echo $avistamento['individuos_estimados']; ?></li>
	<?php } ?>

	<?php if ($avistamento['observacions_coroloxia']) { ?>
	<li><strong><?php __e('Observacións'); ?>:</strong> <?php echo $avistamento['observacions_coroloxia']; ?></li>
	<?php } ?>
</ul>
