<?php defined('ANS') or die(); ?>
<section class="content">
    <header>
        <div class="wrapper">
            <h1><?php echo __('Resultado da importación'); ?></h1>
        </div>
    </header>

    <div class="content wrapper">
        <section class="subcontent resultado-importacion">
            
            <p>
                <?php __e('O proceso de importación deu de alta %s observacións e %s puntos.', $resultado['avistamentos'], $resultado['puntos']); ?>
            </p>

            <?php if ($resultado && $resultado['erros']) { ?>
            <p class="erros">
                <i class="icon-warning-sign"></i> <?php __e('Sen embargo producíronse %s erros:', count($resultado['erros'])); ?>
            </p>
            <ul class="listado-erros">
                <?php foreach($resultado['erros'] as $code => $erro) { ?>
                <li>
                    <p>
                        <?php __e('Código da especie: %s', $code) ?>
                    </p>
                    <p>
                        <strong><?php __e('Erro: '); ?></strong>
                    </p>
                    <p>
                        <?php echo is_array($erro) ? join('</p><p>', $erro) : $erro; ?>
                    </p>
                </li>
                <?php } ?>
            </ul>

            <?php } ?>
            <nav>
                <a href="<?php echo path('importar-observacions'); ?>">
                    <i class="icon-angle-left"></i> <?php __e('Importar máis datos'); ?>
                </a>
            </nav>
        </section>
    </div>
</section>
