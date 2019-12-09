<?php defined('ANS') or die(); ?>

<h1><?php __e('Imaxes (pode levar varias)'); ?></h1>

<div class="formulario-field-bloque">
	<label><?php __e('Sube unha imaxe'); ?></label>

	<div>
		<ul class="imagelist">
			<?php foreach ($imaxes as $imaxe) { ?>
			<li data-id="<?php echo $imaxe['id']; ?>">
				<figure>
					<img data-src="<?php echo $Html->imgSrc($imaxe['imaxe']); ?>" src="<?php echo $Html->imgSrc($imaxe['imaxe'], 'zoomCrop,120,100'); ?>"/>
				</figure>

				<select name="imaxes[licenza][]" class="w2" data-placeholder="<?php __e('Licenza'); ?>" required="required">
					<?php foreach ($licenzas as $licenza) { ?> 
					<option value="<?php echo $licenza; ?>" <?php echo ($licenza === $imaxe['licenza']) ? 'selected' : ''; ?>>
						<?php echo $licenza; ?>
					</option>
					<?php } ?>
				</select>

				<?php if ($imaxes_tipos) { ?>
				<select name="imaxes[tipo][]" class="" data-placeholder="<?php __e('Tipo'); ?>">
					<?php foreach ($imaxes_tipos as $tipo) { ?> 
					<option value="<?php echo $tipo['url']; ?>" <?php echo ($imaxe['imaxes_tipos']['url'] === $tipo['url']) ? 'selected' : ''; ?>>
						<?php echo $tipo['nome']; ?>
					</option>
					<?php } ?>
				</select>
				<?php } ?>

                <label class="check-imaxe-principal">
                    <input type="hidden" name="imaxes[main][]" value="<?php echo $imaxe['portada'] ? '1': '0'; ?>"/>
                    <input type="radio" name="main-radio" <?php echo $imaxe['portada'] ? 'checked="checked"': ''; ?>/>
                    <small><?php __e('Imaxe principal'); ?></small>
                </label>

                <label>
                	<input type="text" name="imaxes[autor][]" value="<?php echo $imaxe['autor']; ?>" />
                </label>

				<input type="hidden" name="imaxes[id][]" value="<?php echo $imaxe['id']; ?>" placeholder="<?php __e('Autor'); ?>" />
			</li>
			<?php } ?>

			<li class="imagelist-new">
				<?php
				echo $Form->select($licenzas, array(
					'variable' => 'imaxes[licenza][]',
					'class' => 'licenza',
					'option_text_as_value' => true,
					'first_option' => '',
					'data-placeholder' => __('Licenza'),
				));

				if ($imaxes_tipos) {
					echo $Form->select($imaxes_tipos, array(
						'variable' => 'imaxes[tipo][]',
						'option_value' => 'url',
						'option_text' => 'nome',
						'first_option' => '',
						'data-placeholder' => __('Tipo')
					));
				}
				?>

                <label class="check-imaxe-principal">
                    <input type="radio" name="main-radio"/>
                    <input type="hidden" name="imaxes[main][]" value="0"/>
                    <small><?php __e('Imaxe principal'); ?></small>
                </label>

                <label>
                	<input type="text" name="imaxes[autor][]" value="" placeholder="<?php __e('Autor'); ?>" />
                </label>
            </li>
		</ul>

		<div class="imaxes-eliminadas hidden">
			<p><?php __e('Imaxes eliminadas'); ?></p>

			<ul class="imagelist-removed"></ul>
		</div>
	</div>
</div>
