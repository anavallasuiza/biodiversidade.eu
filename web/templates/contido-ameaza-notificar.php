<?php defined('ANS') or die(); ?>

<section class="content">
	<header>
		<div class="wrapper">
			<h1><?php __e('Notificar ás autoridades'); ?></h1>
		</div>
	</header>

	<div class="content wrapper">
		<section class="subcontent log">
			<form class="formulario" method="post">
                <div class="ly-11">
                    <div class="ly-e1">
                        <h3><?= __('Autoridades estatais'); ?></h3>
                        <?php foreach ($paises as $pais) { ?>
                        <p class="formulario-field">
                            <?php
                                $checked = '';
                                if ($pais['id'] === $ameaza['paises']['id']) {
                                    $checked = 'checked';
                                }
                                $labelText = $pais['nome'];
                                $labelText .= $pais['telefono'] ? ' (<a href="tel:'.$pais['telefono'].'">'.$pais['telefono'].'</a>)' : '';
                                echo $Form->checkbox(array(
                                    'variable' => 'paises['.$pais['id'].']',
                                    'value' => 1,
                                    'label_text' => $labelText,
                                    'checked' => $checked
                                ));
                            ?>
                        </p>
                        <?php } ?>
                    </div>
                    <div class="ly-e2">
                        <h3><?= __('Autoridades territorias'); ?></h3>
                        <?php foreach ($territorios as $territorio) { ?>
                        <p class="formulario-field">
                            <?php
                                $checked = '';
                                if ($territorio['id'] === $ameaza['territorios']['id']) {
                                    $checked = 'checked';
                                }
                                $labelText = $territorio['nome'];
                                $labelText .= $territorio['telefono'] ? ' (<a href="tel:'.$territorio['telefono'].'">'.$territorio['telefono'].'</a>)' : '';
                                echo $Form->checkbox(array(
                                    'variable' => 'territorios['.$territorio['id'].']',
                                    'value' => 1,
                                    'label_text' => $labelText,
                                    'checked' => $checked
                                ));
                            ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
                <p class="formulario-field">
                    <?php
                        echo $Form->textarea(array(
                            'variable' => 'texto',
                            'style' => 'width: 70%;',
                            'label_text' => __('Queres engadir algo?')
                        ));
                    ?>
                </p>
                <p class="formulario-buttons" style="width: 100%; text-align: right;">
                <?php
                    echo $Form->button(array(
                        'type' => 'submit',
                        'id' => 'btn-login',
                        'variable' => 'phpcan_action',
                        'value' => 'ameaza-notificar',
                        'class' => 'btn btn-highlight',
                        'text' => __('Notificar')
                    ));
                ?>
                </p>
			</form>
		</section>
	</div>
</section>

<script type="text/javascript">

</script>
