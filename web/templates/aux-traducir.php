<?php defined('ANS') or die(); ?>

<?php if ($idioma && ($idioma !== LANGUAGE) && (LANGUAGE !== 'en')) {?>
<section class="info">
    <p><?php
    if ($Vars->var['translate']) {
    	__e('Estás visualizando unha versión traducida deste contido. O idioma orixinal desde artigo é %s e podes <a href="%s">consultalo aquí</a>.', __('idioma-'.$idioma), get('translate', ''));
    } else {
    	__e('Este contido dispón dunha versión traducida de xeito automático para %s. Se o desexas podes <a href="%s">consultala aquí</a> pero ten en conta que a calidade pode non ser axeitada.', __('idioma-'.LANGUAGE), get('translate', 'true'));
    }
    ?></p>
</section>
<?php } ?>
