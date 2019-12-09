<?php defined('ANS') or die(); ?>

<ul class="avistamento-informacion">
    <?php if ($avistamento['banco_xeoplasma']) { ?>
    <li><strong><?php __e('Banco de xermoplasma'); ?>:</strong> <?php echo $avistamento['banco_xeoplasma']; ?></li>
    <?php } ?>

    <?php if ($avistamento['idcoleccion']) { ?>
    <li><strong><?php __e('Id colección'); ?>:</strong> <?php echo $avistamento['idcoleccion']; ?></li>
    <?php } ?>

    <?php if ($avistamento['numero_colleita']) { ?>
    <li><strong><?php __e('Número de colleita'); ?>:</strong> <?php echo $avistamento['numero_colleita']; ?></li>
    <?php } ?>

    <?php if ($avistamento['colector']) { ?>
    <li><strong><?php __e('Colector'); ?>:</strong> <?php echo $avistamento['colector']; ?></li>
    <?php } ?>

    <?php if ($avistamento['xermoplasma_herbario']) { ?>
    <li><strong><?php __e('Voucher herbario'); ?>:</strong> <?php echo $avistamento['xermoplasma_herbario'] ? __('Sí') : __('Non'); ?></li>
    <?php } ?>

    <?php if ($avistamento['xermoplasma_herbario_numero']) { ?>
    <li><strong><?php __e('Nº'); ?>:</strong> <?php echo $avistamento['xermoplasma_herbario_numero']; ?></li>
    <?php } ?>

    <?php if ($avistamento['arquivo_gps']) { ?>
    <li><strong><?php __e('Arquivos GPS'); ?>:</strong> <?php echo $Html->a(__('Descargar'), $avistamento['arquivo_gps']); ?></li>
    <?php } ?>

    <?php if ($avistamento['profundidade_auga']) { ?>
    <li><strong><?php __e('Profundidade auga (m)(acuáticas)'); ?>:</strong> <?php echo $avistamento['profundidade_auga']; ?></li>
    <?php } ?>

    <?php if ($avistamento['pendente']) { ?>
    <li><strong><?php __e('Pendente'); ?>:</strong> <?php echo $avistamento['pendente']; ?></li>
    <?php } ?>

    <?php if ($avistamento['orientacion']) { ?>
    <li><strong><?php __e('Orientacion'); ?>:</strong> <?php echo $avistamento['orientacion']; ?></li>
    <?php } ?>

    <?php if ($avistamento['metodo_muestreo']) { ?>
    <li><strong><?php __e('Método de muestreo'); ?>:</strong> <?php __e($avistamento['metodo_muestreo']); ?></li>
    <?php } ?>

    <?php if ($avistamento['adultas_localizadas']) { ?>
    <li><strong><?php __e('Número de plantas adultas localizadas'); ?>:</strong> <?php __e($avistamento['adultas_localizadas']); ?></li>
    <?php } ?>

    <?php if ($avistamento['adultas_mostreadas']) { ?>
    <li><strong><?php __e('Número de plantas adultas mostreadas'); ?>:</strong> <?php __e($avistamento['adultas_mostreadas']); ?></li>
    <?php } ?>

    <?php if ($avistamento['area_mostreada']) { ?>
    <li><strong><?php __e('Área mostreada aprox. (m2)'); ?>:</strong> <?php echo $avistamento['area_mostreada']; ?></li>
    <?php } ?>

    <?php if ($avistamento['area_ocupacion']) { ?>
    <li><strong><?php __e('Área de ocupación aprox. (m2)'); ?>:</strong> <?php echo $avistamento['area_ocupacion']; ?></li>
    <?php } ?>

    <?php if ($avistamento['fenoloxia_froito']) { ?>
    <li><strong><?php __e('Fenoloxía do froito'); ?>:</strong> <?php __e($avistamento['fenoloxia_froito']); ?></li>
    <?php } ?>

    <?php if ($avistamento['procedencia_semente']) { ?>
    <li><strong><?php __e('Procedencia das sementes'); ?>:</strong> <?php __e($avistamento['procedencia_semente']); ?></li>
    <?php } ?>

    <?php if ($avistamento['tipo_vexetacion']) { ?>
    <li><strong><?php __e('Tipo de vexetación'); ?>:</strong> <?php __e($avistamento['tipo_vexetacion']); ?></li>
    <?php } ?>

    <?php if ($avistamento['textura_solo']) { ?>
    <li><strong><?php __e('Textura do solo'); ?>:</strong> <?php __e($avistamento['textura_solo']); ?></li>
    <?php } ?>

    <?php if ($avistamento['observacions_xermoplasma']) { ?>
    <li><strong><?php __e('Observacións'); ?>:</strong> <?php __e($avistamento['observacions_xermoplasma']); ?></li>
    <?php } ?>
</ul>
