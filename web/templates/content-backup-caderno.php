<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1>
                <?php __e('Versión anteior de %s', $Html->a($backup['caderno']['titulo'], path('caderno', $proxecto['url'], $backup['caderno']['url']))); ?>
            </h1>

            <?php if ($user) { ?>
            <nav>                
                <?php if ($Acl->check('action', 'caderno-restaurar')) { ?>
                <a id="boton-restaurar" href="<?php echo get('phpcan_action', 'caderno-restaurar'); ?> " class="btn btn-highlight" data-confirm="<?php __e('¿Está seguro de que desexas restaurar esta versión do caderno?'); ?>"
                    <i class="icon-edit"></i> <?php __e('Restaurar'); ?>
                </a>
                <?php } ?>

                <a id="boton-volver" href="<?php echo path('caderno', $proxecto['url'], $backup['caderno']['url']); ?>" class="btn">
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

    <div class="content wrapper ly-f1">
        <section class="subcontent ly-e1">
            <article class="caderno caderno-permalink">
                <div>
                    <header>
                        <h1><?php echo $caderno['titulo']; ?></h1>
                    </header>

                    <div class="caderno-intro">
                        <?php echo $caderno['texto']; ?>
                    </div>

                    <footer>
                        <p><?php __e('Creado %s por %s', $Html->time($caderno['data_alta']), $Html->a($caderno['usuarios_autor']['nome']['title'].' '.$caderno['usuarios_autor']['apelido1'], path('perfil', $caderno['usuarios_autor']['nome']['url']))); ?></p>

                        <?php if ($caderno['data_actualizado']) { ?>
                        <p><?php __e('Actualizado %s', $Html->time($caderno['data_actualizado'])); ?></p>
                        <?php } ?>
                    </footer>
                </div>
            </article>
        </section>

        <?php if ($backups) { ?>

        <section class="subcontent ly-e2 sidebar-ficha">
            <section class="info">
                <header>
                    <h1><?php __e('Outras actualizacións'); ?></h1>
                </header>

                <section class="historico">
                    <div class="listado-cambios">
                        <ul>
                            <?php foreach ($backups as $i => $fila) { ?>
                            <li>
                                <a href="<?php echo path('caderno-backup', $fila['id']); ?>" class="right btn" title="<?php __e('Ver datos'); ?>">
                                    <i class="icon-search"></i>
                                </a>
                                <?php __e('<a href="%s" class="autor">%s</a> as <strong>%s</strong>', path('perfil', $fila['usuarios_autor']['nome']['url']), $fila['usuarios_autor']['nome']['title'].' '.$fila['usuarios_autor']['apelido1'], strtolower($Html->time($fila['date'], '', 'absolute-hour-short'))); ?>
                            </li>
                            
                            <?php echo $Html->each('</ul><ul class="hidden">', 10, $i); ?>
                            
                            <?php } ?>    
                        </ul>
                    </div>

                    <?php if (count($backups) > 10) { ?>
                    <nav>
                        <a id="historico-siguiente" href="#" class="hidden right">
                            <?php __e('Posteriores'); ?>
                            <i class="icon-angle-right"></i>
                        </a>
                        <a id="historico-anterior" href="#" class="left">
                            <i class="icon-angle-left"></i>
                            <?php __e('Anteriores'); ?>
                        </a>
                    </nav>
                    <?php } ?>
                </section>
            </section>
        </section>

        <?php } ?>
    </div>
</section>
