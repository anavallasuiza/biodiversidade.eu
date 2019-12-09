<?php
defined('ANS') or die();

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');

if ($Vars->var['fix-mgrs-type']) {

	$points = $Db->select(array(
		'table' => 'puntos',
		'conditions' => array(
			'tipo' => 'mgrs'
		)
	));

	foreach($points as $point) {

		$tipo = getMGRSCentroidType($point['mgrs']);

		$res = $Db->relate(array(
			'tables' => array(
				array(
					'table' => 'puntos',
					'limit' => 1,
					'conditions' => array(
						'id' => $point['id']
					)
				),
				array(
					'table' => 'puntos_tipos',
					'limit' => 1,
					'conditions' => array(
						'numero' => $tipo
					)
				)
			)
		));
		
		if (!$res) {
			var_dump($Errors->getList());
			die();
		}
	}
}

if ($Vars->var['fix-datums']) {

	$points = $Db->select(array(
		'table' => 'puntos'
	));

	foreach($points as $point) {

		$datumCode;

		if (strpos($point['datum'], 'ED50') === 0 || 
			strpos($point['datum'], 'ED_1950') === 0) {

			$datumCode = 'ed50';

		} else if (strpos($point['datum'], 'ETRS89') === 0) {
			
			$datumCode = 'etrs89';

		} else if (strpos($point['datum'], 'Lisboa') > 0) {
			$datumCode = 'lisboa';
		} else {
			$datumCode = 'wgs84';
		}

		$res = $Db->relate(array(
			'tables' => array(
				array(
					'table' => 'puntos',
					'conditions' => array(
						'id' => $point['id']
					)
				),
				array(
					'table' => 'datums',
					'conditions' => array(
						'url' => $datumCode
					)
				)
			)
		));

		if (!$res) {
			var_dump($Errors->getList());
			die();
		}
	}

}


if ($Vars->var['fix-point-type']) {

	// Get all the points
	$points = $Db->select(array(
		'table' => 'puntos',
		'add_tables' => array(
			array(
				'table' => 'puntos_tipos',
				'limit' => 1
			)
		)
	));
	$j = 0;
	foreach($points as $i => $point) {

		// If it has a type we skip it
		if ($point['tipo']) {
			continue;
		}

		$tipo = 'latlong';
		$numero = 4;

		if ($point['arquivo']) {
			$tipo = 'file';
			$numero = 3;
		} else if ($point['mgrs']) {
			$tipo = 'mgrs';

			$mgrs = trim($point['mgrs']);

		    preg_match_all('/\D/', $mgrs, $matches, PREG_OFFSET_CAPTURE);
		    $lastStr = $matches[0][count($matches[0]) - 1][1];

		    $grid = substr($mgrs, 0, $lastStr + 1);
		    $location = substr($mgrs, $lastStr + 1);

		    if (strlen($location) <= 2) { // Dous numeros (1 northing + 1 easting) === 10km2
		        $numero = 1;
		    } else if (strlen($location) <= 4) { // Catro numeros (2 northing + 2 easting) === 1km2
		        $numero = 2;
		    }

		} else if ($point['utm_fuso'] && $point['utm_x'] && $point['utm_y']) {
			$tipo = 'utm';
		}

		$res = $Db->update(array(
			'table' => 'puntos',
			'data' => array(
				'tipo' => $tipo
			),
			'conditions' => array(
				'id' => $point['id']
			),
			'relate' => array(
				array(
					'table' => 'puntos_tipos',
					'conditions' => array(
						'numero' => $numero
					)
				)
			)
		));

		if (!$res) {

			var_dump($Errors->getList());
			die();
		} else {
			echo('<p>' . $point['id'] . '-' . $tipo . '-' . $numero . '</p>');
			$j++;	
		}
	}

	echo 'Total actualizados: ' . $j;
}

