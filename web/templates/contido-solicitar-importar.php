<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Solicitar importar bloques de datos'); ?></h1>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent log">
            <?php if ($user['solicita_importar']) { ?>

            <p><?= __('Xa fixeches a túa solicitude e está pendente de ser confirmada.'); ?></p>

            <?php } else { ?>

            <p><?= __('texto-solicitar-importar'); ?></p>

			<form class="formulario" method="post">
				<fieldset>
                    <div class="formulario-field">
                        <?php
                        echo $Form->checkbox(array(
                            'name' => 'importar',
                            'label_text' => __('Teño bloques de datos que quero importar na web.'),
                            'required' => 'required'
                        ));
                        ?>
                    </div>
                </fieldset>

                <br /><p><?php __e('Para os seguintes grupos taxonómicos'); ?></p>

                <fieldset>
                    <div class="formulario-field">
                        <ul>
                            <?php
                            foreach ($grupos as $grupo) {
                                echo '<li>'.$Form->checkbox(array(
                                    'name' => 'grupos[]',
                                    'value' => $grupo['nome'],
                                    'label_text' => $grupo['nome'],
                                    'force' => false
                                )).'</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </fieldset>

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
                                    'name' => 'usuarios[]',
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
                            'name' => 'mensaxe',
                            'required' => 'required'
                        ));
                        ?>
                    </div>
                </fieldset>

                <fieldset class="formulario-field">
					<p class="formulario-buttons"><?php
    					echo $Form->button(array(
    						'type' => 'submit',
    						'name' => 'phpcan_action',
    						'value' => 'solicitar-importar',
    						'class' => 'btn btn-highlight',
    						'text' => __('Solicitar')
    					));
					?></p>
				</fieldset>
			</form>
            <?php } ?>
		</section>
	</div>
</section>

<script type="text/javascript">

</script>
