@props([
    'name' => 'availability',
    'value' => [],
    'showDates' => false,
    'startHour' => 0,
    'endHour' => 24,
    'interval' => 30,
])

@use('Carbon\Carbon')

<div
    x-data="{
        availability: @js($value),
        days: @js($days),
        timeSlots: @js($timeSlots),
        isDragging: false,
        dragState: null, // 'selecting' or 'deselecting'

        init() {
            // Initialize availability for each day if not present
            this.days.forEach(day => {
                if (!this.availability[day.full_date]) {
                    this.availability[day.full_date] = [];
                }
            });
        },

        toggleSlot(day, slot) {
            const dayAvailability = this.availability[day.full_date];
            const index = dayAvailability.indexOf(slot);

            if (index === -1) {
                dayAvailability.push(slot);
            } else {
                dayAvailability.splice(index, 1);
            }
        },

        startDrag(day, slot) {
            this.isDragging = true;
            const isSelected = this.availability[day.full_date].includes(slot);
            this.dragState = isSelected ? 'deselecting' : 'selecting';
            this.handleDrag(day, slot);
        },

        handleDrag(day, slot) {
            if (!this.isDragging) return;

            const dayAvailability = this.availability[day.full_date];
            const index = dayAvailability.indexOf(slot);

            if (this.dragState === 'selecting' && index === -1) {
                dayAvailability.push(slot);
            } else if (this.dragState === 'deselecting' && index !== -1) {
                dayAvailability.splice(index, 1);
            }
        },

        stopDrag() {
            this.isDragging = false;
            this.dragState = null;
        },

        isSlotSelected(day, slot) {
            return this.availability[day.full_date] && this.availability[day.full_date].includes(slot);
        }
    }"
    @mouseup.window="stopDrag"
    class="isolate flex flex-auto flex-col overflow-auto bg-white dark:bg-gray-900"
>
    <!-- Hidden inputs for form submission -->
    <template x-for="[day, slots] in Object.entries(availability)" :key="day">
        <template x-for="slot in slots" :key="day + '-' + slot">
            <input type="hidden" :name="`{{ $name }}[${day}][]`" :value="slot">
        </template>
    </template>

    <div style="width: 165%" class="flex max-w-full flex-none flex-col sm:max-w-none md:max-w-full">
        <div class="sticky top-0 z-30 flex-none bg-white shadow-sm ring-1 ring-black/5 sm:pr-8 dark:bg-gray-900 dark:shadow-none dark:ring-white/20">
            <div class="-mr-px hidden grid-cols-7 divide-x divide-gray-100 border-r border-gray-100 text-sm/6 text-gray-500 sm:grid dark:divide-white/10 dark:border-white/10 dark:text-gray-400">
                <div class="col-end-1 w-14"></div>
                <template x-for="day in days" :key="day.full_date">
                    <div class="flex items-center justify-center py-3">
                        <span :class="day.is_today ? 'flex items-baseline' : ''">
                            <span x-text="day.short_name"></span>
                            <template x-if="showDates">
                                <span
                                    :class="day.is_today ? 'ml-1.5 flex size-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white dark:bg-indigo-500' : 'items-center justify-center font-semibold text-gray-900 dark:text-white'"
                                    x-text="day.number"
                                ></span>
                            </template>
                        </span>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex flex-auto">
            <div class="sticky left-0 z-10 w-14 flex-none bg-white ring-1 ring-gray-100 dark:bg-gray-900 dark:ring-white/5"></div>
            <div class="grid flex-auto grid-cols-1 grid-rows-1">
                <!-- Horizontal lines -->
                <div
                    :style="`grid-template-rows: repeat({{ ($endHour - $startHour) * (60 / $interval) }}, 1.5rem)`"
                    class="col-start-1 col-end-2 row-start-1 grid divide-y divide-gray-100 dark:divide-white/5"
                >
                    <div class="row-end-1 h-6"></div>
                    @foreach ($hours as $hour)
                        <div style="grid-row-start: {{ ($loop->index * (60 / $interval)) + 2 }}; grid-row-end: span {{ 60 / $interval }};">
                            <div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">{{ $hour }}</div>
                        </div>
                        @for ($i = 0; $i < (60 / $interval); $i++)
                            <div></div>
                        @endfor
                    @endforeach
                </div>

                <!-- Vertical lines -->
                <div class="col-start-1 col-end-2 row-start-1 hidden grid-rows-1 divide-x divide-gray-100 sm:grid sm:grid-cols-7 dark:divide-white/5">
                    <div class="col-start-1 row-span-full"></div>
                    <div class="col-start-2 row-span-full"></div>
                    <div class="col-start-3 row-span-full"></div>
                    <div class="col-start-4 row-span-full"></div>
                    <div class="col-start-5 row-span-full"></div>
                    <div class="col-start-6 row-span-full"></div>
                    <div class="col-start-7 row-span-full"></div>
                    <div class="col-start-8 row-span-full w-8"></div>
                </div>

                <!-- Slots -->
                <div
                    class="col-start-1 col-end-2 row-start-1 grid grid-cols-1 sm:grid-cols-7 sm:pr-8"
                    :style="`grid-template-rows: 1.5rem repeat({{ ($endHour - $startHour) * (60 / $interval) }}, 1.5rem) auto`"
                >
                    <template x-for="(day, dayIndex) in days" :key="day.full_date">
                        <div :style="`grid-column-start: ${dayIndex + 1}`" class="relative">
                            <template x-for="(slot, slotIndex) in timeSlots" :key="slot">
                                <div
                                    :style="`grid-row-start: ${slotIndex + 2}`"
                                    class="relative h-full w-full"
                                    @mousedown="startDrag(day, slot)"
                                    @mouseenter="handleDrag(day, slot)"
                                >
                                    <div :class="{'bg-primary-200/50 dark:bg-primary-500/20': isSlotSelected(day, slot)}" class="absolute inset-0 h-full w-full"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
