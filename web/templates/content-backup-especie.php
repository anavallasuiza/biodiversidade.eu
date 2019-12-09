<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1>
                <a href="<?php echo path('especie', $backup['especie']['url']); ?>">
                    <?php echo $backup['especie']['nome']; ?>
                </a>
            </h1>

            <?php if ($user) { ?>
            <nav>                
                <?php if ($Acl->check('action', 'especie-restaurar')) { ?>
                <a id="boton-restaurar" href="<?php echo path('especie-backup', $backup['id']) . get('phpcan_action', 'especie-restaurar');?>" class="btn btn-highlight">
                    <i class="icon-edit"></i> <?php __e('Restaurar'); ?>
                </a>
                <?php } ?>
                
                <a id="boton-volver" href="<?php echo path('especie', $backup['especie']['url']); ?>" class="btn">
                    <i class="icon-arrow-left"></i> <?php __e('Volver'); ?>
                </a>
            </nav>
            <?php } ?>
        </div>
    </header>
    
    <section class="info-version wrapper">
        <p>
            <i class="icon-info-sign"></i> <?php __e('Versión de <a href="%s">%s</a> de %s', path('perfil', $backup['autor']['nome']['url']), $backup['autor']['nome']['title'].' '.$backup['autor']['apelido1'], $Html->time($backup['date'])); ?>
        </p>
    </section>
    
   <?php include($Templates->file('aux-especie-ficha.php')); ?>
</section>    