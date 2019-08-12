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
     */
    public function __construct($day, $month, $year) {
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }
}