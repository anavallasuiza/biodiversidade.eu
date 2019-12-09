<?php

namespace Eu\Biodiversidade;

class FerramentaAreas {

    public static function getConditionsAndValues($param, $type = null) {

        $conditions = [];
        $values = array();

        if ($param['points']) {
            $polygon = "POLYGON((";
            $coordinates = array();

            foreach($param['points'] as $point) {
                $coordinates[] = $point['lat'] . ' ' . $point['lng'];
            }

            $coordinates[] = $param['points'][0]['lat'] . ' ' . $param['points'][0]['lng'];
            $polygon .= join(", ", $coordinates) . "))";

            if ($type === 'points') {
                $conditions[] =' ST_Contains(GEOMFROMTEXT(:polygon), gp.point) ';
            } else if ($type === 'polygons') {
                $conditions[] =' ST_intersects(GEOMFROMTEXT(:polygon), gpl.polygon) ';
            } else {
                $conditions[] =' (ST_intersects(GEOMFROMTEXT(:polygon), gpl.polygon) or ST_Contains(GEOMFROMTEXT(:polygon), gp.point)) ';
            }

            $values[':polygon'] = $polygon;
        }


        if ($param['territorio']) {
            $conditions[] = ' t.url = :territorio ';
            $values[':territorio'] = $param['territorio'];
        }

        if ($param['provincia']) {
            $conditions[] = ' pr.`nome-url` = :provincia ';
            $values[':provincia'] = $param['$provincia'];
        }

        if ($param['concello']) {
            $conditions[] = ' c.`nome-url` = :concello ';
            $values[':concello'] = $param['concello'];
        }

        if ($param['ano']) {
            $conditions[] = ' year(a.`data_observacion`) = :ano ';
            $values[':ano'] = $param['$ano'];
        }

        if ($param['ameaza']) {
            $conditions[] = ' e.`nivel_ameaza` = :ameaza';
            $values[':ameaza'] = $param['$ameaza'];
        }

        if ($param['validada'] === '1' || $param['validada'] === '0') {
            $conditions[] = ' a.validado = :validado';
            $values[':validado'] = $param['validada'];
        }

        if ($param['proteccion']) {
            $conditions[] = ' e.`protexida` = 1';
            $conditions[] = ' prt.id = :proteccion';
            $values[':proteccion'] = $param['proteccion'];
        }

        return array(
            'conditions' => $conditions,
            'values' => $values
        );
    }

