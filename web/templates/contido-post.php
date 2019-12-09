<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1><a href="<?php echo path('blogs'); ?>"><?php echo __('Blogs'); ?></a></h1>
            <span>&gt;</span>
            <h2><?php echo $blog['titulo']; ?></h2>

            <?php if ($user && $Acl->check('action', 'blog-editar')) { ?>
            <nav>
                <div class="btn-group">
                    <button class="btn"><i class="icon-plus"></i>
                        <?php __e('Xestión'); ?>
                        <span class="caret"></span>
                    </button>

                    <ul>
                        <li>
                            <a href="<?php echo path('editar', 'post', $blog['url']); ?>">
                                <i class="icon-plus"></i>
                                <?php __e('Novo post'); ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo path('editar', 'post', $blog['url'], $post['url']); ?>">
                                <i class="icon-pencil"></i>
                                <?php __e('Editar post'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <?php } ?>
        </div>
    </header>

    <div class="content wrapper ly-f1">
        <section class="subcontent ly-e1">
            <a class="btn-link" href="<?php echo path('blog', $blog['url']); ?>">
                <i class="icon-arrow-left"></i>
                <?php echo __('Volver á listaxe de posts do blog'); ?>
            </a>

            <article class="post post-permalink">
                <header>
                    <h1><?php echo $post['titulo']; ?></h1>
                </header>

                <footer>
                    <?php 
                    echo ucfirst($Html->time($post['data'], '', 'absolute'));

                    if ($post['comentarios']) {
                        echo (count($post['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($post['comentarios']));
                    }
                    ?>
                </footer>

                <div class="post-intro">
                    <?php echo $post['texto']; ?>                   <?php 
                    echo ucfirst($Html->time($nova['data'], '', 'absolute'));

                    if ($nova['comentarios']) {
                        echo (count($nova['comentarios']) === 1) ? __('un comentario') : __('%s comentarios', count($nova['comentarios']));
                    }
                    ?>
                </div>

                <?php
                if ($imaxes) {
                    $Templates->render('aux-gallery.php', array(
                        'images' => $imaxes,
                        'licenzas' => $licenzas,
                        'hide' => 'templates|img/logo-imaxe.png'
                    ));
                }
                ?>
            </article>

            <?php
            $Templates->render('aux-comentarios.php', array(
                'comentarios' => $comentarios
            ));
            ?>
        </section>

        <?php if ($posts) { ?>
        <section class="subcontent ly-e2">
            <header>
                <h1><?php __e('Últimos posts'); ?></h1>
            </header>

            <div>
                <ul class="listaxe">
                    <?php
                    foreach ($posts as $post) {
                        $Templates->render('aux-post.php', array(
                            'post' => $post
                        ));
                    }
                    ?>
                </ul>
            </div>
        </section>
        <?php } ?>
    </div>
</section>