if ($Vars->var['fix-categories']) {

	$reinos = $Db->select(array(
		'table' => 'reinos'
	));

	foreach($reinos as $reino) {

		$clases = $Db->select(array(
			'table' => 'clases',
			'conditions' => array(
				'reinos.id' => $reino['id']
			)
		));

		foreach($clases as $clase) {

			$res = $Db->relate(array(
				'tables' => array(
					array(
						'table' => 'clases',
						'conditions' => array(
							'id' => $clase['id']
						)
					),
					array(
						'table' => 'reinos',
						'conditions' => array(
							'id' => $reino['id']
						)
					)
				)
			));

			if (!$res) {
				var_dump($Errors->getList());
				die();
			}

			$ordes = $Db->select(array(
				'table' => 'ordes',
				'conditions' => array(
					'clases.id' => $clase['id']
				)
			));

			foreach($ordes as $orde) {

				$res = $Db->relate(array(
					'tables' => array(
						array(
							'table' => 'ordes',
							'conditions' => array(
								'id' => $orde['id']
							)
						),
						array(
							'table' => 'reinos',
							'conditions' => array(
								'id' => $reino['id']
							)
						)
					)
				));

				if (!$res) {
					var_dump($Errors->getList());
					die();
				}

				$res = $Db->relate(array(
					'tables' => array(
						array(
							'table' => 'ordes',
							'conditions' => array(
								'id' => $orde['id']
							)
						),
						array(
							'table' => 'clases',
							'conditions' => array(
								'id' => $clase['id']
							)
						)
					)
				));

				if (!$res) {
					var_dump($Errors->getList());
					die();
				}

				$familias = $Db->select(array(
					'table' => 'familias',
					'conditions' => array(
						'ordes.id' => $orde['id']
					)
				));

				foreach($familias as $familia) {
					
					$res = $Db->relate(array(
						'tables' => array(
							array(
								'table' => 'familias',
								'conditions' => array(
									'id' => $familia['id']
								)
							),
							array(
								'table' => 'reinos',
								'conditions' => array(
									'id' => $reino['id']
								)
							)
						)
					));

					if (!$res) {
						var_dump($Errors->getList());
						die();
					}

					$res = $Db->relate(array(
						'tables' => array(
							array(
								'table' => 'familias',
								'conditions' => array(
									'id' => $familia['id']
								)
							),
							array(
								'table' => 'clases',
								'conditions' => array(
									'id' => $clase['id']
								)
							)
						)
					));

					if (!$res) {
						var_dump($Errors->getList());
						die();
					}

					$res = $Db->relate(array(
						'tables' => array(
							array(
								'table' => 'familias',
								'conditions' => array(
									'id' => $familia['id']
								)
							),
							array(
								'table' => 'ordes',
								'conditions' => array(
									'id' => $orde['id']
								)
							)
						)
					));

					if (!$res) {
						var_dump($Errors->getList());
						die();
					}

					$xeneros = $Db->select(array(
						'table' => 'xeneros',
						'conditions' => array(
							'familias.id' => $familia['id']
						)
					));

					foreach($xeneros as $xenero) {
						
						$res = $Db->relate(array(
							'tables' => array(
								array(
									'table' => 'xeneros',
									'conditions' => array(
										'id' => $xenero['id']
									)
								),
								array(
									'table' => 'reinos',
									'conditions' => array(
										'id' => $reino['id']
									)
								)
							)
						));

						if (!$res) {
							var_dump($Errors->getList());
							die();
						}

						$res = $Db->relate(array(
							'tables' => array(
								array(
									'table' => 'xeneros',
									'conditions' => array(
										'id' => $xenero['id']
									)
								),
								array(
									'table' => 'clases',
									'conditions' => array(
										'id' => $clase['id']
									)
								)
							)
						));

						if (!$res) {
							var_dump($Errors->getList());
							die();
						}

						$res = $Db->relate(array(
							'tables' => array(
								array(
									'table' => 'xeneros',
									'conditions' => array(
										'id' => $xenero['id']
									)
								),
								array(
									'table' => 'ordes',
									'conditions' => array(
										'id' => $orde['id']
									)
								)
							)
						));

						if (!$res) {
							var_dump($Errors->getList());
							die();
						}

						$res = $Db->relate(array(
							'tables' => array(
								array(
									'table' => 'xeneros',
									'conditions' => array(
										'id' => $xenero['id']
									)
								),
								array(
									'table' => 'familias',
									'conditions' => array(
										'id' => $familia['id']
									)
								)
							)
						));

						if (!$res) {
							var_dump($Errors->getList());
							die();
						}

						echo '<p>' . $reino['url'] . '</p>';
						echo '<p>' . $clase['url'] . '</p>';
						echo '<p>' . $orde['url'] . '</p>';
						echo '<p>' . $familia['url'] . '</p>';
						echo '<p>' . $xenero['url'] . '</p>';
						echo '<hr/><hr/><br/>';
					}					
				}
			}
		}

	}
}

