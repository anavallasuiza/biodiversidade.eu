<?php defined('ANS') or die(); ?>
<section class="content">
    <header>
        <div class="wrapper">
            <h1><?php echo __('Importar observacións'); ?></h1>
        </div>
    </header>

    <div class="content wrapper">
        <section class="subcontent">
            <form class="formulario-arquivo" action="<?php echo path('importar-observacions', 'previsualizar'); ?>" method="post" enctype="multipart/form-data">
                <fieldset>
                    <p class="alert">
                        <?php __e('Comproba que o arquivo ten formato csv e que cumple cas %sespecificacións necesarias%s para facer a importación. Podes descargar un exemplo %saquí%s', 
                                  '<a href="' . path('info', 'importacion') .'">', '</a>', 
                                  '<a href="' . fileWeb('templates|img/csv-example-' . $Vars->getLanguage() . '.csv') . '">', '</a>');;
                        ?>
                    </p>

                    <p>
                    	<input type="file" name="arquivo" id="arquivo" accept="text/csv" required/>
                    </p>

                    <button type="submit" class="btn">
                    	<?php __e('Comprobar'); ?>
                    </button>
                </fieldset>
            </form>

        </section>
    </div>
</section>
