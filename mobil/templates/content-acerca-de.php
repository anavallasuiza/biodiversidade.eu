<div data-role="page" id="page-acerca-de">
    <div data-role="header" data-add-back-btn="true">
        <a href="<?php echo path(''); ?>" data-rel="back" data-icon="arrow-l" class="ui-btn-left"><?php echo __('Atrás') ?></a>
        <?php if ($user) { ?><h1><?php __e('Ola, %s', $user['nome']['title']); ?></h1><?php } else { echo '<h1>&nbsp;</h1>';  } ?>
    </div>

    <div data-role="content">
        <div class="content-header">
            <h1><?php echo __('Información'); ?></h1>
        </div>

        <div>
            <?= __('texto-acerca-de'); ?>
        </div>
    </div>
</div>
