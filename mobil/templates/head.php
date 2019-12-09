<?php
defined('ANS') or die();

$meta_title = $Html->meta('title', null, false).' - '.__('Biodiversidade ameazada');
$meta_description = textCutter($Html->meta('description', null, false), 200) ?: __('meta-description');
$meta_image = array();

if ($images = $Html->meta('image', null, false)) {
    $images = (array)$images;

    foreach ($images as $image) {
        $meta_image[] = 'http://'.SERVER_NAME.$image;
    }
}

$meta_image[] = 'http://'.SERVER_NAME.fileWeb('templates|img/logo-imaxe.png');
?>

<title><?php echo $meta_title; ?></title>

<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<meta property="og:site_name" content="<?php __e('Biodiversidade ameazada'); ?>" />
<meta property="og:url" content="<?= here(); ?>" />
<meta property="og:locale" content="es_ES" />

<!-- Site info -->
<meta name="author" content="A navalla suíza - http://anavallasuiza.com" />
<meta name="generator" content="phpCan <?php echo PHPCAN_VERSION; ?>" />

<?php
echo "\n".'<meta name="title" content="'.$meta_title.'" />';
echo "\n".'<meta property="og:title" content="'.$meta_title.'" />';

echo "\n".'<meta name="description" content="'.$meta_description.'" />';
echo "\n".'<meta property="og:description" content="'.$meta_description.'" />'."\n";

foreach ($meta_image as $image) {
    echo "\n".'<meta name="image" content="'.$image.'" />';
    echo "\n".'<meta property="og:image" content="'.$image.'" />';
    echo "\n".'<link rel="image_src" href="'.$image.'" />'."\n";
}
?>

<!-- Google Chrome Frame -->
<!--[if lt IE 9]>
<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1">
<![endif]-->

<!-- Mobile devices -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Favicon -->
<link rel="shortcut icon" href="http://<?php echo SERVER_NAME.BASE_WWW; ?>favicon.ico">
<link rel="apple-touch-icon" href="http://<?php echo SERVER_NAME.BASE_WWW; ?>apple-icon-touch.png">

<!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Css files -->
<?php echo $Html->cssLinks($Config->templates['css']); ?>

<!-- Javascript files -->
<?php echo $Html->jsLinks($Config->templates['js']);

