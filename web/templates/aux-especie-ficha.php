<?php defined('ANS') or die(); ?>

<div class="content wrapper ly-f1">
    <section class="subcontent ly-e1">
        <a class="btn-link" href="javascript:void(0);" onclick="history.back();">
            <i class="icon-arrow-left"></i>
            <?php echo __('Voltar'); ?>
        </a>

        <article class="especie especie-permalink">
            <header>
                <h1 id="nome-especie">
                    <span>
                        <span id="nome_cientifico" class="texto texto-nome" data-required="required"  data-placeholder="<?php __e('Nome da especie'); ?>">
                            <?php echo $especie['nome_cientifico']; ?>
                        </span>
                        <span id="autor" class="texto texto-autor" data-required="required" data-placeholder="<?php __e('Autor especie'); ?>">
                            <?php echo $especie['autor']; ?>
                        </span>
                    </span>

                    <span class="<?php echo !$especie['subespecie'] ? 'hidden': ''; ?>">
                        <span class="texto-tipo-sub" data-name="<?php __e('subsp.') ?>"><?php echo $especie['subespecie'] ? __('subsp.') : ''; ?></span>
                        <span id="subespecie" class="texto texto-sub" data-placeholder="<?php __e('Subespecie'); ?>">
                            <?php echo $especie['subespecie']; ?>
                        </span>
                        <span id="subespecie_autor" class="texto texto-sub-autor" data-placeholder="<?php __e('Autor subespecie'); ?>">
                            <?php echo $especie['subespecie_autor']; ?>
                        </span>
                    </span>

                    <span class="<?php echo !$especie['variedade'] ? 'hidden': ''; ?>">
                        <span class="texto-tipo-sub" data-name="<?php __e('var.') ?>"><?php echo $especie['variedade'] ? __('var.') : ''; ?></span>
                        <span id="variedade" class="texto texto-var" data-placeholder="<?php __e('Variedade'); ?>">
                            <?php echo $especie['variedade']; ?>
                        </span>
                        <span id="variedade_autor" class="texto texto-var-autor" data-placeholder="<?php __e('Autor variedade'); ?>">
                            <?php echo $especie['variedade_autor']; ?>
                        </span>
                    </span>
                </h1>

                <div id="info-nome" class="info-nome">
                    <i class="icon-info-sign"></i> <?php __e('Se editas o nome, recorda pasar o anterior nome para o campo de sinónimos.'); ?>
                </div>
            </header>

            <div id="text-tree">
                <ul class="especie-clasificacion">
                    <li>
                        <div class="nome-categoria">
                            <i class="icon-caret-right"></i> <?php echo $especie['grupos']['nome']; ?>
                        </div>
                        <div class="select-categoria">
                            <?php
                            echo $Form->select(array(
                                'id' => 'grupos',
                                'variable' => 'grupos',
                                'options' => $grupos,
                                'option_value' => 'url',
                                'option_text' => 'nome',
                                'class' => 'w2 select-grupos',
                                'first_option' => __(''),
                                'data-placeholder' => __('Grupo'),
                                'data-child' => 'select.select-clases',
                                'required' => 'required',
                                'data-selected' => $especie['grupos']['url']
                            ));
                            ?>
                        </div>
                    </li>
                    <li>
                        <div class="nome-categoria">
                            <i class="icon-caret-right"></i> <?php echo $especie['clases']['nome']; ?>
                        </div>
                        <div class="select-categoria">
                            <?php
                            echo $Form->select(array(
                                'id' => 'clases',
                                'variable' => 'clases',
                                'class' => 'w2 select-clases',
                                'first_option' => __(''),
                                'data-placeholder' => __('Clase'),
                                'required' => 'required',
                                'data-sendatos' => __('O reino escollido non ten clases'),
                                'disabled' => 'disabled',
                                'data-child' => 'select.select-ordes',
                                'data-selected' => $especie['clases']['url']
                            ));
                            ?>
                        </div>
                    </li>
                    <li>
                        <div class="nome-categoria">
                            <i class="icon-caret-right"></i> <?php echo $especie['ordes']['nome']; ?>
                        </div>
                        <div class="select-categoria">
                            <?php
                            echo $Form->select(array(
                                'id' => 'ordes',
                                'variable' => 'ordes',
                                'class' => 'w2 select-ordes',
                                'first_option' => __(''),
                                'data-placeholder' => __('Orde'),
                                'required' => 'required',
                                'data-sendatos' => __('A clase escollida non ten ordes'),
                                'disabled' => 'disabled',
                                'data-child' => 'select.select-familias',
                                'data-selected' => $especie['ordes']['url']
                            ));
                            ?>
                        </div>
                    </li>
                    <li>
                        <div class="nome-categoria">
                            <i class="icon-caret-right"></i> <?php echo $especie['familias']['nome']; ?>
                        </div>
                        <div class="select-categoria">
                            <?php
                            echo $Form->select(array(
                                'id' => 'familias',
                                'variable' => 'familias',
                                'class' => 'w2 select-familias',
                                'first_option' => __(''),
                                'data-placeholder' => __('Familia'),
                                'required' => 'required',
                                'data-sendatos' => __('A orde escollida non ten familias'),
                                'disabled' => 'disabled',
                                'data-child' => 'select.select-xeneros',
                                'data-selected' => $especie['familias']['url']
                            ));
                            ?>
                        </div>
                    </li>
                    <li>
                        <div class="nome-categoria">
                            <i class="icon-caret-right"></i> <?php echo $especie['xeneros']['nome']; ?>
                        </div>
                        <div class="select-categoria">
                            <?php
                            echo $Form->select(array(
                                'id' => 'xeneros',
                                'variable' => 'xeneros',
                                'class' => 'w2 select-xeneros',
                                'first_option' => __(''),
                                'data-placeholder' => __('Xénero'),
                                'required' => 'required',
                                'data-sendatos' => __('A familia escollida non ten xéneros'),
                                'disabled' => 'disabled',
                                'data-selected' => $especie['xeneros']['url']
                            ));
                            ?>
                        </div>
                    </li>
                </ul>
            </div>

            <?php
            $Templates->render('aux-gallery.php', array(
                'images' => $imaxes,
                'licenzas' => $licenzas,
                'imaxes_tipos' => $imaxes_tipos,
                'hide' => 'templates|img/logo-imaxe.png',
                'editable' => true,
                'editables' => $especie['imaxes'],
                'comparador' => array('especie' => $especie['url'], 'reino' => $especie['reinos']['url'])
            ));
            ?>

            <div class="tabs">
                <ul>
                    <li>
                        <a href="#tab-ficha"><?php echo __('Ficha'); ?></a>
                    </li>
                    <li>
                        <a href="#tab-conservacion"><?php echo __('Conservación'); ?></a>
                    </li>
                    <li>
                        <a href="#tab-observacions"><?php echo __('Observacións'); ?></a>
                    </li>
                    <li>
                        <a href="#tab-bibliografia"><?php echo __('Bibliografía'); ?></a>
                    </li>
                </ul>

                <section id="tab-ficha" class="height ">

                    <h1><?php __e('Descrición e bioloxía'); ?></h1>
                    <div id="descricion" class="texto-ficha">
                        <?php echo $especie['descricion']; ?>
                    </div>

                    <h1><?php __e('Nº de cromosomas'); ?></h1>
                    <div id="cromosomas" class="texto-ficha">
                        <?php echo $especie['cromosomas']; ?>
                    </div>

                    <h1><?php __e('Fenoloxía'); ?></h1>
                    <div id="fenoloxia" class="texto-ficha">
                        <?php echo $especie['fenoloxia']; ?>
                    </div>

                    <h1><?php __e('Distribución'); ?></h1>
                    <div id="distribucion" class="texto-ficha">
                        <?php echo $especie['distribucion']; ?>
                    </div>

                    <h1><?php __e('Hábitat'); ?></h1>
                    <div id="habitat" class="texto-ficha">
                        <?php echo $especie['habitat']; ?>
                    </div>

                </section>

                <section id="tab-conservacion" class="height">

                    <h1><?php __e('Poboación'); ?></h1>
                    <div id="poboacion" class="texto-ficha">
                        <?php echo $especie['poboacion']; ?>
                    </div>

                    <h1><?php __e('Factores de ameaza'); ?></h1>
                    <div id="ameazas" class="texto-ficha">
                        <?php echo $especie['ameazas']; ?>
                    </div>

                    <h1><?php __e('Medidas de conservación'); ?></h1>
                    <div id="conservacion" class="texto-ficha">
                        <?php echo $especie['conservacion']; ?>
                    </div>

                </section>

                <section id="tab-observacions" class="height">

                    <h1><?php __e('Observacions'); ?></h1>
                    <div id="observacions" class="texto-ficha">
                        <?php echo $especie['observacions']; ?>
                    </div>

                    <h1><?php __e('Agradecementos'); ?></h1>
                    <div id="agradecementos" class="texto-ficha">
                        <?php echo $especie['agradecementos']; ?>
                    </div>

                </section>

                <section id="tab-bibliografia" class="height">
                    <h1><?php __e('Bibliografía'); ?></h1>

                    <div id="bibliografia" class="texto-ficha">
                        <?php echo $especie['bibliografia']; ?>
                    </div>
                </section>
            </div>

            <nav class="edicion">
                <?php
                echo $Form->button(array(
                    'type' => 'button',
                    'name' => 'boton-editar-especie',
                    'class' => 'btn boton-cancelar',
                    'text' => __('Cancelar a edición')
                ));

                echo $Form->button(array(
                    'type' => 'submit',
                    'name' => 'phpcan_action',
                    'value' => 'especie-gardar',
                    'class' => 'btn btn-highlight boton-gardar',
                    'text' => ('<i class="icon-save"></i> '.__('Gardar os cambios'))
                ));
                ?>
            </nav>

            <?php if ($avistamentos || $rotas || $novas || $ameazas) { ?>
            <div class="tabs">
                <ul>
                    <?php if ($avistamentos) { ?><li><a href="#avistamentos"><?php echo __('Avistamentos'); ?></a></li><?php } ?>
                    <?php if ($rotas) { ?><li><a href="#rotas"><?php echo __('Rotas e espazos'); ?></a></li><?php } ?>
                    <?php if ($novas) { ?><li><a href="#novas"><?php echo __('Novas'); ?></a></li><?php } ?>
                    <?php if ($ameazas) { ?><li><a href="#ameazas"><?php echo __('Ameazas'); ?></a></li><?php } ?>
                </ul>

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

                <?php if ($rotas) { ?>
                <section id="rotas" class="listaxe">
                    <div id="listado-rotas" class="listado-paxinado-cliente">
                        <div class="ly-11">
                            <ul class="listaxe ly-e1">
                                <?php
                                foreach ($rotas as $num => $rota) {
                                    if ($num % 6 != 0) {
                                        echo $Html->each('</ul><ul class="listaxe ly-e2">', 3, $num - 1);
                                    }

                                    echo $Html->each('</ul></div><div class="ly-11 hidden"><ul class="listaxe ly-e1">', 6, $num - 1);

                                    $Templates->render('aux-rota.php', array(
                                        'rota' => $rota
                                    ));
                                }
                                ?>
                            </ul>

                        </div>
                    </div>
                    <?php
                    $Templates ->render('aux-paxinacion-cliente.php', array(
                        'length' => ceil(count($rotas) / 6),
                        'list' => 'listado-rotas'
                    ));
                    ?>
                </section>					
                <?php } ?>

                <?php if ($novas) { ?>
                <section id="novas">
                    <div id="listado-novas" class="listado-paxinado-cliente">
                        <div class="ly-11">
                            <ul class="listaxe ly-e1">
                                <?php
                                foreach ($novas as $num => $nova) {
                                    if ($num % 6 != 0) {
                                        echo $Html->each('</ul><ul class="listaxe ly-e2">', 3, $num - 1);
                                    }

                                    echo $Html->each('</ul></div><div class="ly-11 hidden"><ul class="listaxe ly-e1">', 6, $num - 1);

                                    $Templates->render('aux-nova.php', array(
                                        'nova' => $nova
                                    ));
                                }
                                ?>
                            </ul>      
                        </div>
                    </div>
                    <?php
                    $Templates->render('aux-paxinacion.php', array(
                        'length' => ceil(count($novas) / 6),
                        'list' => 'listado-novas'
                    ));
                    ?>
                </section>
                <?php } ?>

                <?php if ($ameazas) { ?>
                <section id="ameazas" class="listaxe">
                    <div id="listado-ameazas" class="listado-paxinado-cliente">
                        <div class="ly-11">
                            <ul class="listaxe ly-e1">
                                <?php
                                foreach ($ameazas as $num => $ameaza) {
                                    if ($num % 6 != 0) {
                                        echo $Html->each('</ul><ul class="listaxe ly-e2">', 3, $num - 1);
                                    }

                                    echo $Html->each('</ul></div><div class="ly-11 hidden"><ul class="listaxe ly-e1">', 6, $num - 1);

                                    $Templates->render('aux-ameaza.php', array(
                                        'ameaza' => $ameaza
                                    ));
                                }
                                ?>
                            </ul>

                        </div>
                    </div>
                    <?php
                    $Templates->render('aux-paxinacion.php', array(
                        'length' => ceil(count($ameazas) / 6),
                        'list' => 'listado-ameazas'
                    ));
                    ?>
                </section>
                <?php } ?>		
            </div>
            <?php } ?>
        </article>
    </section>

    <section class="ly-e2 sidebar-ficha">
        <?php $Templates->render('aux-traducir.php', array(
            'idioma' => $especie['idioma']
        )); ?>

        <div class="social-share">
            <a href="<?php echo shareTwitter($especie['nome_cientifico']); ?>" class="social-share-twitter" rel="nofollow" target="_blank"></a>
            <a href="<?php echo shareFacebook(); ?>" class="social-share-facebook" rel="nofollow" target="_blank"></a>
        </div>

        <section class="info">
            <header id="autor-ameaza" class="<?php echo ($especie['usuarios_autor'] || $especie['nivel_ameaza']) ? '' : 'hidden'; ?>">
                <p class="autor">
                    <?php if (strtotime($especie['data_alta']) > 0) {
                        __e('Creada por <a href="%s">%s</a> as %s', path('perfil', $especie['usuarios_autor']['nome']['url']), $especie['usuarios_autor']['nome']['title'].' '.$especie['usuarios_autor']['apelido1'], $Html->time($especie['data_alta'], '', 'absolute-hour-short'));
                    } else {
                        __e('Creada por <a href="%s">%s</a>', path('perfil', $especie['usuarios_autor']['nome']['url']), $especie['usuarios_autor']['nome']['title'].' '.$especie['usuarios_autor']['apelido1']);
                    }
                    ?>
                </p>

                <div class="nivel-ameaza n<?php echo $especie['nivel_ameaza'] ?: '0'; ?>">
                    <div><strong><?php __e('Nivel de amenaza'); ?></strong></div>

                    <div class="texto-ameaza">
                        <?php
                        if ($especie['nivel_ameaza']) {
                            echo '<i class="nivel-ameaza n'.$especie['nivel_ameaza'].'"></i>';
                            echo ' '.__('nivel-ameaza-especies-'.$especie['nivel_ameaza']);
                        } else {
                            __e('Especie non ameazada');
                        }
                        ?>
                    </div>

                    <?php if ($especie['aloctona']) { ?>
                    <div class="texto-ameaza">
                        <?php __e('Especie alóctona'); ?>
                    </div>
                    <?php } ?>

                    <?php if ($especie['invasora']) { ?>
                    <div class="texto-ameaza">
                        <?php __e('Especie invasora'); ?>
                    </div>
                    <?php } ?>

                    <div class="select-ameaza">
                        <select id="nivel-de-ameaza" class="nivel-ameaza w3" name="especies[nivel_ameaza]" data-placeholder="<?php __e('Nivel de ameaza'); ?>">
                            <option></option>

                            <?php foreach (getNiveisAmeazas('especies') as $numero => $nivel) { ?>
                            <option value="<?php echo $numero; ?>" <?php echo ($especie['nivel_ameaza'] == $numero) ? 'selected' : ''; ?>>
                                <?php echo $nivel; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="proteccion <?php echo $especie['protexida'] ? '': 'empty';?>">
                    <div class="texto-proteccions" >
                        <strong><?php echo $especie['protexida'] ? __('Especie protexida') : ''; ?></strong>
                    </div>

                    <div class="input-proteccions">
                        <input type="checkbox" id="protexida" name="especies[protexida]" <?php echo $especie['protexida'] ? 'checked="checked"' : ''; ?> value="<?php __e('Especie protexida'); ?>"/> 
                        <label for="protexida"><?php __e('Especie protexida')?></label>
                    </div>

                    <div class="input-proteccions">
                        <input type="checkbox" id="aloctona" name="especies[aloctona]" <?php echo $especie['aloctona'] ? 'checked="checked"' : ''; ?> value="1"/> 
                        <label for="aloctona"><?php __e('Especie alóctona')?></label>
                    </div>

                    <div class="input-proteccions">
                        <input type="checkbox" id="invasora" name="especies[invasora]" <?php echo $especie['invasora'] ? 'checked="checked"' : ''; ?> value="1"/> 
                        <label for="invasora"><?php __e('Especie invasora')?></label>
                    </div>

                    <div class="catalogos-proteccion <?php echo $especie['proteccions_tipos'] ? '': 'empty';?>">
                        <?php __e('Pertence a:'); ?>

                        <div id="proteccions-tipos-texto">
                            <?php echo join(', ', arrayKeyValues($especie['proteccions_tipos'], 'nome')); ?>
                        </div>

                        <div id="proteccions-tipos-select">
                            <select id="proteccions" multiple="multiple" data-placeholder="<?php __e('Tipo de protección'); ?>">
                                <?php foreach($proteccions_tipos as $proteccion) { ?>
                                <option value="<?php echo $proteccion['id']; ?>" <?php 
                                    echo in_array($proteccion['id'], arrayKeyValues($especie['proteccions_tipos'], 'id')) ? 'selected': ''; ?>>
                                    <?php echo $proteccion['nome']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </header>

            <section class="validacion">
                <div><strong><?php __e('Estado'); ?></strong></div>
                <div class="texto-validacion">
                    <span class="estado solucionada <?php echo $especie['validada'] ? '' : 'hidden'; ?>"<?php echo $especie['usuarios_validador'] ? (' title="'.__('Validada por %s', $especie['usuarios_validador']['nome']['title']).'"') : ''; ?>>
                        <i class="icon-thumbs-up"></i> <?php __e('Validada'); ?>
                    </span>
                    <span class="estado activa <?php echo $especie['validada'] ? 'hidden' : ''; ?>">
                        <i class="icon-thumbs-down"></i> <?php __e('Non validada'); ?>
                    </span>
                </div>
                <?php if ($Acl->check('action', 'especie-validar')) { ?>
                <div class="datos-validados hidden">
                    <label>
                        <input type="checkbox" name="especies[validada]" value="1" <?php echo $especie['validada'] ? 'checked': '';?>/>
                        <?php __e('Datos validados'); ?>
                    </label>
                </div>
                <?php } ?>
            </section>

            <section id="external" class="lsid <?php echo !$especie['lsid_name'] ? 'empty': ''; ?>">
                <div><strong><?php __e('Id externo'); ?></strong></div>
                <div id="lsid_name">
                    <?php echo $especie['lsid_name']; ?>
                </div>
            </section>

            <?php if ($especieTipo || $relacionadas) { ?>
            <section id="especies-relacionadas" class="especies-relacionadas">
                <div><strong><?php __e('Especies relacionadas'); ?></strong></div>
                <div id="especie-tipo">
                    <?php if ($especieTipo) { ?>
                    <a href="<?php echo path('especie', $especieTipo['url']); ?>">
                        <?php echo $especieTipo['nome'];?>
                    </a>
                    <?php } ?>

                    <?php foreach($relacionadas as $relacionada) {?>
                    <a href="<?php echo path('especie', $relacionada['url']); ?>">
                        <?php echo $relacionada['nome']; ?>
                    </a>
                    <?php }?>
                </div>
            </section>
            <?php } ?>

            <section id="extra-nomes" class="extra-nomes <?php echo !$especie['nome_comun'] && !$especie['sinonimos'] ? 'empty': ''; ?>">
                <h1><?php echo __('Outros nomes'); ?></h1>
                <ul>
                    <li class="item-nomes-comuns <?php echo !$especie['nome_comun'] ? 'empty': ''; ?>">
                        <?php __e('Outros nomes comúns'); ?>:
                        <div><?php echo $especie['nome_comun']; ?></div>
                    </li>
                    <li class="item-sinonimos <?php echo !$especie['sinonimos'] ? 'empty': ''; ?>">
                        <?php __e('Sinónimos'); ?>:
                        <div><?php echo $especie['sinonimos']; ?></div>
                    </li>
                </ul>
            </section>

            <?php if ($usuarios) { ?>
            <section class="membros">
                <h1><?php __e('Seguida por'); ?></h1>

                <ul>
                    <?php foreach ($usuarios as $usuario) { ?>
                    <li>
                        <a href="<?php echo path('perfil', $usuario['nome']['url']); ?>">
                            <?php
                            echo $Html->img(array(
                                'src' => $usuario['avatar'],
                                'alt' => $usuario['nome']['title'],
                                'width' => 20,
                                'height' => 20,
                                'transform' => 'zoomCrop,20,20'
                            ));

                            echo ' '.$usuario['nome']['title'].' '.$usuario['apelido1'];
                            ?>
                        </a>
                    </li>					
                    <?php } ?>
                </ul>
            </section>
            <?php } ?>

            <?php if ($backups) { ?>
            <section class="historico">
                <h1><?php __e('Histórico de cambios'); ?></h1>
                <div class="listado-cambios">
                    <ul>
                        <?php foreach ($backups as $i => $fila) { ?>
                        <li>
                            <a href="<?php echo path('especie-backup', $fila['id']); ?>" class="right btn" title="<?php __e('Ver datos'); ?>">
                                <i class="icon-search"></i>
                            </a>
                            <?php __e('<a href="%s" class="autor">%s</a> as <strong>%s</strong>', path('perfil', $fila['usuarios_autor']['nome']['url']), $fila['usuarios_autor']['nome']['title'], strtolower($Html->time($fila['date'], '', 'absolute-hour-short'))); ?>
                        </li>

                        <?php echo $Html->each('</ul><ul class="hidden">', 10, $i); ?>

                        <?php } ?>    
                    </ul>
                </div>
                <?php if (count($backups) > 10) { ?>
                <nav>
                    <a id="historico-siguiente" href="#" class="hidden right">
                        <?php __e('Posteriores'); ?> <i class="icon-angle-right"></i>
                    </a>
                    <a id="historico-anterior" href="#" class="left">
                        <i class="icon-angle-left"></i> <?php __e('Anteriores'); ?>
                    </a>
                </nav>
                <?php } ?>
            </section>
            <?php } ?>
        </section>

        <?php if ($avistamentos) { ?>
        <div class="enlace-mapa">
            <a class="btn" href="<?php echo path('catalogo', 'mapa', $especie['url']); ?>">
                <i class="icon-map-marker"></i> <?php __e('Ver mapa de avistamentos'); ?>
            </a>
        </div>
        <?php } ?>
    </section>

    <div class="overlay-save hidden">
        <div class="overlay-background"></div>
        <div class="overlay-text">
            <i class="icon-spin icon-spinner"></i> <?php __e('Gardando'); ?>
        </div>
    </div>
</div>