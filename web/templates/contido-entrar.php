<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1><?php __e('Entrar no sitio'); ?></h1>

            <nav>
                <a class="btn" href="<?php echo path('recuperar', 'contrasinal'); ?>"><?php __e('Esquecín o meu contrasinal'); ?></a>
            </nav>
        </div>
    </header>

    <div class="content wrapper">
        <section class="subcontent log">
            <form class="formulario formulario-login" method="post">
                <input type="hidden" name="referer" value="<?php echo $Vars->var['referer'] ?: getenv('HTTP_REFERER'); ?>" />

                <fieldset class="oculto">
                    <input type="email" name="email" value="" class="required" />
                    <input type="text" name="mail" value="" class="required" />
                </fieldset>

                <fieldset class="login-layer ly-11">
                    <p class="formulario-field ly-e1"><?php
                    echo $Form->email(array(
                        'name' => 'usuario',
                        'label_text' => __('O teu email'),
                        'placeholder' => __('exemplo@email.com'),
                        'required' => 'required'
                    ));
                    ?></p>

                    <p class="formulario-field ly-e2"><?php
                    echo $Form->password(array(
                        'name' => 'contrasinal',
                        'label_text' => __('Contrasinal'),
                        'required' => 'required'
                    ));
                    ?></p>

                    <div id="user-login" class="ly-11">
                        <p class="formulario-field ly-e1"><?php
                        echo $Form->checkbox(array(
                            'name' => 'recordar',
                            'label_text' => __('Recordar')
                        ));
                        ?></p>

                        <p class="formulario-field ly-e2"><?php
                        echo $Form->button(array(
                            'type' => 'submit',
                            'id' => 'btn-login',
                            'name' => 'phpcan_action',
                            'value' => 'acceso',
                            'class' => 'btn btn-highlight',
                            'text' => __('Entrar')
                        ));
                        ?>
                        &nbsp;
                        <?php
                        echo $Form->button(array(
                            'id' => 'btn-rexistro',
                            'type' => 'button',
                            'class' => 'btn-link',
                            'text' => __('Ou rexístrate')
                        ));
                        ?></p>
                    </div>
                </fieldset>

                <fieldset id="new-user" class="register-layer hidden ly-11">

                    <p class="formulario-field ly-e1"><?php
                    echo $Form->text(array(
                        'name' => 'nome',
                        'label_text' => __('Danos tamén o teu nome'),
                        'placeholder' => 'Por exemplo: Martín'
                    ));
                    ?></p>

                    <p class="formulario-field ly-e2"><?php
                    echo $Form->password(array(
                        'name' => 'contrasinal_repeat',
                        'label_text' => __('Repite o Contrasinal')
                    ));
                    ?></p>

                    <div>
                        <?php
                        echo $Form->checkbox(array(
                            'id' => 'bt-import-data',
                            'name' => 'importar',
                            'label_text' => __('Teño bloques de datos que quero importar na web.')
                        ));
                        ?>
                    </div>

                    <div class="info-solicitar-importar" style="display: none;">
                        <br /><p><?php __e('Solicitar este permiso aos seguintes expertos'); ?></p>

                        <fieldset>
                            <div class="formulario-field">
                                <ul>
                                    <?php
                                    foreach ($usuarios as $usuario) {
                                        $label = $usuario['nome_completo'];
                                        if ($usuario['especialidade']) {
                                            $label .= ' ('.$usuario['especialidade'].')';
                                        }
                                        echo '<li>'.$Form->checkbox(array(
                                            'name' => 'usuarios_importar[]',
                                            'value' => $usuario['nome']['url'],
                                            'label_text' => $label,
                                            'force' => false
                                        )).'</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </fieldset>

                        <br /><p><?php __e('Por último, indícanos brevemente por qué precisas subir datos de xeito masivo'); ?></p>

                        <fieldset>
                            <div class="formulario-field">
                                <?php
                                echo $Form->textarea(array(
                                    'name' => 'mensaxe'
                                ));
                                ?>
                            </div>
                        </fieldset>
                    </div>

                    <p class="formulario-buttons"><?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'name' => 'phpcan_action',
                        'value' => 'rexistro',
                        'class' => 'btn btn-highlight',
                        'text' => __('Rexistrarme')
                    ));
                    ?></p>
                </fieldset>
            </form>
        </section>
    </div>
</section>

<script type="text/javascript">

</script>
