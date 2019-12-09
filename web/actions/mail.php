<?php
defined('ANS') or die();

if (empty($text) && (empty($subject) || empty($body))) {
    return false;
}

$Mail = new \ANS\PHPCan\Mail;

if (is_array($to[0])) {
    foreach ($to as $to_value) {
        if (is_array($to_value)) {
            $Mail->AddAddress($to_value[0], $to_value[1]);
        } else {
            $Mail->AddAddress($to_value);
        }
    }
} else if (is_array($to)) {
    $Mail->AddAddress($to[0], $to[1]);
} else if ($to) {
    $Mail->AddAddress($to);
} else {
    $Mail->AddAddress($Mail->From, $Mail->FromName);
}

if ($cc) {
    if (is_array($cc[0])) {
        foreach ($cc as $cc_value) {
            if (is_array($cc_value)) {
                $Mail->AddCC($cc_value[0], $cc_value[1]);
            } else {
                $Mail->AddCC($cc_value);
            }
        }
    } else if (is_array($cc)) {
        $Mail->AddCC($cc[0], $cc[1]);
    } else {
        $Mail->AddCC($cc);
    }
}

if ($vixiantes) {
    $vixiantes['activo'] = 1;

    $vixiantes = $Db->select(array(
        'table' => 'usuarios',
        'fields' => array('usuario', 'nome', 'notificacions'),
        'conditions' => $vixiantes
    ));

    if ($vixiantes) {
        $bcc = array();

        foreach ($vixiantes as $vixiante) {
            if ($vixiante['notificacions'] && ($user['id'] !== $vixiante['id'])) {
                $bcc[] = array($vixiante['usuario'], $vixiante['nome']['title']);
            }

            if ($log) {
                $Data->execute('actions|sub-logs.php', array(
                    'action' => $log['action'],
                    'table' => $log['table'],
                    'id' => $log['id'],
                    'table2' => $log['table2'],
                    'id2' => $log['id2']
                ));
            }
        }
    }
}

if ($bcc) {
    if (is_array($bcc[0])) {
        foreach ($bcc[0] as $bcc_value) {
            $Mail->AddBCC($bcc_value);
        }
    } else if (is_array($bcc)) {
        $Mail->AddBCC($bcc[0], $bcc[1]);
    } else {
        $Mail->AddBCC($bcc);
    }
}

if ($replyto) {
    if (is_array($replyto)) {
        $Mail->AddReplyTo($replyto[0], $replyto[1]);
    } else {
        $Mail->AddReplyTo($replyto);
    }
}

if (is_array($attachment) && $attachment['tmp_name']) {
    $Mail->AddAttachment($attachment['tmp_name'], $attachment['name']);
}

if ($text) {
    $texto = $Db->select(array(
        'table' => 'textos',
        'fields' => '*',
        'limit' => 1,
        'conditions' => array(
            'url' => $text['code']
        )
    ));

    if (empty($texto)) {
        die($text['code'] ?: 'empty-code');
    }

    unset($text['code']);

    $text['home'] = absolutePath('');

    if (empty($text['link'])) {
        $text['link'] = absolutePath();
    }

    foreach ($text as $key => $value) {
        $texto['texto'] = str_replace('%'.$key, $value, $texto['texto']);
    }

    $Mail->Subject = $texto['titulo'];
    $Mail->Body = $texto['texto'];
} else {
    $Mail->Subject = $subject;
    $Mail->Body = $body;
}

ob_start();

include (filePath('templates|mail.php'));

$Mail->Body = ob_get_contents();

ob_end_clean();

if (defined('DEV') && (DEV === true)) {
    return true;
}

return $Mail->send();
