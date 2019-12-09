<?php defined('ANS') or die(); ?>

<section class="content">
    <header>
        <div class="wrapper">
            <h1><?php echo __('Evento recente'); ?></a></h1>
        </div>
    </header>

    <div class="content wrapper">
        <section class="subcontent">
            <form action="<?php echo path(); ?>" method="post" enctype="multipart/form-data">
                <fieldset>
                    <p>
                        <?php
                        echo $Form->file(array(
                            'variable' => 'arquivo',
                            'id' => 'arquivo',
                            'label_text' => __('Arquivo do Datum')
                        ));
                        ?>
                    </p>
                </fieldset>

                <fieldset>
                    <button type="submit"><?php __e('Comprobar'); ?></button>
                </fieldset>
            </form>

            <?php if ($resultado) { ?>

            <form action="<?php echo path(); ?>" method="post" class="form-datatable">
                <table class="datatable">
                    <thead>
                        <tr>
                            <th><?php __e('Selección'); ?></th>
                            <th><?php __e('Nome'); ?></th>
                            <th><?php __e('Atopado'); ?></th>
                            <th><?php __e('Estado'); ?></th>
                            <th><?php __e('Mensaxe'); ?></th>
                            <th><?php __e('Xa procesada'); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($resultado as $i => $fila) { ?>
                        <tr class="<?php echo $fila['status']; ?>">
                            <td>
                                <?php
                                if ($fila['status'] === 'success') {
                                    echo '<input type="checkbox" id="escollidas_'.$i.'" name="escollidas['.$fila['i'].']" value="'.$i.'" '.(empty($fila['exists']) ? 'checked="checked"' : '').' />';
                                } else if ($fila['status'] === 'warning') {
                                    echo '<input type="radio" id="escollidas_'.$i.'" name="escollidas['.$fila['i'].']" value="'.$i.'" />';
                                } else {
                                    echo '&nbsp;';
                                }
                                ?>
                            </td>
                            <td><label for="escollidas_<?php echo $i; ?>"><?php echo $fila['nome']; ?></label></td>
                            <td><label for="escollidas_<?php echo $i; ?>"><?php echo implode(', ', arrayKeyValues($fila['especie'], 'nome')); ?></label></td>
                            <td><?php echo $fila['status']; ?></td>
                            <td><?php echo $fila['message']; ?></td>
                            <td><?php echo $fila['exists'] ? __('Sí') : __('Non'); ?></td>
                        </tr>
                        <?php } ?>
                </table>

                <textarea name="csv" class="hidden"><?php echo base64_encode(serialize($resultado)); ?></textarea>

                <button type="submit" name="phpcan_action" value="arquivo-gardar"><?php __e('Gardar'); ?></button>
            </form>
            <?php } ?>
        </section>
    </div>
</section>
