<div data-role="page" id="page-notificar">
    <div data-role="header" data-add-back-btn="true">
        <a href="<?php echo path('') ?>" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Atrás') ?></a>
        <?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
        <a href="<?php echo path('acerca-de'); ?>" data-icon="info" data-iconpos="notext" class="ui-btn-right"><?php echo __('Acerca de') ?></a>
    </div>

    <div data-role="content">
        <div class="content-header">
            <h1><?php echo __('Chamar ás autoridades'); ?></h1>
        </div>

        <div>
            <h2><?php __e('Autoridades estatais'); ?></h2>

            <ul data-role="listview" data-inset="true">
                <?php 
                foreach ($paises as $pais) {
                    $liContent = '';
                    if ($pais['telefono']) {
                        $liContent = '<a href="tel:'.$pais['telefono'].'">'.$pais['nome'].' ('.$pais['telefono'].')</a>';
                    } else {
                        $liContent = $pais['nome'];
                    }
                ?>
                <li><?= $liContent ?></li>
                <?php } ?>
            </ul>
        </div>

        <div>
            <h2><?php __e('Autoridades territorias'); ?></h2>

            <ul data-role="listview" data-inset="true">
                <?php 
                foreach ($territorios as $territorio) {
                    $liContent = '';
                    if ($territorio['telefono']) {
                        $liContent = '<a href="tel:'.$territorio['telefono'].'">'.$territorio['nome'].' ('.$territorio['telefono'].')</a>';
                    } else {
                        $liContent = $territorio['nome'];
                    }
                ?>
                <li><?= $liContent ?></li>
                <?php } ?>
            </ul>
        </div>

        <a href="<?php echo path('notificar-autoridades'); ?>" data-transition="slide" data-role="button" data-theme="a" data-icon="alert"><?php echo __('Enviar notificación (só con conexión)') ?></a>
    </div>
</div>