<?php
defined('ANS') or die();

$shapes = json_encode(\Eu\Biodiversidade\FerramentaAreas::getPuntos($Vars->var));
echo $shapes;

die();