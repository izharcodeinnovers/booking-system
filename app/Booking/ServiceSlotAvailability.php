<?php

namespace App\Booking;

use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
            $availability = (new ScheduleAvailability($employee, $this->service))
                ->forPeriod($startsAt , $endsAt);
            // Todo remove appointments from the period collection
            // Todo add the availability employees to the $range
            // Todo remove empty slots
        });
        return $range;


    }

}
