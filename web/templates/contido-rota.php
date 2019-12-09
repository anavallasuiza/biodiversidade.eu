<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1><?php __e('Rotas e espazos'); ?></h1>

            <nav>
                <a class="btn" href="?phpcan_exit_mode=csv">
                    <i class="icon-download"></i> <?php __e('Exportar a CSV'); ?>
                </a>

                <?php if ($user) { ?>

                <a class="btn" href="<?php echo get(array('phpcan_action' => 'vixiar')); ?>">
                    <i class="icon-eye-open"></i> <?php echo $rota['vixiar'] ? __('Deixar de seguir') : __('Vixiar rota'); ?>
                </a>

                <div class="btn-group">
                    <button class="btn">
                        <i class="icon-plus"></i> <?php __e('Xestión'); ?> <span class="caret"></span>
                    </button>

                    <ul>
                        <li>
                            <a href="<?php echo path('editar', 'rota'); ?>">
                                <i class="icon-plus"></i> <?php __e('Nova rota'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo path('editar-grupo', 'avistamento'); ?>">
                                <i class="icon-plus"></i> <?php __e('Novo avistamento') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo path('editar', 'ameaza'); ?>">
                                <i class="icon-plus"></i> <?php __e('Nova ameaza') ?>
                            </a>
                        </li>
                        <li>
                            <?php if ($Acl->check('action', 'rota-editar')) { ?>
                            <a href="<?php echo path('editar', 'rota', $rota['url']); ?>">
                                <i class="icon-pencil"></i> <?php __e('Editar'); ?>
                            </a>
                            <?php } ?>
                        </li>
                        <li>
                    </ul>
                </div>

                <?php } ?>
            </nav>
        </div>
    </header>

    <div class="content wrapper ly-f1">
        <section class="subcontent ly-e1">
            <a class="btn-link" href="javascript:void(0);" onclick="history.back();">
                <i class="icon-arrow-left"></i>
                <?php __e('Voltar'); ?>
            </a>

            <article class="rota rota-permalink">
                <header>
                    <h1>
                        <?php echo $rota['titulo']; ?>

                        <?php if ($rota['validado']) { ?>
                        <span class="estado solucionada"<?php echo $rota['usuarios_validador'] ? (' title="'.__('Validada por %s', $rota['usuarios_validador']['nome']['title']).'"') : ''; ?>><i class="icon-thumbs-up"></i> <?php __e('Validada'); ?></span>
                        <?php } else { ?>
                        <span class="estado activa"><i class="icon-thumbs-down"></i> <?php __e('Non validada'); ?></span>
                        <?php } ?>
                    </h1>
                </header>

                <div class="rota-intro">
                    <?php echo $rota['texto']; ?>
                </div>

                <div class="content-mapa">
                    <div class="mapa" <?php echo $shapes['markers'] ? 'data-markers="' . $shapes['markers']  . '"': ''; ?>  <?php echo $shapes['polygons'] ? 'data-polygons="' . $shapes['polygons'] . '"': ''; ?> <?php echo $shapes['polylines'] ? 'data-polylines="' . $shapes['polylines'] . '"': ''; ?>></div>

                    <div class="hidden">
                        <div id="mapa-toolbar-top" class="toolbar-mapa">
                            <button id="fullscreen" class="btn fullscreen right" type="button">
                                <i class="icon-fullscreen"></i>
                            </button>

                            <button id="zoom-plus" type="button" tabindex="-1" class="btn zoom" type="button">
                                <i class="icon-plus"></i>
                            </button>

                            <button id="zoom-minus" type="button" tabindex="-1" class="btn zoom" type="button">
                                <i class="icon-minus"></i>
                            </button>

                            <label>
                                <div class="desplegable w3" id="map-type" data-value="mapa">
                                    <i class="icon-caret-down right"></i> <span><?php __e('Mapa')?></span>
                                    <ul class="hidden" tabindex="-1">
                                        <li data-value="mapa"><?php __e('Mapa'); ?></li>
                                        <li data-value="satelite"><?php __e('Satelite'); ?></li>
                                        <li data-value="relieve"><?php __e('Relieve'); ?></li>
                                        <li data-value="hybrid"><?php __e('Mapa e satélite'); ?></li>
                                    </ul>
                                </div>
                            </label>

                            <section class="toolbar-options">
                                <label>
                                    <span><?php __e('Ver etiquetas'); ?></span>
                                    <input type="checkbox" id="toggle-labels" checked="checked"/>
                                </label>

                                <label>
                                    <span><?php __e('Ver avistamentos'); ?></span>
                                    <input type="checkbox" id="toggle-avistamentos" checked="checked"/>
                                </label>

                                <label>
                                    <span><?php __e('Filtrar por especie:')?></span>
                                    <select id="filtro-especie" class="w3" data-placeholder="<?php __e('Todas'); ?>">
                                        <option selected></option>
                                        <?php foreach($rota['especies'] as $especie) { ?>
                                        <option value="<?php echo $especie['url']; ?>"><?php echo $especie['nome']; ?></option>
                                        <?php } ?>
                                    </select>
                                </label>
                            </section>
                        </div>
                    </div>

                    <div class="hidden">
                        <div id="mapa-toolbar-bottom">
                            <div id="elevation" data-points="<?php echo $elevation; ?>">
                                <p><?php __e('Perfil de altura'); ?></p>
                                <div class="chart"></div>
                            </div>
                        </div>
                    </div>

                    <div id="infoWindow" class="hidden">
                        <header>
                            <h1></h1>
                        </header>
                        <p></p>
                    </div>

                    <?php
                    /*if ($rota['kml']) {
                        //$rota['kml'] = 'http://biodiversidade.eu/uploads/rotas/kml/993/5422893896-aula-aberta-da-natureza-do-rio-furnia-editado.kml';
                        $rota['kml'] = 'http://biodiversidade.eu/uploads/espazos/kml/894/543c5c825c-terras-do-mino.kml';
                    }*/ ?>

                    <?php /*if ($rota['kml']) { ?>
                    <div class="footer-link">
                        <a id="link-kml" href="<?= $rota['kml']; ?>">
                            <?php __e('Descargar o kml da rota'); ?>
                        </a>
                    </div>
                    <?php }*/ ?>

                    <?php if ($rota['kml']) { ?>
                    <div class="footer-link">
                        <a id="link-kml" href="<?php echo host().fileWeb('uploads|'.$rota['kml']); ?>">
                            <?php __e('Descargar o kml da rota'); ?>
                        </a>
                    </div>
                    <?php } ?>
                </div>

                <div class="tabs tabs-rota">
                    <ul>
                        <?php if ($rota['especies']) { ?>
                        <li><a href="#especies"><?php __e('Especies'); ?></a></li>
                        <?php } if ($avistamentos) { ?>
                        <li><a href="#avistamentos"><?php __e('Avistamentos'); ?></a></li>
                        <?php } ?>
                    </ul>

                    <?php if ($rota['especies']) { ?>
                    <section id="especies" class="listaxe-relacionada especies-relacionadas">
                        <?php foreach($grupos as $grupo) { ?>
                        <h2><?php echo $grupo['nome']; ?></h2>
                        <ul class="rota-especies">
                            <?php foreach($grupo['familias'] as $familia) { ?>
                            <li>
                                <h3><?php echo $familia['nome']; ?></h3>
                                <ul>
                                    <?php foreach($familia['especies'] as $especie) { ?>
                                    <li>
                                        <a href="<?php echo path('especie', $especie['url']); ?>" class="ameazada-nivel-<?php echo $especie['nivel_ameaza']; ?>" title="<?php __e('nivel-ameaza-especies-'.$especie['nivel_ameaza']); ?>">
                                            <i class="nivel-ameaza n<?php echo $especie['nivel_ameaza']; ?>"></i> <?php echo $especie['nome']; ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </section>
                    <?php } ?>

                    <?php if ($avistamentos) { ?>
                    <section id="avistamentos" class="listaxe ">
                        <div id="listado-avistamentos" class="listado-paxinado-cliente">
                            <div class="ly-11">
                                <ul class="listaxe ly-e1">
                                    <?php
                                    foreach ($avistamentos as $num => $avistamento) {
                                        if ($num % 6 != 0) {
                                            echo $Html->each('</ul><ul class="listaxe ly-e2">', 3, $num - 1);
                                        }

                                        echo $Html->each('</ul></div><div class="ly-11 hidden"><ul class="listaxe ly-e1">', 6, $num - 1);

                                        $Templates->render('aux-avistamento.php', array(
                                            'avistamento' => $avistamento,
                                            'mapa' => true
                                        ));
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                        $Templates ->render('aux-paxinacion-cliente.php', array(
                            'length' => ceil(count($avistamentos) / 6),
                            'list' => 'listado-avistamentos'
                        ));
                        ?>
                    </section>
                    <?php } ?>
                </div>

                <?php
                if ($imaxes) {
                    $Templates->render('aux-gallery.php', array(
                        'images' => $imaxes
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

        <section class="ly-e2 sidebar-ficha">
            <?php $Templates->render('aux-traducir.php', array(
                'idioma' => $rota['idioma']
            )); ?>

            <section class="info">
                <header>
                    <?php if ($rota['usuarios_autor']) { ?>
                    <p class="autor">
                        <?php __e('Creado por'); ?>
                        <a href="<?php echo path('perfil', $rota['usuarios_autor']['nome']['url']); ?>">
                            <?php echo $rota['usuarios_autor']['nome']['title'].' '.$rota['usuarios_autor']['apelido1']; ?>
                        </a>
                    </p>
                    <?php } ?>

                    <?php echo ucfirst($Html->time($rota['data']) ); ?>
                </header>

                <section class="validacion">
                    <div>
                        <strong><?php __e('Estado'); ?></strong>
                    </div>

                    <span class="estado <?php echo $rota['validado'] ? 'solucionada' : 'activa' ?>"<?php echo $rota['usuarios_validador'] ? (' title="'.__('Validada por %s', $rota['usuarios_validador']['nome']['title'].' '.$rota['usuarios_validador']['apelido1']).'"') : ''; ?>>
                        <?php if ($rota['validado']) { ?>
                        <i class="icon-thumbs-up"></i> <?php __e('Validado'); ?>
                        <?php } else { ?>
                        <i class="icon-thumbs-down"></i> <?php __e('Non validado'); ?>
                        <?php } ?>
                    </span>
                </section>

                <section>
                    <h1><?php __e('Datos do terreo'); ?></h1>

                    <ul class="rota-informacion">
                        <?php if ($rota['territorios']) { ?>
                        <li>
                            <p><strong><?php echo $rota['concellos']['nome']['title'].' '.$rota['provincias']['nome']['title'] . ' ' . $rota['territorios']['nome']; ?></strong></p>
                        </li>
                        <?php } ?>

                        <?php if ($rota['lugar']) { ?>
                        <li>
                            <p><strong><?php echo $rota['lugar']; ?></strong></p>
                        </li>
                        <?php } ?>

                        <?php if ($rota['dificultade']) { ?>
                        <li><?php __e('Dificultade'); ?>: <strong><?php echo ucfirst($rota['dificultade']); ?></strong></li>
                        <?php } ?>

                        <li><?php __e('Distancia'); ?>: <strong><?php echo (intval($rota['distancia'] > 1000) ? number_format($rota['distancia'] / 1000, 1, ',', '') . ' Km': $rota['distancia'] . ' m'); ?></strong></li>

                        <?php if ($rota['duracion']) { ?>
                        <li><?php __e('Duración'); ?>: <strong><?php echo gmdate('H \h i \m', ($rota['duracion'] * 60)); ?></strong></li>
                        <?php } ?>
                    </ul>
                </section>

                <section class="lenda">
                    <h1><?php __e('Lenda'); ?></h1>

                    <ul>
                        <?php foreach ($tiposPois as $tipo) { ?>
                        <li>
                            <?php
                            echo $Html->img(array(
                                'src' => $tipo['imaxe'],
                                'width' => 16,
                                'height' => 26,
                                'transform' => 'zoomCrop,16,26'
                            )).' <span>'.$tipo['nome'].'</span>';
                            ?>
                        </li>
                        <?php } ?>
                    </ul>
                </section>

                <?php if ($rota['usuarios_vixiar']) { ?>
                <section class="membros">
                    <h1><?php __e('Seguida por'); ?></h1>

                    <ul>
                        <?php foreach ($rota['usuarios_vixiar'] as $usuario) { ?>
                        <li>
                            <a href="<?php echo path('perfil', $usuario['nome']['url']); ?>">
                                <?php
                                echo $Html->img(array(
                                    'src' => $usuario['avatar'],
                                    'alt' => $usuario['nome']['title'],
                                    'width' => 30,
                                    'height' => 30,
                                    'transform' => 'zoomCrop,30,30'
                                ));

                                echo ' '.$usuario['nome']['title'].' '.$usuario['apelido1'];
                                ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </section>
                <?php } ?>

                <?php if ($votar || intval($rota['votos_media'])) { ?>
                <section class="valoracion">
                    <?php if (intval($rota['votos_media'])) { ?>
                    <h1><?php __e('Valoración media'); ?></h1>
                    <p class="puntos"><?php echo str_replace('.', ',', round($rota['votos_media'], 1)); ?></p>
                    <?php } ?>

                    <?php if ($votar) { ?>
                    <h1><?php __e('A túa valoración'); ?></h1>

                    <form method="post">
                        <div class="rating-box">
                            <span class="rating">
                                <input type="radio" class="rating-input" id="rating-input-1-5" name="voto" value="5" />
                                <label for="rating-input-1-5" class="rating-star"></label>
                                <input type="radio" class="rating-input" id="rating-input-1-4" name="voto" value="4" />
                                <label for="rating-input-1-4" class="rating-star"></label>
                                <input type="radio" class="rating-input" id="rating-input-1-3" name="voto" value="3" />
                                <label for="rating-input-1-3" class="rating-star"></label>
                                <input type="radio" class="rating-input" id="rating-input-1-2" name="voto" value="2" />
                                <label for="rating-input-1-2" class="rating-star"></label>
                                <input type="radio" class="rating-input" id="rating-input-1-1" name="voto" value="1" />
                                <label for="rating-input-1-1" class="rating-star"></label>
                            </span>

                            <button type="submit" name="phpcan_action" value="votar" class="btn"><?php __e('Vota'); ?></button>
                        </div>
                    </form>
                    <?php } ?>
                </section>
                <?php } ?>
            </section>
        </section>
    </div>
</section>
