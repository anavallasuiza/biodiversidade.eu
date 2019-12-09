<?php defined('ANS') or die(); ?>

<?php foreach ($items as $item) { ?>
<li data-sinonimos="<?php echo $item['sinonimos']; ?>" data-comun="<?php echo $item['nome_comun']; ?>">
	
	<span class="especie <?php echo $item['avistamentos'] ? 'con-avistamientos' : ''; ?> <?php echo $item['protexida'] ? 'protexida' : 'sen-protexer'; ?> <?php echo $item['validada'] ? 'validada' : 'sen-validar'; ?>" data-codigo="<?php echo $item['url']; ?>" data-name="<?php echo $item['nome']; ?>">
        <?php if($item['avistamentos']) { ?>
        <i class="icon-circle-blank especie-con-datos"></i> 
        <?php } ?>
		<i class="right icon-remove"></i> <?php echo $item['nome']; ?>
	</span>
</li>
<?php } ?>