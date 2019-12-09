<?php
defined('ANS') or die();

header("content-type: text/xml; charset=UTF-8");

echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <atom:link href="<?php echo absolutePath(); ?>" rel="self" type="application/rss+xml" />
        <title><?php echo __('Biodiversidade ameazada').' - '.$Html->meta('title', null, false); ?></title>
        <link><?php echo absolutePath(''); ?></link>
        <language>gl</language>
        <generator><?php echo absolutePath(''); ?></generator>
        <description><?php echo __('Biodiversidade ameazada').' - '.$Html->meta('title', null, false); ?></description>

        <pubDate><?php print(date('r', time())); ?></pubDate>

        <?php foreach ($listado as $fila) { ?>
        <item>
            <title><?php echo htmlspecialchars($fila['titulo']); ?></title>
            <guid><?php echo $fila['link']; ?></guid>
            <link><?php echo $fila['link']; ?></link>

            <description>
                <?php echo htmlspecialchars($fila['texto']); ?>
            </description>

            <pubDate><?php echo date('r', strtotime($fila['data_publicacion'])); ?></pubDate>
        </item>
        <?php } ?>
    </channel>
</rss>
