<?php

/**
 * Wochentagsberechnung nach https://de.wikipedia.org/wiki/Wochentagsberechnung
 */

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

/**
 * @return array
 */
function handleCommandLine($argv): array {

    $argc = count($argv);
    if ($argc < 4 || $argc > 5) {
        echo "Wrong number of arguments.";
        exit(1);
    }

    $day = $argv[1];
    $month = $argv[2];
    $year = $argv[3]; /* muss vierstellig sein */
    $date = new Date($day, $month, $year);

    if(isset($argv[4]) && ($argv[4] == '-d' || $argv[4] == '--debug')) {
        $debug = true;
    } else {
        $debug = false;
    }

    return [$date, $debug];
}

/**
 * @param int $weekDayNumber
 * @return string
 */
function weekdayNumberToWeekday(int $weekDayNumber) : string {
    $weekDayNames = [
        1 => "Montag",
        2 => "Dienstag",
        3 => "Mittwoch",
        4 => "Donnerstag",
        5 => "Freitag",
        6 => "Samstag",
        0 => "Sonntag",
    ];

    if (isset($weekDayNames[$weekDayNumber])) {
        $weekDay = $weekDayNames[$weekDayNumber];
    } else {
        echo "Error: Unknown w={$weekDayNumber}\n";
        exit(1);
    }
    return $weekDay;
}

/**
 * @param $day
 * @param $month
 * @param $year
 * @return int
 */
function dateToWeekdayNumber($date, bool $debug=false): int {
    $d = $date->day;
    $m = 0;
    $m = (($date->month - 2 - 1) + 12) % 12 + 1; // this is because of the modulo
    $c = substr($date->year, 0, 2);
    if ($m >= 11) {
        $c = substr($date->year - 1, 0, 2);
    }
    $y = substr($date->year, 2, 2);
    if ($m >= 11) {
        $y = substr($date->year - 1, 2, 2);
    }

    $weekDayNumber = ($d + intval(2.6 * $m - 0.2) + $y + intval($y / 4) + intval($c / 4) - 2 * $c) % 7;
    if ($debug) {
        echo "DEBUG: m={$m} y={$y} c={$c}\n";
    }
    return $weekDayNumber;
}

/**
 * @param Date $date
 * @param string $weekDay
 */
function outputResult(Date $date, string $weekDay): void {
    echo "Eingabe: {$date->day}.{$date->month}.{$date->year}\n";
    echo strftime("Berechnung PHP: Wochentag='%A'\n", strtotime("{$date->year}-{$date->month}-{$date->day}"));
    echo "Berechnung Algorithmus: Wochentag='{$weekDay}'\n";
}

/**
 * @param $argv
 * @return int
 */
function main($argv): int {
    setlocale(LC_TIME, 'de_AT.utf-8');
    list($inputDate, $debug) = handleCommandLine($argv);

    $weekDayNumber = dateToWeekdayNumber($inputDate, $debug);
    $weekDay = weekdayNumberToWeekday($weekDayNumber);

    outputResult($inputDate, $weekDay);
    return 0;
}

return main($argv);

