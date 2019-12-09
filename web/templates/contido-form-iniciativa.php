<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1><a href="<?php echo path('ameazas'); ?>"><?php echo __('Ameazas e iniciativas de conservación'); ?></a></h1>
            <span>&gt;</span>
            <h2><?php echo $iniciativa ? __('Edición da iniciativa') : __('Nova iniciativa') ?></h2>
        </div>
    </header>
    
    <?php $Templates->render('aux-form-validation.php'); ?>

    <div class="content wrapper">
        <form action="<?php echo path(); ?>" class="formulario-pisos custom-validation form-iniciativa" <?php echo $shapes['markers'] ? 'data-markers="' . $shapes['markers']  . '"': ''; ?>  <?php echo $shapes['polygons'] ? 'data-polygons="' . $shapes['polygons'] . '"': ''; ?> <?php echo $shapes['polylines'] ? 'data-polylines="' . $shapes['polylines'] . '"': ''; ?> method="post" enctype="multipart/form-data">
            <div class="ly-f1">
                <fieldset class="ly-e1">
                    <h1><?php echo __('Iniciativa') ?></h1>

                    <div class="formulario-field obrigatorio">
                        <label for="titulo"><?php __e('Nome iniciativa'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'titulo',
                                'variable' => 'iniciativas[titulo]',
                                'placeholder' => _('Nome da iniciativa'),
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field obrigatorio">
                        <label for="descricion"><?php __e('Descrición da accións'); ?></label>
                        <div>
                            <?php
                            echo $Form->textarea(array(
                                'id' => 'descricion',
                                'variable' => 'iniciativas[texto]',
                                'required' => 'required'
                            ));
                            ?>
                        </div>
                    </div>

                    <div id="iniciativa-territorio" class="formulario-field">
                        <label for="territorio"><?php __e('Teritorio'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($territorios, array(
                                'id' => 'territorio',
                                'variable' => 'territorio',
                                'option_value' => 'url',
                                'option_text' => 'nome',
                                'class' => 'selector-despregable select-territorios',
                                'first_option' => '',
                                'data-placeholder' => __('Selección obrigatoria'),
                                'data-selected' => $Vars->var['territorio'],
                                'data-child' => 'select.select-provincias',
                                'data-anchor' => 'iniciativa-territorio'
                            ));
                            ?>
                        </div>
                    </div>

                    <div id="iniciativa-provincia" class="formulario-field">
                        <label for="provincia"><?php __e('Provincia'); ?></label>
                        <div>
                            <?php
                            echo $Form->select(array(
                                'id' => 'provincia',
                                'variable' => 'provincia',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'class' => 'selector-despregable select-provincias',
                                'first_option' => '',
                                'data-placeholder' => __('Selección obrigatoria'),
                                'data-selected' => $Vars->var['provincia'],
                                'data-child' => 'select.select-concellos',
                                'data-anchor' => 'iniciativa-provincia'
                            ));
                            ?>
                        </div>
                    </div>

                    <div id="iniciativa-concellos" class="formulario-field">
                        <label for="concello"><?php __e('Concello'); ?></label>
                        <div>
                            <?php
                            echo $Form->select(array(
                                'id' => 'concello',
                                'variable' => 'concello',
                                'option_value' => 'url',
                                'option_title' => 'nome',
                                'class' => 'selector-despregable select-concellos',
                                'first_option' => '',
                                'data-placeholder' => __('Selección obrigatoria'),
                                'data-selected' => $Vars->var['concello'],
                                'data-anchor' => 'iniciativa-concellos'
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <label for="lugar"><?php __e('Lugar'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'lugar',
                                'variable' => 'iniciativas[lugar]',
                                'placeholder' => __('Lugar da iniciativa')
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field-bloque">
                        <p class="label"><?php __e('Xeolocalización'); ?></p>

                        <div class="bloque-left">
                            <div class="content-mapa">
                                <div class="mapa"></div>
                            </div>

                            <div id="mapa-toolbar-top" class="toolbar-mapa">                            
                                <button id="fullscreen" class="btn fullscreen right" type="button">
                                    <i class="icon-fullscreen"></i>
                                </button>
                                
                                <button id="zoom-plus" type="button" tabindex="-1" class="btn zoom">
                                    <i class="icon-plus"></i>
                                </button>
                                
                                <button id="zoom-minus" type="button" tabindex="-1" class="btn zoom">
                                    <i class="icon-minus"></i>
                                </button>
                                
                                <label>
                                    <div class="desplegable w3" id="map-type" data-value="mapa">
                                        <i class="icon-caret-down right"></i> <span><?php __e('Mapa')?></span>
                                        <ul class="hidden" tabindex="-1">
                                            <li data-value="mapa"><?php __e('Mapa'); ?></li>
                                            <li data-value="satelite"><?php __e('Satelite'); ?></li>
                                            <li data-value="relieve"><?php __e('Relieve'); ?></li>
                                        </ul>
                                    </div>
                                </label>
                                
                                <button id="xeolocalizame" class="btn xeo" type="button">
                                    <i class="icon-screenshot"></i>
                                </button>
                                
                                <span class="separator"></span>
                                
                                <section class="toolbar-options drawing-options">
                                    <button id="drawing-default" class="btn pressed" type="button">
                                        <i class="icon-hand-up"></i> <?php __e('Seleccionar'); ?>
                                    </button>
                                    <button id="drawing-delete" class="btn" disabled type="button">
                                        <i class="icon-remove"></i> <?php __e('Borrar'); ?>
                                    </button>
                                    <button id="drawing-marker" class="btn" type="button">
                                        <?php __e('Punto'); ?>
                                    </button>
                                    <button id="drawing-polygon" class="btn" type="button">
                                        <?php __e('Polígono'); ?>
                                    </button>
                                </section>
                            </div>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <label for="kml"><?php __e('Arquivo kml'); ?></label>
                        <div>
                            <?php
                            echo $Form->file(array(
                                'variable' => 'iniciativas[kml]',
                                'id' => 'kml',
                                'data-text' => basename($iniciativa['kml']),
                                'data-value' => ($iniciativa['kml'] ? fileWeb('uploads|'  .$iniciativa['kml'], false, true) : '')
                            ));
                            ?>
                        </div>
                    </div>
                </fieldset>

                <section class="erros-axuda ly-e2">
                    <p><strong><?php __e('Este contido será gardado en idioma %s', __('idioma-'.LANGUAGE)); ?></strong></p>
                    <br />                
                    <p><?php echo __('axuda-formulario-iniciativas-contido'); ?></p>
                </section>
            </div>

            <div class="ly-f1">
                <fieldset class="ly-e1">
                    <h1><?php echo __('Información adicional') ?></h1>

                    <div class="formulario-field obrigatorio">
                        <label for="tipo"><?php __e('Tipo de iniciativa'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($tipos, array(
                                'id' => 'tipo',
                                'variable' => 'iniciativas_tipos[id]',
                                'option_value' => 'id',
                                'option_text' => 'nome',
                                'multiple' => 'multiple',
                                'placeholder' => __('Tipo de iniciativa'),
                                'class' => 'selector-despregable',
                                /*'data-values' => $Vars->var['iniciativas_tipos']['id'],*/
                                'data-values' => preg_replace('/"/i', '\'', json_encode($Vars->var['iniciativas_tipos'])),
                                'required' => true
                            ));
                            ?>
                        </div>
                    </div>    

                    <div class="formulario-field">
                        <label for="especie"><?php __e('Especies'); ?></label>
                        <div>
                            <?php
                            echo $Form->text(array(
                                'id' => 'especie',
                                'variable' => 'especies[url]',
                                'placeholder' => __('Especies afectadas'),
                                'class' => 'listaxe-especies',
                                'data-multiple' => 'multiple',
                                'data-values' => preg_replace('/"/i', '\'', json_encode($Vars->var['especies']))
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <label for="proxectos"><?php __e('Proxectos'); ?></label>
                        <div>
                            <?php
                            echo $Form->select($proxectos, array(
                                'id' => 'proxectos',
                                'variable' => 'proxectos[id]',
                                'option_value' => 'id',
                                'option_text' => 'titulo',
                                'multiple' => 'multiple',
                                'data-placeholder' => __('Relacionar con algún dos meus proxectos'),
                                'class' => 'selector-despregable',
                                'data-values' => preg_replace('/"/i', '\'', json_encode($Vars->var['proxectos']))
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="formulario-field">
                        <label for="estado"><?php __e('O estado actual'); ?></label>
                        <div>
                            <?php
                            echo $Form->select(array(
                                'options' => array(
                                    1 => __('Activa'),
                                    0 => __('Desactiva')
                                ),
                                'id' => 'estado',
                                'variable' => 'iniciativas[estado]',
                                'class' => 'selector-despregable'
                            ));
                            ?>
                        </div>
                    </div>
                </fieldset>

                <section class="erros-axuda ly-e2">
                    <p><?php echo __('axuda-formulario-iniciativas-informacion-adicional'); ?></p>
                </section>
            </div>

            <div class="ly-f1">
                <fieldset class="ly-e1">
                    <?php
                    $Templates->render('aux-form-imaxes.php', array(
                        'imaxes' => $imaxes,
                        'licenzas' => $licenzas,
                        'imaxes_tipos' => $imaxes_tipos
                    ));
                    ?>
                </fieldset>

                <section class="erros-axuda ly-e2">
                    <p><?php __e('axuda-formulario-iniciativas-imaxes'); ?></p>
                </section>
            </div>

            <fieldset class="footer">
                <p class="formulario-buttons">
                    <a href="<?php echo $iniciativa['url'] ? path('iniciativa', $iniciativa['url']) : (path('ameazas').'#iniciativas'); ?>" class="btn right">
                        <i class="icon-remove"></i> <?php __e('Cancelar'); ?>
                    </a>

                    <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'name' => 'phpcan_action',
                        'value' => 'iniciativa-gardar',
                        'class' => 'btn btn-highlight',
                        'text' => ('<i class="icon-save"></i> '.__('Gardar'))
                    ));
                    ?>
                </p>
            </fieldset>
        </form>
    </div>
</section>
