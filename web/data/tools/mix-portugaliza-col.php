<?php
defined('ANS') or die();

exit;

function debug ($message, $title, $line, $tag = 'h4') {
    echo '<'.$tag.'>'.$line.' - '.$title.'</'.$tag.'>';
    print_r($message);
}

function addRow ($csv, $r, $accepted = 0, $synonym = array()) {
    $csv[] = $accepted;
    $csv[] = implode('|', $synonym);

    if (isset($r['_species_details']['taxon_id'])) {
        $csv[] = $r['_species_details']['taxon_id'];
    } else if ($r['_species_details']) {
        $csv[] = implode('|', arrayKeyValues($r['_species_details'], 'taxon_id'));
    } else {
        $csv[] = '';
    }

    $fila = str_putcsv($csv, ';', '"');

    //debug($fila, 'ROW', __LINE__);

    return $fila;
}

echo '<pre>';
echo '<h1>Started at '.date('Y-m-d H:i:s').'</h1>';

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

$ipni = BASE_PATH.'tmp/IPNI-Portugaliza-Combined.txt';

// Output
$new = BASE_PATH.'tmp/IPNI-Portugaliza-COL.txt';

if (!is_file($ipni)) {
    die('Some file not found');
}

$COL = new \ANS\PHPCan\Data\Db('Db');
$COL->setConnection('col2011ac');

$all = array();
$report = array(
    'found' => 0,
    'notfound' => array(),
    'duplicated' => array()
);

