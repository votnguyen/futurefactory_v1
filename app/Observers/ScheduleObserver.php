<?php

namespace App\Observers;

use App\Models\Schedule;

class ScheduleObserver
{
    /**
     * Handle the Schedule "created" event.
     */
    public function created(Schedule $schedule): void
    {
        $this->updateVehicleStatus($schedule);
    }

    /**
     * Handle the Schedule "updated" event.
     */
    public function updated(Schedule $schedule): void
    {
        $this->updateVehicleStatus($schedule);
    }

    /**
     * Handle the Schedule "deleted" event.
     */
    public function deleted(Schedule $schedule): void
    {
        $this->updateVehicleStatus($schedule);
    }

    /**
     * Update the related vehicle's status
     */
    protected function updateVehicleStatus(Schedule $schedule): void
    {
        $schedule->vehicle->updateCompletionStatus();
    }
}