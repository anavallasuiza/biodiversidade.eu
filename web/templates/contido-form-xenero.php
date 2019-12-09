<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1><?php __e('Engadir novo xénero'); ?></h1>
        </div>
    </header>

    <div class="content wrapper">
        <section class="subcontent log">
            <form class="formulario form-xenero" method="post">
                <div class="formulario-field obrigatorio">
                    <label for="reino"><?php __e('Grupo'); ?></label>

                    <div>
                        <?php
                        echo $Form->select(array(
                            'variable' => 'grupos',
                            'options' => $grupos,
                            'option_value' => 'url',
                            'option_text' => 'nome',
                            'id' => 'grupo',
                            'class' => 'w3 select-grupos',
                            'first_option' => __(''),
                            'data-placeholder' => __('Grupos'),
                            'data-child' => '.form-xenero select.select-clases',
                            'required' => 'required',
                            'tabindex' => 1
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
                            'id' => 'clase',
                            'class' => 'w3 select-clases',
                            'first_option' => __(''),
                            'data-placeholder' => __('Clases'),
                            'required' => 'required',
                            'data-sendatos' => __('O reino escollido non ten clases'),
                            'data-child' => '.form-xenero select.select-ordes',
                            'tabindex' => 2
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
                            'id' => 'orde',
                            'class' => 'w3 select-ordes',
                            'first_option' => __(''),
                            'data-placeholder' => __('Ordes'),
                            'required' => 'required',
                            'data-sendatos' => __('A clase escollida non ten ordes'),
                            'data-child' => '.form-xenero select.select-familias',
                            'tabindex' => 3
                        ));
                        ?>
                    </div>
                </div>

                <div class="formulario-field obrigatorio">
                    <label for="familia"><?php __e('Familia'); ?></label>

                    <div>
                        <?php
                        echo $Form->select(array(
                            'variable' => 'familias',
                            'id' => 'familia',
                            'class' => 'w3 select-familias',
                            'first_option' => __(''),
                            'data-placeholder' => __('Familias'),
                            'required' => 'required',
                            'data-sendatos' => __('A orde escollida non ten familias')
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
                            'placeholder' => __('Nome do xénero')
                        ));
                        ?>
                    </div>
                </div>

                <p class="formulario-buttons" style="margin-top: 20px; text-align: right;">
                <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'variable' => 'phpcan_action',
                        'value' => 'xenero-gardar',
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