$ipni = file($ipni, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$header = str_getcsv(array_shift($ipni), ';', '"');

$header = array_merge($header, array(
    'ACCEPTED NAMES',
    'SYNONYM NAMES',
    'COL TAXON ID'
));

// Use fields ESPECIE (H 7), GENERO (F 5) e INFRA (K 10) as key

$total = count($ipni);

foreach ($ipni as $i => $fila) {
    echo '<h3>Processing row '.$i.' of '.$total.'</h3>';

    if (empty($fila)) {
        continue;
    }

    ob_flush();
    flush();

    $csv_accepted = 0;
    $r = $csv_synonym = array();

    $csv = str_getcsv($fila, ';', '"');

    $csv[10] = str_replace('_', '', $csv[10]);

    $csv = array_map('trim', $csv);

    $query = 'SELECT * FROM _species_details'
        .' WHERE genus_name = "'.$csv[5].'"'
        .' AND species_name = "'.$csv[7].'"'
        .' AND infraspecies_name = "'.$csv[10].'";';

    $r['_species_details'] = $COL->queryResult($query);

    if (empty($r['_species_details'])) {
        $all[] = addRow($csv, $r, $csv_accepted, $csv_synonym);

        $report['notfound'][] = $fila;

        debug($query, 'Empty _species_details', __LINE__);

        continue;
    }

    ++$report['found'];

    if (count($r['_species_details']) !== 1) {
        $all[] = addRow($csv, $r, $csv_accepted, $csv_synonym);

        $report['duplicated'][] = $fila;

        foreach ($r['_species_details'] as $_species_details_value) {
            $report['duplicated'][] = var_export($_species_details_value, true);
        }

        debug($query, 'More than one _species_details ('.count($r['_species_details']).')', __LINE__);

        continue;
    }

    $r['_species_details'] = $r['_species_details'][0];

    $query = 'SELECT scientific_name_status_id FROM taxon_detail'
        .' WHERE taxon_id = "'.$r['_species_details']['taxon_id'].'"'
        .' AND scientific_name_status_id IN ("1", "4");';

    $r['taxon_detail'] = $COL->queryResult($query);

    if (empty($r['taxon_detail'])) {
        $all[] = addRow($csv, $r, $csv_accepted, $csv_synonym);

        debug($query, 'Empty Result taxon_detail', __LINE__);
    } else {
        $csv_accepted = 1;
    }

    $query = 'SELECT id, scientific_name_status_id FROM synonym'
        .' WHERE taxon_id = "'.$r['_species_details']['taxon_id'].'"'
        .' AND scientific_name_status_id IN ("2", "5");';

    $r['synonym'] = $COL->queryResult($query);

    if (empty($r['synonym'])) {
        $all[] = addRow($csv, $r, $csv_accepted, $csv_synonym);

        debug($query, 'Empty Result synonym', __LINE__);

        continue;
    }

    $query = 'SELECT taxonomic_rank_id FROM taxon'
        .' WHERE id = "'.$r['_species_details']['taxon_id'].'"'
        .' LIMIT 1;';

    $r['taxon'] = $COL->queryResult($query)[0];

    if (empty($r['taxon'])) {
        $all[] = addRow($csv, $r, $csv_accepted, $csv_synonym);

        debug($query, 'Empty Result taxon', __LINE__);

        continue;
    }

    $query = 'SELECT parent_id FROM _taxon_tree'
        .' WHERE taxon_id = "'.$r['_species_details']['taxon_id'].'"'
        .' LIMIT 1;';

    $r['_taxon_tree'] = $COL->queryResult($query)[0];

    if (empty($r['_taxon_tree'])) {
        $all[] = addRow($csv, $r, $csv_accepted, $csv_synonym);

        debug($query, 'Empty Result _taxon_tree', __LINE__);

        continue;
    }

    foreach ($r['synonym'] as $synonym_value) {
        $query = 'SELECT scientific_name_element_id FROM synonym_name_element'
            .' WHERE synonym_id = "'.$synonym_value['id'].'"'
            .' AND taxonomic_rank_id = "'.$r['taxon']['taxonomic_rank_id'].'";';

        $r['synonym_name_element'] = $COL->queryResult($query);

        if (empty($r['synonym_name_element'])) {
            debug($query, 'Empty Result synonym_name_element', __LINE__);
            continue;
        }

        foreach ($r['synonym_name_element'] as $synonym_name_element_value) {
            $query = 'SELECT taxon_id FROM taxon_name_element'
                .' WHERE scientific_name_element_id = "'.$synonym_name_element_value['scientific_name_element_id'].'"'
                .' AND parent_id = "'.$r['_taxon_tree']['parent_id'].'";';

            $r['taxon_name_element'] = $COL->queryResult($query);

            if (empty($r['taxon_name_element'])) {
                //debug($query, 'Empty Result taxon_name_element', __LINE__);
                continue;
            }

            foreach ($r['taxon_name_element'] as $taxon_name_element_value) {
                $query = 'SELECT genus_name, species_name, infraspecies_name FROM _species_details'
                    .' WHERE taxon_id = "'.$taxon_name_element_value['taxon_id'].'"'
                    .' LIMIT 1;';

                $scientific_name = $COL->queryResult($query)[0];

                if (empty($scientific_name)) {
                    debug($query, 'Empty Result scientific_name', __LINE__);
                    continue;
                }

                $scientific_name = trim(implode(' ', $scientific_name));

                if (!in_array($scientific_name, $csv_synonym)) {
                    $csv_synonym[] = $scientific_name;
                }
            }
        }
    }

    $all[] = addRow($csv, $r, $csv_accepted, $csv_synonym);
}

$header = str_putcsv($header, ';', '"');

array_unshift($all, $header);

file_put_contents($new, implode("\n", $all));

array_unshift($report['notfound'], $header);

file_put_contents($new.'-notfound', implode("\n", $report['notfound']));
file_put_contents($new.'-duplicated', implode("\n", $report['duplicated']));

debug($report['found'], 'Found rows', __LINE__);
debug($report['notfound'], 'NOT Found rows: '.count($report['notfound']), __LINE__);
debug($report['duplicated'], 'Duplicated rows: '.count($report['duplicated']), __LINE__);

die ('<h1>Ended at '.date('Y-m-d H:i:s').'</h1>');
