<?php

namespace App\Booking;

use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Period\Boundaries;
use Spatie\Period\Period;
use Spatie\Period\PeriodCollection;
use Spatie\Period\Precision;
class ServiceSlotAvailability
{
    public function __construct(
        protected Collection $employees,
        protected Service $service
    )
    {
    }

    public function forPeriod(Carbon $startsAt , Carbon $endsAt)
    {
        $range = (new SlotRangeGenerater($startsAt, $endsAt))->generate($this->service->duration);
        $this->employees->each(function (Employee $employee) use ($startsAt ,$endsAt, &$range){
            // Todo get the availability for the employee
            $periods = (new ScheduleAvailability($employee, $this->service))
                ->forPeriod($startsAt , $endsAt);
            $periods = $this->removeAppointments($periods, $employee);

            foreach ($periods as $period) {
                $this->addAvailableEmployeeForPeriod($range, $period, $employee);
            }
            // Todo remove appointments from the period collection
            // Todo add the availability employees to the $range
            // Todo remove empty slots
        });
        $range = $this->removeEmptySlots($range);
        return $range;
    }

    protected function removeEmptySlots(Collection $range)
    {
        return $range->filter(function (Date $date) {
            $date->slots = $date->slots->filter(function (Slot $slot) {
                return $slot->hasEmployees();
            });

            return true;
        });
    }

    protected function addAvailableEmployeeForPeriod(Collection $range, Period $period, Employee $employee)
    {
        $range->each(function (Date $date) use ($period, $employee) {
            $date->slots->each(function (Slot $slot) use ($period, $employee) {
                // TODO contains slot time
                if ($period->contains($slot->time)) {
                    $slot->addEmployee($employee);
                }
            });
        });
    }

}
