<?php
defined('ANS') or die();

if ($comentario['novas']) {
    $title = $comentario['novas']['titulo'];
    $link = path('nova', $comentario['novas']['url']);
} else if ($comentario['blogs_posts']) {
    $title = $comentario['blogs_posts']['titulo'];
    $link = path('post', $comentario['blogs_posts']['blogs']['url'], $comentario['blogs_posts']['url']);
} else {
    return '';
}
?>

<li>
	<article class="comentario comentario-ultimos">
		<footer class="comentario-info">
            <?php
            __e('<a href="%s" class="comentario-autor">%s</a> en <a href="%s" class="comentario-fonte">%s</a>',
                path('perfil', $comentario['usuarios_autor']['nome']['url']),
                $comentario['usuarios_autor']['nome']['title'].' '.$comentario['usuarios_autor']['apelido1'],
                $link,
                $title
            );

            echo ucfirst($Html->time($comentario['data']));
            ?>
		</footer>

		<div class="comentario-contido">
			<?php echo textCutter($comentario['texto']); ?>
		</div>
	</article>
</li>
