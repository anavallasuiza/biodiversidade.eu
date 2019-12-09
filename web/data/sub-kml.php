<?php
defined('ANS') or die();

if (empty($name) || empty($description) || empty($polygons)) {
    die('');
}

$polygons_tpl = '';
$polygon_tpl = <<<EOT
<Polygon>
  <extrude>1</extrude>
  <outerBoundaryIs>
    <LinearRing>
      <coordinates>
COORDINATES
      </coordinates>
    </LinearRing>
  </outerBoundaryIs>
</Polygon>
EOT;

foreach ($polygons as $polygon) {
    $coordinates = array();

    foreach ($polygon['puntos'] as $point) {
        $coordinates[] = $point['lonxitude'].','.$point['latitude'].',0';
    }

    $polygons_tpl .= str_replace('COORDINATES', implode("\n", $coordinates), $polygon_tpl);
}

header('Pragma: private');
header('Expires: 0');
header('Cache-control: private, must-revalidate');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Type: application/force-download');
header('Content-Transfer-Encoding: binary');
header('Content-Disposition: attachment; filename="'.$name.'.kml"');

echo <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2">
  <Placemark>
    <name>$name</name>
    <description>
        <![CDATA[
            $description
        ]]>
    </description>
    $polygons_tpl
  </Placemark>
</kml>
EOF;

exit;