<?php

/**
 * Wochentagsberechnung nach https://de.wikipedia.org/wiki/Wochentagsberechnung
 */

require_once("Date.php");

/**
 * @param $argv
 * @return array
 * @throws Exception
 */
function handleCommandLine($argv): array {

    $argc = count($argv);
    if ($argc < 4 || $argc > 5) {
        throw new Exception("Syntax: {$argv[0]} <Tag> <Monat> <Jahr> [ -d ]");
    }

    $day = $argv[1];
    $month = $argv[2];
    $year = $argv[3];

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
 * @throws Exception
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
        throw new Exception("Error: Unknown w={$weekDayNumber}\n");
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
    $m = calculateM($date);
    $c = calculateC($date, $m);
    $y = calculateY($date, $m);

    $weekDayNumber = ($d + intval(2.6 * $m - 0.2) + $y + intval($y / 4) + intval($c / 4) - 2 * $c) % 7;

    if ($debug) {
        echo "DEBUG: m={$m} y={$y} c={$c}\n";
    }

    return $weekDayNumber;
}

/**
 * @param $date
 * @param int $m
 * @return bool|string
 */
function calculateY($date, int $m) {
    if ($m >= 11) {
        $y = substr($date->year - 1, 2, 2);
    } else {
        $y = substr($date->year, 2, 2);
    }
    return $y;
}

/**
 * @param $date
 * @param int $m
 * @return bool|string
 */
function calculateC($date, int $m) {
    if ($m >= 11) {
        $c = substr($date->year - 1, 0, 2);
    } else {
        $c = substr($date->year, 0, 2);
    }
    return $c;
}

/**
 * @param $date
 * @return int
 */
function calculateM($date): int {
    // remove and add 1 before and after the modulo because we have 1-based counting
    $m = (($date->month - 2 - 1) + 12) % 12 + 1;
    return $m;
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
 * @throws Exception
 */
function main($argv): int {
    $exitCode=0;

    setlocale(LC_TIME, 'de_AT.utf-8');

    try {
        list($inputDate, $debug) = handleCommandLine($argv);

        $weekDayNumber = dateToWeekdayNumber($inputDate, $debug);
        $weekDay = weekdayNumberToWeekday($weekDayNumber);

        outputResult($inputDate, $weekDay);
    } catch (Exception $e) {
        echo $e->getMessage();
        $exitCode=1;
    }

    return $exitCode;
}

return main($argv);

