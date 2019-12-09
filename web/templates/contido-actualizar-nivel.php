<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Actualizar os niveis do usuario'); ?></h1>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent log">
			<form class="formulario" method="post">
				<?php foreach ($niveis as $nivel) { ?>
                <span style="width: 100px; display: inline-block;">
                    <?php
                        $checked = '';
                        if (in_array($nivel['id'], arrayKeyValues($usuario['roles'], 'id'))) {
                            $checked = 'checked';
                        }

                        echo $Form->checkbox(array(
                            'variable' => $nivel['code'],
                            'value' => $nivel['id'],
                            'label_text' => $nivel['name'],
                            'checked' => $checked
                        ));
                    ?>
                </span>
                <?php } ?>
                <p class="formulario-buttons" style="margin-top: 20px; text-align: right;">
                <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'id' => 'btn-login',
                        'variable' => 'phpcan_action',
                        'value' => 'perfil-gardar-nivel',
                        'class' => 'btn btn-highlight',
                        'text' => ('<i class="icon-save"></i> '.__('Gardar'))
                    ));
                ?>
                </p>
			</form>
		</section>
	</div>
</section>

<script type="text/javascript">

</script>
