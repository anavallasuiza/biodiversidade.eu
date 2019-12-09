<?php
defined('ANS') or die();

if (empty($_POST)) {
	header(getenv('SERVER_PROTOCOL').' 404 Not Found');
	die();
}

$dummy = $Vars->var['email'];

if ($dummy || stristr($Vars->var['feedback']['texto'], '<a ')) {
	header(getenv('SERVER_PROTOCOL').' 404 Not Found');
	die();
}

$body = '<div>Email: '.$Vars->var['feedback']['email'].'</div><div>'.$Vars->var['feedback']['texto'].'</div>';

$server = $_SERVER;

if ($server['HTTP_REFERER']) {
	$body .= '<div>Dende: <a href="' . $server['HTTP_REFERER'] . '">' . $server['HTTP_REFERER'] . '</a></div>';
}

ksort($server);

$body .= '<ul>';

foreach ($server as $name => $value) {
	$body .= '<li><strong>'.$name.'</strong>'. $value.'</li>';
}

$body .= '</ul>';

$Config->load('mail.php');

$result = $Data->execute('actions|mail.php', array(
	'to' => array(array($Config->mail['from'], 'feedback@anavallasuiza.com')),
	'replyto' => $Vars->var['email'],
	'subject' => __('Feedback de Biodiversidade: %s', $Vars->var['tipo']),
	'body' => $body
));

if (!$result) {
	header(getenv('SERVER_PROTOCOL').' 500 Internal Server Error');
}

die();
