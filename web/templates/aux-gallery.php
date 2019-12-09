<?php
defined('ANS') or die();

$cnt = 0;

if ($images) {
    array_walk($images, function (&$value) use ($hide) {
        $value = is_string($value) ? array('imaxe' => $value) : $value;
        $value = ($value['imaxe'] === $hide) ? null : $value;
    });

    $images = array_values(array_filter($images));

    $rel = $rel ?: 'galeria';
    $cnt = count($images);
}

?>
<div class="media <?php echo !$images ? 'empty' : ''; ?>">
	<div class="galeria">
		<div class="main-imaxe">
			<figure>
                <?php if ($images) { ?>
				<a href="<?php echo $Html->imgSrc($images[0]['imaxe'], 'resize, 800'); ?>" class="gallery" rel="<?php echo $rel;?>">
					<?php
					echo $Html->img(array(
						'src' => $images[0]['imaxe'],
						'transform' => 'resize, 576,400'
					));
					?>

				</a>
                <?php } ?>

                <small>
                    <?php if ($images[0]['related']) { ?>
                        <a href="<?php echo path($images[0]['related'], $images[0]['related_url']); ?>">
                            <?php echo ucfirst($images[0]['related']); ?>
                        </a>
                        <?php } ?>
                    <?php echo $images[0]['autor'] ? $images[0]['autor'] . ' - ' : ''; ?><?php echo $images[0]['licenza']; ?>
                </small>
			</figure>
		</div>

        <?php if ($cnt > 1) { ?>
		<div class="thumbnails">
			<ul id="carousel" class="elastislide-list">
				<?php for ($i = 0; $i < $cnt; $i++) { ?>
				<li>
					<a href="<?php echo $Html->imgSrc($images[$i]['imaxe'], 'resize, 800'); ?>" class="gallery" data-recorte="<?php echo $Html->imgSrc($images[$i]['imaxe'], 'resize, 600,400'); ?>" rel="<?php echo $rel;?>">
						<?php
						echo $Html->img(array(
							'src' => $images[$i]['imaxe'],
							'transform' => 'resizeCrop, 300,200'

						));
						?>
					</a>

                    <small>
                        <?php if ($images[$i]['related']) { ?>
                        <a href="<?php echo path($images[$i]['related'], $images[$i]['related_url']); ?>">
                            <?php echo ucfirst($images[$i]['related']); ?>
                        </a>
                        <?php } ?>
                        <?php echo $images[$i]['autor'] ? $images[$i]['autor'] . ' - ' : ''; ?><?php echo $images[$i]['licenza']; ?>
                    </small>
				</li>
				<?php } ?>
			</ul>
		</div>
        <?php } ?>

        <?php if ($cnt > 0 && $comparador) { ?>
        <div class="link-comparador">
            <a href="<?php echo path('comparador', $comparador['reino']). get('especies[]', $comparador['especie']); ?>" class="btn">
                <i class="icon-picture"></i> <?php __e('Comparar imaxes con outras especies'); ?>
            </a>
        </div>
        <?php } ?>
	</div>

    <?php if ($editable) { ?>
    <ul class="imagelist">
        <?php foreach ($editables as $imaxe) { ?>
        <li data-id="<?php echo $imaxe['id']; ?>">
            <figure>
                <img data-src="<?php echo $Html->imgSrc($imaxe['imaxe']); ?>" src="<?php echo $Html->imgSrc($imaxe['imaxe'], 'zoomCrop,120,100'); ?>"/>
            </figure>
            <select name="imaxes[licenza][]" class="w2" data-placeholder="<?php __e('Licenza'); ?>" required="required">
                <?php foreach($licenzas as $licenza) { ?>
                <option value="<?php echo $licenza; ?>" <?php echo $licenza === $imaxe['licenza'] ? 'selected' : ''; ?>>
                    <?php echo $licenza; ?>
                </option>
                <?php } ?>
            </select>

            <?php if ($imaxes_tipos) {  ?>
            <select name="imaxes[tipo][]" class="" data-placeholder="<?php __e('Tipo'); ?>">
                <?php foreach($imaxes_tipos as $tipo) { ?>
                <option value="<?php echo $tipo['url']; ?>" <?php echo $imaxe['imaxes_tipos']['url'] === $tipo['url'] ? 'selected' : ''; ?>>
                    <?php echo $tipo['nome']; ?>
                </option>
                <?php } ?>
            </select>
            <?php } ?>

            <label class="check-imaxe-principal">
                <input type="radio" name="imaxesmain" value="1" <?php echo $imaxe['portada'] ? 'checked="checked"': ''; ?>/>
                <input type="hidden" name="imaxes[main][]" value=""/>
                <small><?php __e('Imaxe principal'); ?></small>
            </label>

            <input type="hidden" name="imaxes[id][]" value="<?php echo $imaxe['id']; ?>"/>
        </li>
        <?php } ?>

        <li class="imagelist-new">
            <?php
            echo $Form->select($licenzas, array(
                'variable' => 'imaxes[licenza][]',
                'class' => 'licenza',
                'option_text_as_value' => true,
                'first_option' => __(''),
                'disabled' => 'disabled',
                'required' => 'required',
                'data-placeholder' => __('Licenza'),
            ));

            if ($imaxes_tipos) {
                echo $Form->select($imaxes_tipos, array(
                    'variable' => 'imaxes[tipo][]',
                    'option_value' => 'url',
                    'option_text' => 'nome',
                    'first_option' => __(''),
                    'disabled' => 'disabled',
                    'data-placeholder' => __('Tipo')
                ));
            }
            ?>
            <label class="check-imaxe-principal">
                <input type="radio" name="imaxesmain" disabled="disabled" value="1"/>
                <input type="hidden" name="imaxes[main][]" disabled="disabled" value=""/>
                <small><?php __e('Imaxe principal'); ?></small>
            </label>
        </li>
    </ul>

    <?php } ?>
</div>