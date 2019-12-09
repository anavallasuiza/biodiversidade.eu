<?php 
defined('ANS') or die(); 

if (!$zonas['nome']) {
    return false;
}
?>
 | <a href="<?php echo path('ameazas') . get($zonas['url']); ?>">
    <?php echo $zonas['nome']; ?>
</a>