if ($Vars->var['fix-especie-nome']) {

	$especies = $Db->select(array(
		'table' => 'especies',
		'add_tables' => array(
			'xenero' => array(
				'table' => 'xeneros',
				'limit' => 1
			)
		)
	));

	foreach($especies as $i => $especie) {

		$nome = $especie['nome'];
		$autor = $especie['autor'];
		$subespecie = $especie['subespecie'];
		$variedade = $especie['variedade'];

		if ($subespecie && $variedade) {

			$data = array();

			if ($subespecie === 'var.') {
				$data['subespecie'] = '';
				$data['variedade'] = $variedade;
			} else if ($subespecie === 'subsp.' || $subespecie === 'espec.') {
				$data['subespecie'] = $variedade;
				$data['variedade'] = '';
			}

		}

		if ($autor) {
			$data['nome_cientifico'] = str_replace($autor, '', $especie['nome_cientifico']);
		}

		$res = $Db->update(array(
			'table' => 'especies',
			'data' => $data,
			'conditions' => array(
				'id' => $especie['id']
			)
		));

		if (!$res) {
			var_dump($Errors->getList());
			die();
		}

		/*
		if (preg_match('/(.+)(var\.|subsp\.)(.+)/', $especie['nome'], $matches)) {
			

			$nome = trim($matches[1]);
			$tipoSubespecie = trim($matches[2]);
			$subespecie = trim($matches[3]);

			$data = array(
				'nome_cientifico' => $nome,
				'subespecie' => $tipoSubespecie,
				'variedade' => $subespecie
			);

		} else {
			$data = array(
				'nome_cientifico' => $especie['nome']
			);
		}

		$res = $Db->update(array(
			'table' => 'especies',
			'data' => $data,
			'conditions' => array(
				'id' => $especie['id']
			)
		));

		if (!$res) {
			var_dump($Errors->getList());
			die();
		}

		$Db->relate(array(
			'tables' => array(
				array(
					'table' => 'especies',
					'limit' => 1,
					'conditions' => array(
						'id' => $especie['id']
					)
				),
				array(
					'table' => 'tipos_subespecies',
					'limit' => 1,
					'conditions' => array(
						'nome' => $tipoSubespecie
					)
				)
			)
		));
		*/
	}
}

if ($Vars->var['fix-especie-variedade']) {

	$especies = $Db->select(array(
		'table' => 'especies_variedade'
	));

	$wrong = array();

	foreach($especies as $i => $especie) {


		$id = $especie['id'];
		$nome = $especie['nome'];
		$autor = $especie['autor'];
		$subespecie = $especie['subespecie'];
		$variedade = $especie['variedade'];

		//echo "<hr/>";
		//echo "<h1>" . $nome . '</h1>';
		


		$especieActual = $Db->select(array(
			'table' => 'especies',
			'limit' => 1,
			'conditions' => array(
				'id' => $especie['id_old'],
				'nome' => $especie['nome'],
			)
		));

		// If its not a complete match we ignore it
		if (!$especieActual) {
			continue;
		}



		$data = array();

		if (preg_match('/(.+)(var\.|subsp\.)(.+)/', $especieActual['nome'], $matches)) {


			$nomeCientifico = trim(str_replace($especieActual['autor'], '', $especieActual['nome_cientifico'])); 

			if (trim($nomeCientifico) !== trim($especieActual['nome_cientifico'])) {
				$data['nome_cientifico'] = $nomeCientifico;
			}

			if ($matches[2] === 'var.') {

				$autorvariedade = trim(str_replace($variedade, '', $matches[3]));
				
				$data['variedade'] = $variedade;
				$data['variedade_autor'] = $autorvariedade;


			} else if ($matches[2] === 'subsp.') {

				
				$autorvariedade = trim(str_replace($variedade, '', $matches[3]));
				
				$data['subespecie'] = $variedade;
				$data['subespecie_autor'] = $autorvariedade;

			} else {
				die('wtf');
			}
			
			$res = $Db->update(array(
				'table' => 'especies',
				'data' => $data,
				'conditions' => array(
					'id' => $especieActual['id']
				)
			));

			if (!$res) {
				var_dump($Errors->getList());
				die();
			}
		}
		
	}
}

