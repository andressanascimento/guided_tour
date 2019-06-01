<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuidedTour\Schedule;
use GuidedTour\Activities;

function builtSchedule(string $file, int $budget, int $days) {
    $json = file_get_contents($file);
    $activities = json_decode($json, true);
    $activities_class = Activities::loadActivities($activities);

    $schedule = new Schedule($budget, $days, $activities_class);
    return $schedule->generateItinerary();
}

echo builtSchedule($argv[1], $argv[2], $argv[3]);