    public static function executeQuery($query, $values) {

        global $Db;

        $statement = $Db->PDO->prepare($query);
        $statement->execute($values);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getAvistamentos($params, $fields = null) {

        global $Vars;

        if (!$fields) {
            $fields = ' a.*, e.url as`especie_url`, e.nome as `especie_nome`, f.nome as `especie_familia`, ' .
                'g.`nome-' . $Vars->getLanguage() . '` as `especie_grupo`, i.imaxe as imaxe, u.id as `usuarios_autor`, ' .
                'u.`nome-url` as `usuarios_autor_url`, u.`nome-title` as `usuarios_autor_nome`, uv.`nome-title` as `usuarios_validador_nome` ';
        }

        $filter = self::getConditionsAndValues($params);

        $query = '
            select ' . $fields . '
            from avistamentos a
            inner join puntos as `p` on a.`id` = p.`id_avistamentos`
            inner join usuarios `u` on a.`id_usuarios_autor` = u.id
            left join avistamentos_territorios as `at` on a.id = at.`id_avistamentos`
            left join territorios as `t` on at.`id_territorios` = t.id
            left join avistamentos_provincias `ap` on a.id = ap.`id_avistamentos`
            left join provincias `pr` on ap.`id_provincias` = `pr`.id
            left join avistamentos_concellos `ac` on a.id = ac.`id_avistamentos`
            left join concellos `c` on ac.`id_concellos` = `c`.id
            left join especies e on a.`id_especies` = e.id
            left join especies_proteccions_tipos `ept` on e.id = ept.`id_especies`
            left join proteccions_tipos `prt` on ept.id_proteccions_tipos = prt.id
            left join familias f on f.id = e.`id_familias`
            left join grupos g on g.id = e.`id_grupos`
            left join imaxes as `i` on i.`id_avistamentos` = a.id
            left join gis_points `gp` on p.id = gp.`id_puntos`
            left join gis_polygons `gpl` on p.id = gpl.`id_puntos`
            left join usuarios `uv` on a.`id_usuarios_validador` = uv.id
            ' . ($filter['conditions'] ? 'where' . join(' and ', $filter['conditions']) : '') . '
            group by a.id;
        ';

        return self::executeQuery($query, $filter['values']);
    }

    public static function getEspecies($params, $fields = null) {

        global $Vars;

        if (!$fields) {
            $fields = ' e.*, g.`nome-' . $Vars->getLanguage() . '` as `grupo_nome`, g.`url` as `grupo_url` ';
        }

        $filter = self::getConditionsAndValues($params);

        $query = '
            select ' . $fields . '
            from especies e
            inner join avistamentos as `a` on a.`id_especies` = e.id
            left join avistamentos_territorios as `at` on a.id = at.`id_avistamentos`
            left join territorios as `t` on at.`id_territorios` = t.id
            left join avistamentos_provincias `ap` on a.id = ap.`id_avistamentos`
            left join provincias `pr` on ap.`id_provincias` = `pr`.id
            left join avistamentos_concellos `ac` on a.id = ac.`id_avistamentos`
            left join concellos `c` on ac.`id_concellos` = `c`.id
            inner join puntos as `p` on a.`id` = p.`id_avistamentos`
            inner join grupos `g` on g.id = e.`id_grupos`
            left join gis_points `gp` on p.id = gp.`id_puntos`
            left join gis_polygons `gpl` on p.id = gpl.`id_puntos`
            left join especies_proteccions_tipos `ept` on e.id = ept.`id_especies`
            left join proteccions_tipos `prt` on ept.id_proteccions_tipos = prt.id
            ' . ($filter['conditions'] ? 'where' . join(' and ', $filter['conditions']) : '') . '
            group by e.id
            order by e.nome asc;
        ';

        return self::executeQuery($query, $filter['values']);
    }

    public static function getPuntos($params, $fields = null) {

        if (!$fields) {
            $fields = ' p.*, a.url as `context` ';
        }

        $filterPoints = self::getConditionsAndValues($params, 'points');
        $filterPolygons = self::getConditionsAndValues($params, 'polygons');

        $query = '
            select ' . $fields . '
            from puntos as `p`
            inner join puntos_tipos as `pt` on p.`id_puntos_tipos` = pt.id
            inner join avistamentos as `a` on p.`id_avistamentos` = a.id
            left join avistamentos_territorios as `at` on a.id = at.`id_avistamentos`
            left join territorios as `t` on at.`id_territorios` = t.id
            left join avistamentos_provincias `ap` on a.id = ap.`id_avistamentos`
            left join provincias `pr` on ap.`id_provincias` = `pr`.id
            left join avistamentos_concellos `ac` on a.id = ac.`id_avistamentos`
            left join concellos `c` on ac.`id_concellos` = `c`.id
            left join especies e on a.`id_especies` = e.id
            left join especies_proteccions_tipos `ept` on e.id = ept.`id_especies`
            left join proteccions_tipos `prt` on ept.id_proteccions_tipos = prt.id
            left join gis_points `gp` on p.id = gp.`id_puntos`
            left join gis_polygons `gpl` on p.id = gpl.`id_puntos`
            where pt.numero = 4 ' . ($filterPoints['conditions'] ? ' and ' . join(' and ', $filterPoints['conditions']) : '') . '
            group by p.id;
        ';

        $puntos = self::executeQuery($query, $filterPoints['values']);

        $query = '
            select ' . $fields . '
            from puntos as `p`
            inner join puntos_tipos as `pt` on p.`id_puntos_tipos` = pt.id
            inner join avistamentos as `a` on p.`id_avistamentos` = a.id
            left join avistamentos_territorios as `at` on a.id = at.`id_avistamentos`
            left join territorios as `t` on at.`id_territorios` = t.id
            left join avistamentos_provincias `ap` on a.id = ap.`id_avistamentos`
            left join provincias `pr` on ap.`id_provincias` = `pr`.id
            left join avistamentos_concellos `ac` on a.id = ac.`id_avistamentos`
            left join concellos `c` on ac.`id_concellos` = `c`.id
            left join especies e on a.`id_especies` = e.id
            left join especies_proteccions_tipos `ept` on e.id = ept.`id_especies`
            left join proteccions_tipos `prt` on ept.id_proteccions_tipos = prt.id
            left join gis_points `gp` on p.id = gp.`id_puntos`
            left join gis_polygons `gpl` on p.id = gpl.`id_puntos`
            where pt.numero = 1 ' . ($filterPolygons['conditions'] ? ' and ' . join(' and ', $filterPolygons['conditions']) : '') . '
            group by p.id;
        ';

        $centroides1 = self::executeQuery($query, $filterPolygons['values']);

        $query = '
            select ' . $fields . '
            from puntos as `p`
            inner join puntos_tipos as `pt` on p.`id_puntos_tipos` = pt.id
            inner join avistamentos as `a` on p.`id_avistamentos` = a.id
            left join avistamentos_territorios as `at` on a.id = at.`id_avistamentos`
            left join territorios as `t` on at.`id_territorios` = t.id
            left join avistamentos_provincias `ap` on a.id = ap.`id_avistamentos`
            left join provincias `pr` on ap.`id_provincias` = `pr`.id
            left join avistamentos_concellos `ac` on a.id = ac.`id_avistamentos`
            left join concellos `c` on ac.`id_concellos` = `c`.id
            left join especies e on a.`id_especies` = e.id
            left join especies_proteccions_tipos `ept` on e.id = ept.`id_especies`
            left join proteccions_tipos `prt` on ept.id_proteccions_tipos = prt.id
            left join gis_points `gp` on p.id = gp.`id_puntos`
            left join gis_polygons `gpl` on p.id = gpl.`id_puntos`
            where pt.numero = 2 ' . ($filterPolygons['conditions'] ? ' and ' . join(' and ', $filterPolygons['conditions']) : '') . '
            group by p.id;
        ';

        $centroides10 = self::executeQuery($query, $filterPolygons['values']);

        return array(
            'puntos' => $puntos,
            'centroides1' => $centroides1,
            'centroides10' => $centroides10
        );
    }
}