if ($Vars->var['fix-especie-tipo']) {
    
    $especies = $Db->select(array(
		'table' => 'especies',
        'add_tables' => array(
            array(
                'table' => 'reinos',
                'limit' => 1
            ),
            array(
                'table' => 'clases',
                'limit' => 1
            ),
            array(
                'table' => 'ordes',
                'limit' => 1
            ),
            array(
                'table' => 'familias',
                'limit' => 1
            ),
            array(
                'table' => 'xeneros',
                'limit' => 1
            )
        )
	));

	foreach($especies as $i => $especie) {
        
        if (!$especie['subespecie'] && !$especie['variedade']) {
            continue;
        }
        
        $tipo = $Db->select(array(
            'table' => 'especies',
            'limit' => 1,
            'conditions' => array(
                'nome_cientifico' => $especie['nome_cientifico'],
                'subespecie' => '',
                'variedade' => '',
                'id !=' => $especie['id']
            )
        ));
        
        if ($tipo) {
            //var_dump($tipo);
        } else {
            
            $res = $Db->insert(array(
                'table' => 'especies',
                'data' => array(
                    'url' => $especie['nome_cientifico'] . ' ' . $especie['autor'],
                    'nome' => $especie['nome_cientifico'] . ' ' . $especie['autor'],
                    'nome_cientifico' => $especie['nome_cientifico'],
                    'autor' => $especie['autor'],
                    'data_alta' => date('Y-m-d H:i:s'),
                    'activo' => 1
                ),
                'relate' => array(
                    array(
                        'table' => 'usuarios',
                        'name' => 'autor',
                        'conditions' => array(
                            'usuario' => 'info@biodiversidade.eu'
                        ),
                        'limit' => 1
                    ),
                    array(
                        'table' => 'reinos',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $especie['reinos']['id']
                        )
                    ),
                    array(
                        'table' => 'clases',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $especie['clases']['id']
                        )
                    ),
                    array(
                        'table' => 'ordes',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $especie['ordes']['id']
                        )
                    ),
                    array(
                        'table' => 'familias',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $especie['familias']['id']
                        )
                    ),
                    array(
                        'table' => 'xeneros',
                        'limit' => 1,
                        'conditions' => array(
                            'id' => $especie['xeneros']['id']
                        )
                    ),
                )
            ));
            
            if (!$res) {
				var_dump($Errors->getList());
				die();
			}
            
            $tipo = $Db->select(array(
                'table' => 'especies',
                'limit' => 1,
                'conditions' => array(
                    'id' => $res
                )
            ));
        }
        
        $Db->relate(array(
            'tables' => array(
                array(
                    'table' => 'especies',
                    'direction' => 'child',
                    'conditions' => array(
                        'id' => $tipo['id']
                    ),
                    'limit' => 1
                ),
                array(
                    'table' => 'especies',
                    'limit' => 1,
                    'direction' => 'parent',
                    'conditions' => array(
                        'id' => $especie['id']
                    )
                )
            )
        ));
    }
}

if ($Vars->var['fix-tipo-referencia']) {
    
    $avistamentos = $Db->select(array(
        'table' => 'avistamentos',
        'conditions' => array(
            'tipo_referencia' => array('observacion', 'bibliografico', 'coleccion')
        )
    ));
    
    
    $res = $Db->relate(array(
        'tables' => array(
            array(
                'table' => 'avistamentos',
                'conditions' => array(
                    'tipo_referencia' => 'observacion'
                )
            ),
            array(
                'table' => 'referencias_tipos',
                'conditions' => array(
                    'url' => 'observacion'
                )
            )
        )
    ));
    
    if ($Errors->getList()) {
        var_dump($Errors->getList());
        die();
    }
}

