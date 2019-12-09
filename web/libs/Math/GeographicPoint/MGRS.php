<?php
namespace Math\GeographicPoint;

class MGRS extends GeographicPoint
{
    private $letter_x = array(
        'M' => 4,
        'N' => 5,
        'P' => 6,
        'Q' => 7
    );

    private $letter_y = array(
        'E' => 44,
        'F' => 45,
        'G' => 46,
        'H' => 47,
        'J' => 48
    );

    private $letter_end = array(
        'A' => array(250, 750),
        'B' => array(750, 750),
        'C' => array(250, 250),
        'D' => array(750, 250),
        ''  => array(500, 500)
    );

    private $x = 0;
    private $y = 0;
    private $zone = '29T';

    public function __construct($mgrs, $datum = '')
    {
        //$mgrs = preg_replace('/^'.$this->zone.'/', '', strtoupper(trim($mgrs)));

        if (!preg_match('/^([0-9]{1,2}[A-Z])([A-Z])([A-Z])([0-9]*)([A-Z])?$/', $mgrs, $matches)) {
            return false;
        }
        
        $location = str_split($matches[4], strlen($matches[4]) / 2);

        $this->x = $this->letter_x[$matches[2]].$location[0];
        $this->y = $this->letter_y[$matches[3]].$location[1];

        if ($matches[5]) {
            $this->x += $this->letter_end[$matches[5]][0];
            $this->y += $this->letter_end[$matches[5]][1];
        }

        

        parent::__construct($datum);
    }

    public function toTM ()
    {
        if ($this->x && $this->y) {
            return new UTM($this->x, $this->y, $this->zone, $this->datum);
        } else {
            return false;
        }
    }
}
