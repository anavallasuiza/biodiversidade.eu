<?php defined('ANS') or die(); ?>

<?php
if (!$Vars->messageExists()) {
    return '';
}

$message = (array) $Vars->message();

$type = $Vars->messageType();
$type = ($type === 'ko') ? 'danger' : (($type === 'ok') ? 'success' : $type);
?>

<div class="alert alert-<?php echo $type; ?>">
    <div class="wrapper">
        <?php echo implode('</p><p>', $message); ?>
    </div>
</div>
