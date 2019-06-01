<?php

namespace GuidedTour;

use GuidedTour\Day;

class Schedule 
{
    private $days;
    private $total_budget;
    private $budget_per_day;
    private $total_budget_available;
    private $total_budget_spent;
    private $total_time_spent;
    private $itinerary;
    private $current_day;


    /**
     *
     * @param  int $budget
     * @param  int $days
     *
     * @return Schedule
     */
    public function __construct(int $budget, int $days)
    {
        $this->total_budget = $budget;
        $this->total_budget_available = $budget;
        $this->days = $days;
        $this->remain_days = $days;
        $this->itinerary = [
            "summary" => [
                "budget_spent" => 0,
                "time_spent" => 0
            ]
        ];
        $this->current_day = 1;
    }

    /**
     * Calculate the budget for the next day planning
     * Budget = (Total Budget - Budget Spent) / Remain days to plan
     * @throws \Exception if the calculate budget smaller than 50
     * @return float
     */
    private function budgetPerDay() : float
    {
        $budget = $this->total_budget_available / $this->remain_days;
        if ($budget < 50) {
            throw new \Exception("Budget per day should be at least 50");
        }
        $this->budget_per_day = $budget; 
        return $budget;
    }

    /**
     * Generate the itinerary
     *
     * @return string
     */
    public function generateItinerary() : string
    {
        try {
            $budget = $this->createGuidedTour();
        } catch (\Exception $e) {
            return json_encode([
                "error" => $e->getMessage()
            ]);
        }

        $itinerary = json_encode([
            "schedule" => $this->itinerary
        ]);
        
        return $itinerary;
    }

    
    /**
     * Create the guided tour day by day
     *
     * @return void
     */
    private function createGuidedTour() {

        for ($i = 0; $i < $this->days; $i++) {
            $budget = $this->budgetPerDay();

            $day = new Day();
            $activities = $day->createDay($budget);
            $total_spent = $day->totalBudgetSpent();
            $total_time = $day->totalTimeSpent();

            $this->addNewDayToItinerary($activities, $total_spent, $total_time);
            $this->remain_days -= 1;
            $this->total_budget_available -= $total_spent;
            $this->current_day += 1;
        }
    }

    /**
     * Update the itinerary adding a new day
     *
     * @param  array $activities
     * @param  array $total_budget_spent
     * @param  array $total_time_spent
     *
     * @return void
     */
    public function addNewDayToItinerary(array $activities, int $total_budget_spent, int $total_time_spent)
    {
        $this->itinerary["summary"]["budget_spent"] += $total_budget_spent;
        $this->itinerary["summary"]["time_spent"] += $total_time_spent;
        $this->itinerary["days"][] = [
            "day" => $this->current_day,
            "itinerary" => $activities
        ];
    }
    
}