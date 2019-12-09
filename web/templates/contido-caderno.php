<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1><a href="<?php echo path('proxecto', $caderno['proxectos']['url']); ?>"><?php __e('Proxectos'); ?></a></h1>
            <span>&gt;</span>
            <h2><?php __e('Caderno en %s', $Html->a($proxecto['titulo'], path('proxecto', $proxecto['url']))); ?></h2>

            <nav>
                <div class="btn-group">
                    <button class="btn">
                        <i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
                    </button>

                    <ul>
                        <li>
                            <a href="<?php echo path('editar', 'caderno', $proxecto['url']); ?>">
                                <i class="icon-plus"></i> <?php __e('Novo caderno'); ?>
                            </a>

                            <a href="<?php echo path('editar', 'caderno', $proxecto['url'], $caderno['url']); ?>">
                                <i class="icon-pencil"></i> <?php __e('Editar caderno'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

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
            
            <?php
            if ($imaxes) {
                $Templates->render('aux-gallery.php', array(
                    'images' => $imaxes,
                    'hide' => 'templates|img/logo-imaxe.png'
                ));
            }
            ?>

            <?php
            $Templates->render('aux-comentarios.php', array(
                'comentarios' => $comentarios
            ));
            ?>
        </section>

        <?php if ($backups) { ?>

        <section class="subcontent ly-e2 sidebar-ficha">
            <section class="info">
                <header>
                    <h1><?php __e('Versións anteriores'); ?></h1>
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
