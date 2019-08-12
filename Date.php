<?php

class Date {

    public $day;
    public $month;
    public $year;

    /**
     * Date constructor.
     * @param $day
     * @param $month
     * @param $year
     * @throws Exception
     */
    public function __construct($day, $month, $year) {

        $day = intval($day);
        $month = intval($month);
        $year = intval($year);

        if(!($day >= 1 && $day <= 31)) {
            throw new Exception("Tag muss eine ganze Zahl zwischen 1 und 31 sein.");
        }
        if(!($month >= 1 && $month <= 12)) {
            throw new Exception("Monat muss eine ganze Zahl zwischen 1 und 12 sein.");
        }
        if(!(strlen($year)==4)) {
            throw new Exception("Jahr muss eine vierstellige Zahl sein.");
        }

        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }
}