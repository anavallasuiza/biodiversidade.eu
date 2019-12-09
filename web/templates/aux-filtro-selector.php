<?php defined('ANS') or die(); ?>

<?php foreach ($grupos as $grupo) { ?>
<li class="<?php echo $clases ? 'selected': '';?>">
	<span class="grupo <?php echo $clases ? '': 'request';?> <?php echo $datos['grupos'][$grupo['id']] || $grupo['avistamentos'] ? 'con-avistamientos' : ''; ?>" data-codigo="<?php echo $grupo['url']; ?>" data-url="<?php echo path('get-listado-clases') . ($catalogo ? get('catalogo', 1) : ''); ?>">
		<i class="<?php echo $clases ? 'icon-caret-down': 'icon-caret-right';?>"></i> <?php echo $grupo['nome']; ?>
	</span>
	<ul>
        <?php 
        foreach ($clases as $clase) { 
            if ($clase['grupos']['id'] !== $grupo['id']) {
                continue;
            }
        ?>
        <li class="selected">
            <span class="clases <?php echo $datos['clases'][$clase['id']] ? 'con-avistamientos' : ''; ?>" data-codigo="<?php echo $clase['url']; ?>" data-url="<?php echo path('get-listado-ordes'); ?>">
                <i class="icon-caret-down"></i> <?php echo $clase['nome']; ?>
            </span>
            <ul>
                <?php 
                foreach ($ordes as $orde) { 
                    if ($orde['clases']['id'] !== $clase['id']) {
                        continue;
                    }
                ?>
                <li class="selected">
                    <span class="ordes <?php echo $datos['ordes'][$orde['id']] ? 'con-avistamientos' : ''; ?>" data-codigo="<?php echo $orde['url']; ?>" data-url="<?php echo path('get-listado-familias'); ?>">
                        <i class="icon-caret-down"></i> <?php echo $orde['nome']; ?>
                    </span>
                    <ul>
                        <?php 
                        foreach ($familias as $familia) { 
                            if ($familia['ordes']['id'] !== $orde['id']) {
                                continue;
                            }
                        ?>
                        <li class="selected">
                            <?php if ($catalogo) { ?>
                            <a class="todas" href="<?php echo path('catalogo', $familia['url']); ?>">
                                <?php __e('Ver todas'); ?>
                            </a>
                            <?php } ?>
                            <span class="familias <?php echo $datos['familias'][$familia['id']] ? 'con-avistamientos' : ''; ?>" data-codigo="<?php echo $familia['url']; ?>" data-name="<?php echo $familia['nome']; ?>" data-url="<?php echo path('get-listado-xeneros'); ?>">
                                <i class="icon-caret-down"></i> <?php echo $familia['nome']; ?>
                            </span>
                            <ul>
                                <?php 
                                foreach ($xeneros as $xenero) { 
                                    if ($xenero['familias']['id'] !== $familia['id']) {
                                        continue;
                                    }
                                ?>
                                <li class="selected">
                                    <span class="xeneros <?php echo $datos['xeneros'][$xenero['id']] ? 'con-avistamientos' : ''; ?> <?php echo $catalogo ? 'custom': ''; ?>" data-codigo="<?php echo $xenero['url']; ?>" data-name="<?php echo $xenero['nome']; ?>" data-url="<?php echo path('get-listado-especies'); ?>">
                                        <?php if (!$catalogo) { ?>
                                        <i class="icon-caret-down"></i> 
                                        <?php } ?>
                                        <?php echo $xenero['nome']; ?>
                                    </span>
                                    <?php if (!$catalogo) { ?>
                                    <ul class="especies-menu-selector">
                                        <?php 
                                        foreach($especies as $especie) { 
                                            if ($especie['xeneros']['id'] !== $xenero['id']) {
                                                continue;
                                            }
                                        ?>
                                        <li class="<?php echo $especie['subespecie'] || $especie['variedade'] ? 'subespecie': ''; ?>" data-sinonimos="<?php echo $especie['sinonimos']; ?>" data-comun="<?php echo $especie['nome_comun']; ?>">											
                                            <span class="especie <?php echo $datos['especies'][$especie['id']] ? 'con-avistamientos' : 'sen-avistamentos'; ?> <?php echo $especie['validada'] ? 'validada' : 'sen-validar'; ?> <?php echo $especie['protexida'] ? 'protexida' : 'sen-protexer'; ?>" data-codigo="<?php echo $especie['url']; ?>" data-name="<?php echo $especie['nome']; ?>">
                                                <?php if($datos['especies'][$especie['id']]) { ?>
                                                <i class="icon-circle-blank especie-con-datos"></i> 
                                                <?php } ?>
                                                <i class="right icon-remove"></i>
                                                <?php echo $especie['nome']; ?>
                                            </span>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                    <?php } ?>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </li>
        <?php } ?>
    </ul>
</li>
<?php } ?>