if ($Vars->var['fix-latlng-mgrs']) {
    
    $points = $Db->select(array(
		'table' => 'puntos',
		'conditions' => array(
			'tipo' => 'mgrs',
            'mgrs !=' => ''
		)
	));

	foreach($points as $punto) {
        

		$centroide = getMGRSCentroid(trim($punto['mgrs']));
        $tipo = getMGRSCentroidType(trim($punto['mgrs']));

        $latLong = Coordinates::mgrsToLatLong($centroide, strtoupper($punto['datum']));
                
        $res = $Db->update(array(
            'table' => 'puntos',
            'data' => array(
                'mgrs' => trim($punto['mgrs']),
                'latitude' => round($latLong['lat'], 14),
                'lonxitude' => round($latLong['lng'], 14),
            ),
            'conditions' => array(
                'id' => $punto['id']
            )
        ));
        
        if ($Errors->getList()) {
            var_dump($Errors->getList());
            die();
        }
    }
}

if ($Vars->var['fill-gis-points']) {
    
    $points = $Db->queryResult("
        select *
        from `puntos` p
        where p.`id_formas` = 0 and (p.`tipo` = 'latlong' or p.`tipo` = 'utm');
    ");
    
    $Db->queryResult('delete from gis_points');
    
    foreach($points as $point) {
        $res = $Db->queryResult("INSERT INTO gis_points
            (id_puntos, point)
            VALUES
            (" . $point['id'] . ", GeomFromText('POINT(" . $point['latitude']  . " " . $point['lonxitude'] . ")'))");
        
        if ($Errors->getList()) {
            var_dump($Errors->getList());
            die();
        }
    }
}

if ($Vars->var['fill-gis-lines']) {
    
    $lines = $Db->select(array(
        'table' => 'formas',
        'conditions' => array(
            'tipo' => 'polyline'
        ),
        'add_tables' => array(
            array(
                'table' => 'puntos'
            )
        )
    ));
    
    $Db->queryResult('delete from gis_lines');
    
    foreach($lines as $line) {
        
        $lineString = array();
        
        foreach($line['puntos'] as $punto) {
            $lineString[] = $punto['latitude'] . ' ' . $punto['lonxitude'];
        }
        
        $res = $Db->queryResult("INSERT INTO gis_lines
            (id_formas, line)
            VALUES
            (" . $line['id'] . ", GeomFromText('LINESTRING(" . join(', ', $lineString) . ")'))");
        
        if ($Errors->getList()) {
            var_dump($Errors->getList());
            die();
        }
    }
}

if ($Vars->var['fill-gis-polygons']) {
    
    $polygons = $Db->select(array(
        'table' => 'formas',
        'conditions' => array(
            'tipo' => 'polygon'
        ),
        'add_tables' => array(
            array(
                'table' => 'puntos'
            )
        )
    ));
    
    $Db->queryResult('delete from gis_polygons');
    
    foreach($polygons as $polygon) {
        
        $polygonString = array();
        
        foreach($polygon['puntos'] as $punto) {
            $polygonString[] = $punto['latitude'] . ' ' . $punto['lonxitude'];
        }
        
        $polygonString[] = $polygon['puntos'][0]['latitude'] . ' ' . $polygon['puntos'][0]['lonxitude'];
        
        $res = $Db->queryResult("INSERT INTO gis_polygons
            (id_formas, polygon)
            VALUES
            (" . $polygon['id'] . ", GeomFromText('POLYGON((" . join(', ', $polygonString) . "))'))");
        
        if ($Errors->getList()) {
            var_dump($Errors->getList());
            die();
        }
    }
    
    $points = $Db->queryResult("
        select *, `p`.id as `idpunto`, pt.numero as numero
        from `puntos` p
        inner join `puntos_tipos` pt on p.`id_puntos_tipos` = pt.id
        where p.`id_formas` = 0 and p.`tipo` = 'mgrs';
    ");
    
    foreach($points as $point) {
        
        $puntos = Coordinates::getCentroidCorners($point['latitude'], $point['lonxitude'], $point['numero'] == 1 ? 1000: 10000);
        
        $polygonString = array();
        
        foreach($puntos as $punto) {
            $polygonString[] = $punto['latitude'] . ' ' . $punto['longitude'];
        }
        
        $polygonString[] = $puntos[0]['latitude'] . ' ' . $puntos[0]['longitude'];
        
        $res = $Db->queryResult("INSERT INTO gis_polygons
            (id_puntos, polygon)
            VALUES
            (" . $point['idpunto'] . ", GeomFromText('POLYGON((" . join(', ', $polygonString) . "))'))");
        
        if ($Errors->getList()) {
            var_dump($Errors->getList());
            die();
        }
    }
}

die();