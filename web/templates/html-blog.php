<?php defined ('ANS') or die(); ?>

<!DOCTYPE html>

<html lang="<?php echo $Vars->getLanguage(); ?>">
	<head>
		<?php include($Templates->file('head.php')); ?>
	</head>

	<body>
		<?php include($Templates->file('cabeceira-blog.php')); ?>

		<?php include($Templates->file('contido')); ?>

		<?php include($Templates->file('footer.php')); ?>
	</body>
</html>