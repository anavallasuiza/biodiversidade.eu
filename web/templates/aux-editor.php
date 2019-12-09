<li>
    <article class="equipo">
    	<a href="<?php echo path('perfil', $usuario['nome']['url']); ?>" class="avatar">
    		<?php
    		echo $Html->img(array(
    			'src' => $usuario['avatar'],
    			'alt' => $usuario['nome']['title'],
    			'width' => 100,
    			'height' => 100,
    			'transform' => 'zoomCrop,100,100',
                'class' => 'imaxe-equipo'
    		));
    		?>
    	</a>
        <div>
            <a href="<?php echo path('perfil', $usuario['nome']['url']); ?>">
                <?php echo ' ' . $usuario['nome']['title'] . ' ' . $usuario['apelido1'] . ' ' . $usuario['apelido2']; ?>
            </a>
            <p>
                <a href="mailto:<?php echo $usuario['usuario']; ?>">
                    <?php echo $usuario['usuario']; ?>
                </a>
            </p>
            <p>
                <?php echo $usuario['bio']; ?>
            </p>
        </div>
    </article>
</li>	