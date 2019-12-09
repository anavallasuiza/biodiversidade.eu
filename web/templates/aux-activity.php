<?php
defined('ANS') or die();

if (empty($log)) {
    return '';
}

if (empty($Config->routes2tables)) {
	$Config->load('routes2tables.php');
}

$autor = $Html->a($log['usuarios_autor']['nome']['title'].' '.$log['usuarios_autor']['apelido1'], path('perfil', $log['usuarios_autor']['nome']['url']));

$table1 = $log[$log['related_table']];
$table2 = $log[$log['related_table2']];

$relation1 = $Config->tables2routes[$log['related_table']];
$relation2 = $Config->tables2routes[$log['related_table2']];
?>

<li>
	<article class="activity">
		<?php
		echo $Html->img(array(
			'src' => $log['usuarios_autor']['avatar'],
			'alt' => $log['usuarios_autor']['nome']['title'],
			'width' => 30,
			'height' => 30,
			'transform' => 'zoomCrop,30,30',
			'class' => 'usuario'
		));
		?>

		<div class="activity-content">
			<div class="activity-message">
				<?php
				switch ($log['action']) {
				    case 'comentar':
				        __e('%s fixo un comentario en <a href="%s">%s</a>', $autor, path($relation2['route'], $table2[$relation2['url']]), $table2[$relation2['title']]);
				}
				?>
				<?php echo ucfirst($Html->time($log['date'])); ?>
			</div>
		</div>
	</article>
</li>
