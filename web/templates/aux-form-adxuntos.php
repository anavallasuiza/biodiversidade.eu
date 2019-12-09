<?php defined('ANS') or die(); ?>

<h1><?php __e('Adxuntos (pode levar varios)'); ?></h1>

<div class="formulario-field-bloque">
    <p class="label"><?php __e('Sube un documento'); ?></p>

    <div class="bloque-left">
        <ul id="listado-adxuntos" class="item-list">
            <?php foreach ($adxuntos as $i => $adxunto) { ?>
            <li>
                <div class="item-content formulario-field">
                    <p><?php
                        echo $Form->file(array(
                            'id' => 'adxuntos-arquivo-<?php echo $i; ?>',
                            'name' => 'adxuntos[arquivo][]',
                            'data-nodelete' => 'true',
                            'data-value' => fileWeb('uploads|'.$adxunto['arquivo']),
                            'data-text' => basename($adxunto['arquivo'])
                        ));
                    ?></p>
    
                    <p>
                        <?php
                        echo $Form->text(array(
                            'id' => 'adxuntos-titulo-<?php echo $i; ?>',
                            'name' => 'adxuntos[titulo][]',
                            'class' => 'w4',
                            'placeholder' => __('Título do documento'),
                            'value' => $adxunto['titulo']
                        ));
                        ?>

                        <button type="button" class="btn item-remove">
                            <?php __e('Borrar'); ?>
                        </button>
                    </p>

                    <input type="hidden" name="adxuntos[borrar][]" value="<?php echo $adxunto['id']; ?>"  class="input-deleted" />
                </div>
            </li>
            <?php } ?>

            <li class="new-item">
                <button type="button" class="btn item-add">
                    <?php __e('Engadir adxunto'); ?>
                </button>

                <div class="item-content formulario-field">
                    <p><?php
                        echo $Form->file(array(
                            'data-anchor' => 'listado-adxuntos',
                            'name' => 'adxuntos[arquivo][]',
                            'data-nodelete' => 'true',
                            'disabled' => 'disabled'
                        ));
                    ?></p>
    
                    <p>
                        <?php
                        echo $Form->text(array(
                            'data-anchor' => 'listado-adxuntos',
                            'name' => 'adxuntos[titulo][]',
                            'class' => 'w4',
                            'placeholder' => __('Título do documento'),
                            'required' => 'required',
                            'disabled' => 'disabled'
                        ));
                        ?>
                        <button type="button" class="btn item-remove"><?php __e('Borrar'); ?></button>
                    </p>
                        
                </div>
            </li>
        </ul>
    </div>
</div>
