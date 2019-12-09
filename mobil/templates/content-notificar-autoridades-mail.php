<div data-role="page" id="page-notificar-mail">
    <div data-role="header" data-add-back-btn="true">
        <a href="<?php echo path('').'#page-notificar'; ?>" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Atrás') ?></a>
        <?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
        <a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
    </div>

    <div data-role="content">
        <div class="content-header">
            <h1><?php echo __('Enviar notificación'); ?></h1>
        </div>

        <?php
        if ($Vars->messageExists()) {
            $message = (array) $Vars->message();

            $type = $Vars->messageType();
            $type = ($type === 'ko') ? 'danger' : (($type === 'ok') ? 'success' : $type);
        ?>
        <div class="alert alert-<?php echo $type; ?>">
            <div class="wrapper">
                <?php echo implode('</p><p>', $message); ?>
            </div>
        </div>
        <?php 
        }
        ?>

        <div>
            <form data-role="content" class="edicion-nota" action="" method="post">
                <fieldset data-role="controlgroup">
                    <legend><?php __e('Autoridades estatais'); ?></legend>
                    <?php 
                    foreach ($paises as $pais) {
                        $labelContent = $pais['nome'];

                        echo $Form->checkbox(array(
                            'variable' => 'paises['.$pais['id'].']',
                            'value' => 1,
                            'label_text' => $labelContent
                        ));
                    }
                    ?>
                </fieldset>

                <fieldset data-role="controlgroup">
                    <legend><?php __e('Autoridades territorias'); ?></legend>
                    <?php 
                    foreach ($territorios as $territorio) {
                        $labelContent = $territorio['nome'];

                        echo $Form->checkbox(array(
                            'variable' => 'territorios['.$territorio['id'].']',
                            'value' => 1,
                            'label_text' => $labelContent
                        ));
                    }
                    ?>
                </fieldset>

                <?php
                    echo $Form->textarea(array(
                        'variable' => 'texto',
                        'required' => 'required',
                        'label_text' => __('Qué queres comunicar?')
                    ));
                ?>

                <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'variable' => 'phpcan_action',
                        'value' => 'ameaza-notificar',
                        'data-role' => 'button',
                        'data-theme' => 'a',
                        'data-icon' => 'forward',
                        'data-iconpos' => 'right',
                        'text' => __('Notificar')
                    ));
                ?>
            </form>
        </div>
    </div>
</div>