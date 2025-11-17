<?php

namespace App\View\Components\Schedule;

use Carbon\Carbon;
use Illuminate\View\Component;

class Weekly extends Component
{
    public array $days;
    public array $hours;
    public array $timeSlots;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name = 'availability',
        public array $value = [],
        public bool $showDates = false,
        public int $startHour = 0,
        public int $endHour = 24,
        public int $interval = 30
    ) {
        $this->days = $this->getDaysArray();
        $this->hours = $this->getHoursArray();
        $this->timeSlots = $this->getTimeSlotsArray();
    }

    /**
     * Get the array of days for the week.
     */
    private function getDaysArray(): array
    {
        $startOfWeek = now()->startOfWeek();
        $days = [];

        // Spanish day names (Monday to Friday only)
        $spanishDays = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie'];

        // Only Monday to Friday (5 days)
        for ($i = 0; $i < 5; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $days[] = [
                'short_name' => $spanishDays[$i],
                'number' => $day->format('d'),
                'full_date' => $day->format('Y-m-d'),
                'is_today' => $day->isToday(),
            ];
        }
        return $days;
    }

    /**
     * Get the array of hours to display.
     */
    private function getHoursArray(): array
    {
        $hours = [];
        for ($h = $this->startHour; $h < $this->endHour; $h++) {
            $hours[] = Carbon::createFromTime($h)->format('gA');
        }
        return $hours;
    }

    /**
     * Get the array of time slots for a day.
     */
    private function getTimeSlotsArray(): array
    {
        $slots = [];
        $start = Carbon::today()->startOfDay()->addHours($this->startHour);
        $end = Carbon::today()->startOfDay()->addHours($this->endHour);

        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes($this->interval);
        }
        return $slots;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Illuminate\Contracts\View\View|\Closure|string
    {
        return view('components.schedule.weekly');
    }
}
