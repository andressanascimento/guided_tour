<?php

namespace GuidedTour;

use GuidedTour\Activities;

class Day
{
    private $activities;

    public function __construct()
    {
        $this->activities = [];
    }

    /**
     * Returns the itinerary of a day based on a budget
     * 
     * @param  int $budget
     *
     * @return array
     */
    public function createDay(int $budget) : array
    {
        // total hours available on a tour
        $total_hour = 720;
        $total_activities_per_day = 3;
        for ($i = 0; $i < 3; $i++) {
            /*
             *  The total hour for the next activity will be: 
             *  The remain hours divide by the remain number of activities to plan less 30 minutes need between activities
             */
            $total_hour_per_activity = ($total_hour / $total_activities_per_day) - 30;
            /*
             *  The total budget for the next activity will be: 
             *  The remain budget divide by the remain number of activities to plan
             */
            $budget_per_activity = $budget / $total_activities_per_day;
            
            $activity = Activities::getActivitiesBy($budget_per_activity, $total_hour_per_activity);
            $budget -= $activity['price'];
            $total_hour -= $activity['duration'] + 30;

            if ($activity) {
                $this->setActivity($activity);
            }

            $total_activities_per_day--;
        }

        return $this->createItinerary();
    
    }

    /**
     * Add an activity to the itinerary
     *
     * @param  array $activity
     *
     * @return void
     */
    private function setActivity(array $activity) {
        $this->activities[] = $activity;
    }

    /**
     * Return the total budget spent on the day itinerary
     *
     * @return int
     */
    public function totalBudgetSpent() : int
    {
        $sum = 0;
        foreach ($this->activities as $activity) {
            $sum += $activity['price']; 
        }
        return $sum;
    }

    /**
     * Return the total time spent on the day itinerary
     *
     * @return int
     */
    public function totalTimeSpent() : int
    {
        $sum = 0;
        foreach ($this->activities as $activity) {
            $sum += $activity['duration']; 
        }
        return $sum;
    }

    
    /**
     * Format the itinerary array adding hours specifications
     * Every day tour starts at 10:00. Between every activity should be an interval of 30 minutes
     * @return array
     */
    private function createItinerary() : array
    {
        $itinerary = [];
        $last_date = \DateTime::createFromFormat('H:i','10:00');
        foreach($this->activities as $activity) {
            $itinerary[] = [
                "start" => $last_date->format('H:i'),
                "activity" => $activity
            ];

            $total_activity_time = $activity['duration'] + 30;
            $format = 'PT'.$total_activity_time.'M';
            $last_date->add(new \DateInterval($format));
        }

        return $itinerary;
    }
}