<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1><?php __e('Engadir nova familia'); ?></h1>
        </div>
    </header>

    <div class="content wrapper">
        <section class="subcontent log">
            <form class="formulario form-familia" method="post">
                <div class="formulario-field obrigatorio">
                    <label for="reino"><?php __e('Grupo'); ?></label>

                    <div>
                        <?php
                        echo $Form->select(array(
                            'variable' => 'grupos',
                            'options' => $grupos,
                            'option_value' => 'url',
                            'option_text' => 'nome',
                            'id' => 'grupo-form-familia',
                            'class' => 'w3 select-grupos',
                            'first_option' => __(''),
                            'data-placeholder' => __('Grupos'),
                            'data-child' => '.form-familia select.select-clases',
                            'required' => 'required'
                        ));
                        ?>
                    </div>
                </div>

                <div class="formulario-field obrigatorio">
                    <label for="clase"><?php __e('Clase'); ?></label>

                    <div>
                        <?php
                        echo $Form->select(array(
                            'variable' => 'clases',
                            'id' => 'clase-form-familia',
                            'class' => 'w3 select-clases',
                            'first_option' => __(''),
                            'data-placeholder' => __('Clases'),
                            'required' => 'required',
                            'data-sendatos' => __('O reino escollido non ten clases'),
                            'data-child' => '.form-familia select.select-ordes'
                        ));
                        ?>
                    </div>
                </div>

                <div class="formulario-field obrigatorio">
                    <label for="orde"><?php __e('Ordes'); ?></label>

                    <div>
                        <?php
                        echo $Form->select(array(
                            'variable' => 'ordes',
                            'id' => 'orde-form-familia',
                            'class' => 'w3 select-ordes',
                            'first_option' => __(''),
                            'data-placeholder' => __('Ordes'),
                            'required' => 'required',
                            'data-sendatos' => __('A clase escollida non ten ordes')
                        ));
                        ?>
                    </div>
                </div>

                <div class="formulario-field obrigatorio">
                    <label for="orde"><?php __e('Nome'); ?></label>

                    <div>
                        <?php
                        echo $Form->text(array(
                            'variable' => 'nome',
                            'required' => 'required',
                            'placeholder' => __('Nome da familia')
                        ));
                        ?>
                    </div>
                </div>

                <p class="formulario-buttons" style="margin-top: 20px; text-align: right;">
                <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'variable' => 'phpcan_action',
                        'value' => 'familia-gardar',
                        'class' => 'btn btn-highlight',
                        'text' => ('<i class="icon-save"></i> '.__('Gardar'))
                    ));
                ?>
                </p>
            </form>
        </section>
    </div>
</section>

<?php echo $Html->jsLinks(array(
    'modules/common.ui.js',
    'modules/common.forms.js'
)); ?>