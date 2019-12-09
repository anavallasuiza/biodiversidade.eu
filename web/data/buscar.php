<?php
defined('ANS') or die();

$Vars->var['q'] = strip_tags($Vars->var['q']);
$Vars->var['p'] = intval($Vars->var['p']);

$resultado = false;

if ((isBot() === false) && $Vars->var['q']) {
    $Api = new Google\Api\CustomSearch();
    $Api->setApiKey('AIzaSyCwHPKrjjFOtbUlwR23fJZkCe5_d0KuLM4');
    $Api->setCustomSearchEngineId('013779606645841795402:c-l_2pjyyok');
    $Api->setQuery($Vars->var['q']);

    if ($Vars->var['p']) {
        $Api->setStartIndex(($Vars->var['p'] * 10) + 1);
    }

    $response = $Api->executeRequest();

    if ($response->isSuccess()) {
        $resultado = $response->getData()->getItems();
        $query = $response->getData()->getQueries();

        $pagination = $Db->getPagination(array(
            'limit' => 10,
            'total' => $query['request']->getTotalNumberOfResults(),
            'pagination' => array(
                'map' => 10,
                'page' => $Vars->var['p']
            )
        ));
    }
}

$Html->meta('title', __('Estás buscando por %s', $Vars->var['q']));
