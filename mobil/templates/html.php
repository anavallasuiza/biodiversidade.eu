<?php defined ('ANS') or die(); ?>

<!DOCTYPE html>

<html lang="<?php echo $Vars->getLanguage(); ?>" manifest="/offline.appcache">
    <head>
        <?php include($Templates->file('head.php')); ?>
    </head>

    <body>
        <?php include($Templates->file('content')); ?>
    </body>
</html>
