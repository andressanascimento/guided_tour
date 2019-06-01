<?php

namespace GuidedTour;

class Activities
{
    public static $activities = [];

    /**
     * Fill the activities array
     *
     * @param  array $activities
     *
     * @return void
     */
    public static function loadActivities(array $activities)
    {
        self::$activities = $activities;
    }

    /**
     * Search an activity where the budget and time are smaller or equal to the args
     * If any activity match return false
     * @param  int $budget
     * @param  int $time
     *
     * @return mixed array|boolean
     */
    public static function getActivitiesBy(int $budget, int $time)
    {
        foreach (self::$activities as $key => $activity) {
            if ($activity['price'] <= $budget && $activity['duration'] <= $time) {
                unset(self::$activities[$key]);
                return $activity;
            }
        }
        
        return false;
    }
}