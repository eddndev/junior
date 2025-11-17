<x-dashboard-layout title="Calendario">
    <x-slot name="sidebar">
        <x-navigation.sidebar />
    </x-slot>

    {{-- Alpine.js Calendar App Definition --}}
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('calendarApp', () => ({
            currentDate: new Date(),
            currentView: 'day',
            miniCalendarDate: new Date(),
            events: [],
            loading: false,

            viewLabels: {
                day: 'Vista de día',
                week: 'Vista de semana',
                month: 'Vista de mes',
                year: 'Vista de año'
            },

            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            shortDayNames: ['D', 'L', 'M', 'X', 'J', 'V', 'S'],

            init() {
                this.miniCalendarDate = new Date(this.currentDate);
                // Load saved view preference from localStorage
                const savedView = localStorage.getItem('calendar_view');
                if (savedView && ['day', 'week', 'month'].includes(savedView)) {
                    this.currentView = savedView;
                }
                this.fetchEvents();
            },

            async fetchEvents() {
                this.loading = true;

                try {
                    let startDate, endDate;

                    if (this.currentView === 'day') {
                        startDate = new Date(this.currentDate);
                        startDate.setHours(0, 0, 0, 0);
                        endDate = new Date(this.currentDate);
                        endDate.setHours(23, 59, 59, 999);
                    } else if (this.currentView === 'week') {
                        const weekDays = this.getWeekDays();
                        startDate = weekDays[0].date;
                        endDate = new Date(weekDays[6].date);
                        endDate.setHours(23, 59, 59, 999);
                    } else if (this.currentView === 'month') {
                        const monthDays = this.getMonthDays();
                        startDate = monthDays[0].date;
                        endDate = new Date(monthDays[monthDays.length - 1].date);
                        endDate.setHours(23, 59, 59, 999);
                    } else {
                        startDate = new Date(this.currentDate);
                        startDate.setHours(0, 0, 0, 0);
                        endDate = new Date(this.currentDate);
                        endDate.setHours(23, 59, 59, 999);
                    }

                    const response = await fetch(
                        `{{ route('calendar.events.api') }}?start=${this.formatDateForApi(startDate)}&end=${this.formatDateForApi(endDate)}`
                    );

                    if (!response.ok) throw new Error('Failed to fetch events');

                    this.events = await response.json();
                } catch (error) {
                    console.error('Error fetching events:', error);
                    this.events = [];
                } finally {
                    this.loading = false;
                }
            },

            formatDateForApi(date) {
                // Use local date to avoid timezone issues
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            },

            formatDateShort(date) {
                const options = { month: 'short', day: 'numeric', year: 'numeric' };
                return date.toLocaleDateString('es-ES', options);
            },

            formatDateLong(date) {
                const options = { month: 'long', day: 'numeric', year: 'numeric' };
                const formatted = date.toLocaleDateString('es-ES', options);
                return formatted.charAt(0).toUpperCase() + formatted.slice(1);
            },

            getDayName(date) {
                return this.dayNames[date.getDay()];
            },

            isToday(date) {
                const today = new Date();
                return this.isSameDay(date, today);
            },

            isSameDay(date1, date2) {
                return date1.getDate() === date2.getDate() &&
                       date1.getMonth() === date2.getMonth() &&
                       date1.getFullYear() === date2.getFullYear();
            },

            previousDay() {
                const newDate = new Date(this.currentDate);
                newDate.setDate(newDate.getDate() - 1);
                this.setCurrentDate(newDate);
            },

            nextDay() {
                const newDate = new Date(this.currentDate);
                newDate.setDate(newDate.getDate() + 1);
                this.setCurrentDate(newDate);
            },

            goToToday() {
                this.setCurrentDate(new Date());
            },

            setCurrentDate(date) {
                this.currentDate = new Date(date);
                this.miniCalendarDate = new Date(date);
                this.fetchEvents();
            },

            setView(view) {
                this.currentView = view;
                // Save view preference to localStorage
                localStorage.setItem('calendar_view', view);
                this.fetchEvents();
            },

            // Navigation methods based on current view
            previous() {
                if (this.currentView === 'day') {
                    this.previousDay();
                } else if (this.currentView === 'week') {
                    this.previousWeek();
                } else if (this.currentView === 'month') {
                    this.previousMonthView();
                }
            },

            next() {
                if (this.currentView === 'day') {
                    this.nextDay();
                } else if (this.currentView === 'week') {
                    this.nextWeek();
                } else if (this.currentView === 'month') {
                    this.nextMonthView();
                }
            },

            previousWeek() {
                const newDate = new Date(this.currentDate);
                newDate.setDate(newDate.getDate() - 7);
                this.setCurrentDate(newDate);
            },

            nextWeek() {
                const newDate = new Date(this.currentDate);
                newDate.setDate(newDate.getDate() + 7);
                this.setCurrentDate(newDate);
            },

            previousMonthView() {
                const newDate = new Date(this.currentDate);
                newDate.setMonth(newDate.getMonth() - 1);
                this.setCurrentDate(newDate);
            },

            nextMonthView() {
                const newDate = new Date(this.currentDate);
                newDate.setMonth(newDate.getMonth() + 1);
                this.setCurrentDate(newDate);
            },

            getHeaderTitle() {
                if (this.currentView === 'day') {
                    return this.formatDateLong(this.currentDate);
                } else if (this.currentView === 'week') {
                    const weekDays = this.getWeekDays();
                    const start = weekDays[0].date;
                    const end = weekDays[6].date;
                    if (start.getMonth() === end.getMonth()) {
                        return `${start.getDate()} - ${end.getDate()} de ${this.monthNames[start.getMonth()]} ${start.getFullYear()}`;
                    } else {
                        return `${start.getDate()} ${this.monthNames[start.getMonth()].substring(0, 3)} - ${end.getDate()} ${this.monthNames[end.getMonth()].substring(0, 3)} ${end.getFullYear()}`;
                    }
                } else if (this.currentView === 'month') {
                    return `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
                }
                return '';
            },

            getHeaderSubtitle() {
                if (this.currentView === 'day') {
                    return this.getDayName(this.currentDate);
                } else if (this.currentView === 'week') {
                    return 'Semana ' + this.getWeekNumber(this.currentDate);
                } else if (this.currentView === 'month') {
                    return this.currentDate.getFullYear();
                }
                return '';
            },

            getWeekNumber(date) {
                const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
                const dayNum = d.getUTCDay() || 7;
                d.setUTCDate(d.getUTCDate() + 4 - dayNum);
                const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
                return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
            },

            getMonthDays() {
                const days = [];
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);

                // Start from Monday
                let startDay = firstDay.getDay() - 1;
                if (startDay < 0) startDay = 6;

                // Add days from previous month
                for (let i = startDay - 1; i >= 0; i--) {
                    const date = new Date(year, month, -i);
                    days.push({
                        date: date,
                        isCurrentMonth: false
                    });
                }

                // Add days from current month
                for (let i = 1; i <= lastDay.getDate(); i++) {
                    days.push({
                        date: new Date(year, month, i),
                        isCurrentMonth: true
                    });
                }

                // Add days from next month to complete 6 weeks
                const remainingDays = 42 - days.length;
                for (let i = 1; i <= remainingDays; i++) {
                    days.push({
                        date: new Date(year, month + 1, i),
                        isCurrentMonth: false
                    });
                }

                return days;
            },

            getEventsForDate(date) {
                return this.events.filter(event => {
                    if (!event.start) return false;
                    // Parse date string to avoid timezone issues
                    const dateStr = event.start.split('T')[0];
                    const [year, month, day] = dateStr.split('-').map(Number);
                    const eventDate = new Date(year, month - 1, day);
                    return this.isSameDay(eventDate, date);
                }).slice(0, 3); // Max 3 events visible
            },

            getEventCountForDate(date) {
                return this.events.filter(event => {
                    if (!event.start) return false;
                    // Parse date string to avoid timezone issues
                    const dateStr = event.start.split('T')[0];
                    const [year, month, day] = dateStr.split('-').map(Number);
                    const eventDate = new Date(year, month - 1, day);
                    return this.isSameDay(eventDate, date);
                }).length;
            },

            get weekAllDayEvents() {
                const weekDays = this.getWeekDays();
                return weekDays.map((day, colIndex) => {
                    return this.events
                        .filter(event => {
                            if (!event.start || !event.allDay) return false;
                            const dateStr = event.start.split('T')[0];
                            const [year, month, dayNum] = dateStr.split('-').map(Number);
                            const eventDate = new Date(year, month - 1, dayNum);
                            return this.isSameDay(eventDate, day.date);
                        })
                        .map(event => ({
                            ...event,
                            colIndex: colIndex + 1
                        }));
                }).flat();
            },

            get weekEvents() {
                const weekDays = this.getWeekDays();
                return weekDays.map((day, colIndex) => {
                    return this.events
                        .filter(event => {
                            if (!event.start) return false;
                            // Parse date string to avoid timezone issues
                            const dateStr = event.start.split('T')[0];
                            const [year, month, dayNum] = dateStr.split('-').map(Number);
                            const eventDate = new Date(year, month - 1, dayNum);
                            return this.isSameDay(eventDate, day.date);
                        })
                        .filter(event => !event.allDay)
                        .map(event => {
                            const startParts = event.start.split('T');
                            if (startParts.length < 2) return null;

                            const timeParts = startParts[1].split(':');
                            const startHour = parseInt(timeParts[0]);
                            const startMinute = parseInt(timeParts[1] || 0);

                            const gridRow = 2 + (startHour * 12) + Math.floor(startMinute / 5);

                            let gridSpan = 12;
                            if (event.end) {
                                const endParts = event.end.split('T');
                                if (endParts.length >= 2) {
                                    const endTimeParts = endParts[1].split(':');
                                    const endHour = parseInt(endTimeParts[0]);
                                    const endMinute = parseInt(endTimeParts[1] || 0);

                                    const startTotal = startHour * 60 + startMinute;
                                    const endTotal = endHour * 60 + endMinute;
                                    const durationMinutes = endTotal - startTotal;

                                    gridSpan = Math.ceil(durationMinutes / 5);
                                }
                            }

                            const displayTime = this.formatTime(startHour, startMinute);

                            return {
                                ...event,
                                colIndex: colIndex + 1,
                                gridRow,
                                gridSpan,
                                displayTime
                            };
                        })
                        .filter(event => event !== null);
                }).flat();
            },

            previousMonth() {
                this.miniCalendarDate = new Date(
                    this.miniCalendarDate.getFullYear(),
                    this.miniCalendarDate.getMonth() - 1,
                    1
                );
            },

            nextMonth() {
                this.miniCalendarDate = new Date(
                    this.miniCalendarDate.getFullYear(),
                    this.miniCalendarDate.getMonth() + 1,
                    1
                );
            },

            getMiniCalendarTitle() {
                const month = this.monthNames[this.miniCalendarDate.getMonth()];
                const year = this.miniCalendarDate.getFullYear();
                return `${month} ${year}`;
            },

            getMiniCalendarDays() {
                const days = [];
                const year = this.miniCalendarDate.getFullYear();
                const month = this.miniCalendarDate.getMonth();

                // First day of the month
                const firstDay = new Date(year, month, 1);
                // Last day of the month
                const lastDay = new Date(year, month + 1, 0);

                // Start from Monday (adjust for Spanish week starting Monday)
                let startDay = firstDay.getDay() - 1;
                if (startDay < 0) startDay = 6;

                // Add days from previous month
                for (let i = startDay - 1; i >= 0; i--) {
                    const date = new Date(year, month, -i);
                    days.push({
                        date: date,
                        isCurrentMonth: false
                    });
                }

                // Add days from current month
                for (let i = 1; i <= lastDay.getDate(); i++) {
                    days.push({
                        date: new Date(year, month, i),
                        isCurrentMonth: true
                    });
                }

                // Add days from next month to complete 6 weeks (42 days)
                const remainingDays = 42 - days.length;
                for (let i = 1; i <= remainingDays; i++) {
                    days.push({
                        date: new Date(year, month + 1, i),
                        isCurrentMonth: false
                    });
                }

                return days;
            },

            getWeekDays() {
                const days = [];
                const current = new Date(this.currentDate);

                // Get Monday of current week
                const monday = new Date(current);
                const dayOfWeek = current.getDay();
                const diff = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
                monday.setDate(current.getDate() + diff);

                // Generate 7 days starting from Monday
                for (let i = 0; i < 7; i++) {
                    const date = new Date(monday);
                    date.setDate(monday.getDate() + i);
                    days.push({
                        date: date,
                        shortName: this.shortDayNames[date.getDay()]
                    });
                }

                return days;
            },

            get allDayEvents() {
                return this.events.filter(event => event.allDay);
            },

            get dayEvents() {
                return this.events
                    .filter(event => !event.allDay && event.start)
                    .map(event => {
                        const startParts = event.start.split('T');
                        if (startParts.length < 2) return null;

                        const timeParts = startParts[1].split(':');
                        const startHour = parseInt(timeParts[0]);
                        const startMinute = parseInt(timeParts[1] || 0);

                        // Calculate grid row (each hour = 12 rows in 288-row grid, header offset = 2)
                        const gridRow = 2 + (startHour * 12) + Math.floor(startMinute / 5);

                        // Calculate duration in 5-minute increments
                        let gridSpan = 12; // Default 1 hour

                        if (event.end) {
                            const endParts = event.end.split('T');
                            if (endParts.length >= 2) {
                                const endTimeParts = endParts[1].split(':');
                                const endHour = parseInt(endTimeParts[0]);
                                const endMinute = parseInt(endTimeParts[1] || 0);

                                const startTotal = startHour * 60 + startMinute;
                                const endTotal = endHour * 60 + endMinute;
                                const durationMinutes = endTotal - startTotal;

                                gridSpan = Math.ceil(durationMinutes / 5);
                            }
                        }

                        // Format display time
                        const displayTime = this.formatTime(startHour, startMinute);

                        return {
                            ...event,
                            gridRow,
                            gridSpan,
                            displayTime
                        };
                    })
                    .filter(event => event !== null);
            },

            formatTime(hour, minute) {
                const period = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                const displayMinute = minute.toString().padStart(2, '0');
                return `${displayHour}:${displayMinute} ${period}`;
            },

            getEventClasses(event) {
                const type = event.extendedProps?.type || event.type || 'event';

                if (type === 'meeting' || event.extendedProps?.isMeeting) {
                    return 'bg-green-50 hover:bg-green-100 dark:bg-green-600/15 dark:hover:bg-green-600/20';
                } else if (type === 'task') {
                    return 'bg-amber-50 hover:bg-amber-100 dark:bg-amber-600/15 dark:hover:bg-amber-600/20';
                } else {
                    return 'bg-blue-50 hover:bg-blue-100 dark:bg-blue-600/15 dark:hover:bg-blue-600/20';
                }
            },

            getEventTitleClass(event) {
                const type = event.extendedProps?.type || event.type || 'event';

                if (type === 'meeting' || event.extendedProps?.isMeeting) {
                    return 'text-green-700 dark:text-green-300';
                } else if (type === 'task') {
                    return 'text-amber-700 dark:text-amber-300';
                } else {
                    return 'text-blue-700 dark:text-blue-300';
                }
            },

            getEventSubtitleClass(event) {
                const type = event.extendedProps?.type || event.type || 'event';

                if (type === 'meeting' || event.extendedProps?.isMeeting) {
                    return 'text-green-500 group-hover:text-green-700 dark:text-green-400 dark:group-hover:text-green-300';
                } else if (type === 'task') {
                    return 'text-amber-500 group-hover:text-amber-700 dark:text-amber-400 dark:group-hover:text-amber-300';
                } else {
                    return 'text-blue-500 group-hover:text-blue-700 dark:text-blue-400 dark:group-hover:text-blue-300';
                }
            },

            getEventTimeClass(event) {
                const type = event.extendedProps?.type || event.type || 'event';

                if (type === 'meeting' || event.extendedProps?.isMeeting) {
                    return 'text-green-500 group-hover:text-green-700 dark:text-green-400 dark:group-hover:text-green-300';
                } else if (type === 'task') {
                    return 'text-amber-500 group-hover:text-amber-700 dark:text-amber-400 dark:group-hover:text-amber-300';
                } else {
                    return 'text-blue-500 group-hover:text-blue-700 dark:text-blue-400 dark:group-hover:text-blue-300';
                }
            },

            openCreateEventDialog() {
                // Format date for Livewire
                const dateStr = this.formatDateForApi(this.currentDate);
                // Dispatch Livewire event
                Livewire.dispatch('openCreateEventDialog', { type: 'event', date: dateStr });
                // Open the dialog
                const dialog = document.getElementById('create-event-dialog');
                if (dialog) dialog.showModal();
            },

            openEventDetail(event) {
                // Check if this is a task (ID starts with 'task-')
                const eventId = event.extendedProps?.id || event.id;
                const eventType = event.extendedProps?.type || event.type;

                if (eventType === 'task' || (typeof eventId === 'string' && eventId.startsWith('task-'))) {
                    // This is a task, open task detail dialog
                    const taskId = typeof eventId === 'string' ? parseInt(eventId.replace('task-', '')) : eventId;
                    Livewire.dispatch('openTaskDetail', { taskId: taskId });
                } else if (eventId) {
                    // This is a calendar event, open the dialog
                    Livewire.dispatch('loadCalendarEvent', { eventId: parseInt(eventId) });
                    const dialog = document.getElementById('event-detail-dialog');
                    if (dialog) dialog.showModal();
                } else if (event.url) {
                    window.location.href = event.url;
                }
            },

            openEditEventDialog(eventId) {
                // Dispatch Livewire event
                Livewire.dispatch('loadCalendarEventForEdit', { eventId: parseInt(eventId) });
                // Open the dialog
                const dialog = document.getElementById('edit-event-dialog');
                if (dialog) dialog.showModal();
            },

            openAttendanceDialog(eventId) {
                // Dispatch Livewire event
                Livewire.dispatch('loadEventForAttendance', { eventId: parseInt(eventId) });
                // Open the dialog
                const dialog = document.getElementById('attendance-dialog');
                if (dialog) dialog.showModal();
            }
        }));
    });
    </script>

    <div
        x-data="calendarApp"
        class="flex h-[calc(100vh-10rem)] flex-col"
    >
        {{-- Header Section --}}
        <header class="flex flex-none items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-white/10 dark:bg-gray-800/50">
            <div>
                <h1 class="text-base font-semibold text-gray-900 dark:text-white">
                    <time :datetime="currentDate.toISOString().split('T')[0]" x-text="getHeaderTitle()"></time>
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="getHeaderSubtitle()"></p>
            </div>

            <div class="flex items-center">
                {{-- Navigation Buttons --}}
                <div class="relative flex items-center rounded-md bg-white shadow-xs outline -outline-offset-1 outline-gray-300 md:items-stretch dark:bg-white/10 dark:shadow-none dark:outline-white/5">
                    <button
                        type="button"
                        @click="previous()"
                        class="flex h-9 w-12 items-center justify-center rounded-l-md pr-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pr-0 md:hover:bg-gray-50 dark:hover:text-white dark:md:hover:bg-white/10"
                    >
                        <span class="sr-only">Anterior</span>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                            <path d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="goToToday()"
                        class="hidden px-3.5 text-sm font-semibold text-gray-900 hover:bg-gray-50 focus:relative md:block dark:text-white dark:hover:bg-white/10"
                    >
                        Hoy
                    </button>
                    <span class="relative -mx-px h-5 w-px bg-gray-300 md:hidden dark:bg-white/10"></span>
                    <button
                        type="button"
                        @click="next()"
                        class="flex h-9 w-12 items-center justify-center rounded-r-md pl-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pl-0 md:hover:bg-gray-50 dark:hover:text-white dark:md:hover:bg-white/10"
                    >
                        <span class="sr-only">Siguiente</span>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                            <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                {{-- View Selector (Desktop) --}}
                <div class="hidden md:ml-4 md:flex md:items-center">
                    <el-dropdown class="relative">
                        <button type="button" class="flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20">
                            <span x-text="viewLabels[currentView]"></span>
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="-mr-1 size-5 text-gray-400 dark:text-gray-500">
                                <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </button>

                        <el-menu anchor="bottom end" popover class="w-40 origin-top-right overflow-hidden rounded-md bg-white shadow-lg outline-1 outline-black/5 transition transition-discrete [--anchor-gap:--spacing(3)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in dark:bg-gray-800 dark:-outline-offset-1 dark:outline-white/10">
                            <div class="py-1">
                                <button @click="setView('day')" :class="{ 'bg-gray-100 dark:bg-white/10': currentView === 'day' }" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 dark:focus:bg-white/5 dark:focus:text-white">Vista de día</button>
                                <button @click="setView('week')" :class="{ 'bg-gray-100 dark:bg-white/10': currentView === 'week' }" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 dark:focus:bg-white/5 dark:focus:text-white">Vista de semana</button>
                                <button @click="setView('month')" :class="{ 'bg-gray-100 dark:bg-white/10': currentView === 'month' }" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 dark:focus:bg-white/5 dark:focus:text-white">Vista de mes</button>
                            </div>
                        </el-menu>
                    </el-dropdown>
                    <div class="ml-6 h-6 w-px bg-gray-300 dark:bg-white/10"></div>
                    @can('create', App\Models\CalendarEvent::class)
                    <button
                        type="button"
                        @click="openCreateEventDialog()"
                        class="ml-6 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:shadow-none dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500"
                    >
                        Agregar evento
                    </button>
                    @endcan
                </div>

                {{-- Mobile Menu --}}
                <div class="ml-6 md:hidden">
                    <el-dropdown class="relative">
                        <button class="relative flex items-center rounded-full text-gray-400 outline-offset-8 hover:text-gray-500 dark:text-gray-500 dark:hover:text-white">
                            <span class="absolute -inset-2"></span>
                            <span class="sr-only">Abrir menú</span>
                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                                <path d="M3 10a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM8.5 10a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM15.5 8.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z" />
                            </svg>
                        </button>

                        <el-menu anchor="bottom end" popover class="w-40 origin-top-right divide-y divide-gray-100 overflow-hidden rounded-md bg-white shadow-lg outline-1 outline-black/5 transition transition-discrete [--anchor-gap:--spacing(3)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in dark:divide-white/10 dark:bg-gray-800 dark:-outline-offset-1 dark:outline-white/10">
                            @can('create', App\Models\CalendarEvent::class)
                            <div class="py-1">
                                <button @click="openCreateEventDialog()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 dark:focus:bg-white/5 dark:focus:text-white">Crear evento</button>
                            </div>
                            @endcan
                            <div class="py-1">
                                <button @click="goToToday()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 dark:focus:bg-white/5 dark:focus:text-white">Ir a hoy</button>
                            </div>
                            <div class="py-1">
                                <button @click="setView('day')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 dark:focus:bg-white/5 dark:focus:text-white">Vista de día</button>
                                <button @click="setView('week')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 dark:focus:bg-white/5 dark:focus:text-white">Vista de semana</button>
                                <button @click="setView('month')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:hover:bg-white/5 dark:focus:bg-white/5 dark:focus:text-white">Vista de mes</button>
                            </div>
                        </el-menu>
                    </el-dropdown>
                </div>
            </div>
        </header>

        {{-- Main Content Area --}}
        <div class="isolate flex flex-auto overflow-hidden bg-white dark:bg-gray-900">
            {{-- DAY VIEW --}}
            <template x-if="currentView === 'day'">
                <div class="flex flex-auto flex-col overflow-auto">
                    {{-- Mobile Week Day Selector --}}
                    <div class="sticky top-0 z-10 grid flex-none grid-cols-7 bg-white text-xs text-gray-500 shadow-sm ring-1 ring-black/5 md:hidden dark:bg-gray-900 dark:text-gray-400 dark:shadow-none dark:ring-white/20">
                        <template x-for="(day, index) in getWeekDays()" :key="index">
                            <button
                                type="button"
                                @click="setCurrentDate(day.date)"
                                class="flex flex-col items-center pt-3 pb-1.5"
                            >
                                <span x-text="day.shortName"></span>
                                <span
                                    :class="{
                                        'bg-gray-900 text-white dark:bg-white dark:text-gray-900': isSameDay(day.date, currentDate),
                                        'text-indigo-600 dark:text-indigo-400': isToday(day.date) && !isSameDay(day.date, currentDate),
                                        'text-gray-900 dark:text-white': !isToday(day.date) && !isSameDay(day.date, currentDate)
                                    }"
                                    class="mt-3 flex size-8 items-center justify-center rounded-full text-base font-semibold"
                                    x-text="day.date.getDate()"
                                ></span>
                            </button>
                        </template>
                    </div>

                    {{-- All Day Events Section --}}
                    <div x-show="allDayEvents.length > 0" class="flex-none border-b border-gray-200 bg-gray-50 dark:border-white/10 dark:bg-gray-800/50">
                        <div class="flex">
                            <div class="w-14 flex-none"></div>
                            <div class="flex-auto px-3 py-2">
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Todo el día</div>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="event in allDayEvents" :key="event.id">
                                        <button
                                            @click="openEventDetail(event)"
                                            :class="{
                                                'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300': event.type === 'task',
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300': event.type !== 'task'
                                            }"
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium hover:opacity-80"
                                        >
                                            <svg x-show="event.type === 'task'" class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                            <span x-text="event.title"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Day View Grid --}}
                    <div class="flex w-full flex-auto">
                        {{-- Time Column --}}
                        <div class="w-14 flex-none bg-white ring-1 ring-gray-100 dark:bg-gray-900 dark:ring-white/5"></div>

                        {{-- Grid Container --}}
                        <div class="grid flex-auto grid-cols-1 grid-rows-1">
                        {{-- Horizontal Time Lines --}}
                        <div style="grid-template-rows: repeat(48, minmax(3.5rem, 1fr))" class="col-start-1 col-end-2 row-start-1 grid divide-y divide-gray-100 dark:divide-white/5">
                            <div class="row-end-1 h-7"></div>
                            {{-- Time Labels (12AM to 11PM) --}}
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">12AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">1AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">2AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">3AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">4AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">5AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">6AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">7AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">8AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">9AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">10AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">11AM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">12PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">1PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">2PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">3PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">4PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">5PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">6PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">7PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">8PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">9PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">10PM</div>
                            </div>
                            <div></div>
                            <div>
                                <div class="-mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500">11PM</div>
                            </div>
                            <div></div>
                        </div>

                        {{-- Events Layer --}}
                        <ol style="grid-template-rows: 1.75rem repeat(288, minmax(0, 1fr)) auto" class="col-start-1 col-end-2 row-start-1 grid grid-cols-1">
                            <template x-for="event in dayEvents" :key="event.id">
                                <li
                                    :style="'grid-row: ' + event.gridRow + ' / span ' + event.gridSpan"
                                    class="relative mt-px flex dark:before:pointer-events-none dark:before:absolute dark:before:inset-1 dark:before:z-0 dark:before:rounded-lg dark:before:bg-gray-900"
                                >
                                    <button
                                        type="button"
                                        @click="openEventDetail(event)"
                                        :class="getEventClasses(event)"
                                        class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg p-2 text-xs/5 text-left"
                                    >
                                        <p class="order-1 font-semibold" :class="getEventTitleClass(event)" x-text="event.title"></p>
                                        <p x-show="event.location" class="order-1" :class="getEventSubtitleClass(event)" x-text="event.location"></p>
                                        <p :class="getEventTimeClass(event)" x-text="event.displayTime"></p>
                                    </button>
                                </li>
                            </template>
                        </ol>
                    </div>
                </div>
            </div>
            </template>

            {{-- WEEK VIEW --}}
            <template x-if="currentView === 'week'">
                <div class="flex flex-auto flex-col overflow-auto">
                    <div style="width: 165%" class="flex max-w-full flex-none flex-col sm:max-w-none md:max-w-full">
                        {{-- Week Header --}}
                        <div class="sticky top-0 z-30 flex-none bg-white shadow-sm ring-1 ring-black/5 sm:pr-8 dark:bg-gray-900 dark:shadow-none dark:ring-white/20">
                            <div class="-mr-px hidden grid-cols-7 divide-x divide-gray-100 border-r border-gray-100 text-sm/6 text-gray-500 sm:grid dark:divide-white/10 dark:border-white/10 dark:text-gray-400">
                                <div class="col-end-1 w-14"></div>
                                <template x-for="(day, index) in getWeekDays()" :key="index">
                                    <div class="flex items-center justify-center py-3">
                                        <span :class="{ 'flex items-baseline': isToday(day.date) }">
                                            <span x-text="['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'][index]" class="mr-1.5"></span>
                                            <span
                                                :class="{
                                                    'ml-1.5 flex size-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white dark:bg-indigo-500': isToday(day.date),
                                                    'items-center justify-center font-semibold text-gray-900 dark:text-white': !isToday(day.date)
                                                }"
                                                x-text="day.date.getDate()"
                                            ></span>
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- All Day Events Row --}}
                        <div x-show="weekAllDayEvents.length > 0" class="flex-none border-b border-gray-200 bg-gray-50 dark:border-white/10 dark:bg-gray-800/50">
                            <div class="flex">
                                <div class="sticky left-0 z-10 w-14 flex-none bg-gray-50 py-2 pr-2 text-right text-xs text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                                    Todo<br>el día
                                </div>
                                <div class="grid flex-auto grid-cols-7 divide-x divide-gray-100 pr-8 dark:divide-white/10">
                                    <template x-for="(day, index) in getWeekDays()" :key="'allday-' + index">
                                        <div class="px-1 py-1 min-h-[2rem]">
                                            <template x-for="event in weekAllDayEvents.filter(e => e.colIndex === index + 1)" :key="event.id">
                                                <button
                                                    @click="openEventDetail(event)"
                                                    :class="{
                                                        'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300': event.type === 'task',
                                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300': event.type !== 'task'
                                                    }"
                                                    class="block w-full mb-1 rounded px-1 py-0.5 text-xs font-medium truncate text-left hover:opacity-80"
                                                    x-text="event.title"
                                                ></button>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Week Grid --}}
                        <div class="flex flex-auto">
                            <div class="sticky left-0 z-10 w-14 flex-none bg-white ring-1 ring-gray-100 dark:bg-gray-900 dark:ring-white/5"></div>
                            <div class="grid flex-auto grid-cols-1 grid-rows-1">
                                {{-- Horizontal Time Lines --}}
                                <div style="grid-template-rows: repeat(48, minmax(3.5rem, 1fr))" class="col-start-1 col-end-2 row-start-1 grid divide-y divide-gray-100 dark:divide-white/5">
                                    <div class="row-end-1 h-7"></div>
                                    <template x-for="hour in 24" :key="hour">
                                        <template x-if="true">
                                            <div>
                                                <div class="sticky left-0 z-20 -mt-2.5 -ml-14 w-14 pr-2 text-right text-xs/5 text-gray-400 dark:text-gray-500" x-text="hour === 1 ? '12AM' : hour <= 12 ? (hour-1) + 'AM' : hour === 13 ? '12PM' : (hour-13) + 'PM'"></div>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-for="i in 24" :key="'empty-' + i">
                                        <div></div>
                                    </template>
                                </div>

                                {{-- Vertical Day Lines --}}
                                <div class="col-start-1 col-end-2 row-start-1 hidden grid-rows-1 divide-x divide-gray-100 sm:grid sm:grid-cols-7 dark:divide-white/5">
                                    <template x-for="col in 8" :key="col">
                                        <div :class="'col-start-' + col + ' row-span-full' + (col === 8 ? ' w-8' : '')"></div>
                                    </template>
                                </div>

                                {{-- Week Events --}}
                                <ol style="grid-template-rows: 1.75rem repeat(288, minmax(0, 1fr)) auto" class="col-start-1 col-end-2 row-start-1 grid grid-cols-1 sm:grid-cols-7 sm:pr-8">
                                    <template x-for="event in weekEvents" :key="event.id + '-' + event.colIndex">
                                        <li
                                            :style="'grid-row: ' + event.gridRow + ' / span ' + event.gridSpan + '; grid-column: ' + event.colIndex"
                                            class="relative mt-px flex dark:before:pointer-events-none dark:before:absolute dark:before:inset-1 dark:before:z-0 dark:before:rounded-lg dark:before:bg-gray-900"
                                        >
                                            <button
                                                type="button"
                                                @click="openEventDetail(event)"
                                                :class="getEventClasses(event)"
                                                class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg p-2 text-xs/5 text-left"
                                            >
                                                <p class="order-1 font-semibold" :class="getEventTitleClass(event)" x-text="event.title"></p>
                                                <p :class="getEventTimeClass(event)" x-text="event.displayTime"></p>
                                            </button>
                                        </li>
                                    </template>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- MONTH VIEW --}}
            <template x-if="currentView === 'month'">
                <div class="flex flex-auto flex-col">
                    <div class="shadow-sm ring-1 ring-black/5 lg:flex lg:flex-auto lg:flex-col dark:shadow-none dark:ring-white/5">
                        {{-- Month Day Headers --}}
                        <div class="grid grid-cols-7 gap-px border-b border-gray-300 bg-gray-200 text-center text-xs/6 font-semibold text-gray-700 lg:flex-none dark:border-white/5 dark:bg-white/15 dark:text-gray-300">
                            <div class="flex justify-center bg-white py-2 dark:bg-gray-900">
                                <span>L</span><span class="sr-only sm:not-sr-only">un</span>
                            </div>
                            <div class="flex justify-center bg-white py-2 dark:bg-gray-900">
                                <span>M</span><span class="sr-only sm:not-sr-only">ar</span>
                            </div>
                            <div class="flex justify-center bg-white py-2 dark:bg-gray-900">
                                <span>X</span><span class="sr-only sm:not-sr-only">mi</span>
                            </div>
                            <div class="flex justify-center bg-white py-2 dark:bg-gray-900">
                                <span>J</span><span class="sr-only sm:not-sr-only">ue</span>
                            </div>
                            <div class="flex justify-center bg-white py-2 dark:bg-gray-900">
                                <span>V</span><span class="sr-only sm:not-sr-only">ie</span>
                            </div>
                            <div class="flex justify-center bg-white py-2 dark:bg-gray-900">
                                <span>S</span><span class="sr-only sm:not-sr-only">áb</span>
                            </div>
                            <div class="flex justify-center bg-white py-2 dark:bg-gray-900">
                                <span>D</span><span class="sr-only sm:not-sr-only">om</span>
                            </div>
                        </div>

                        {{-- Month Grid --}}
                        <div class="flex bg-gray-200 text-xs/6 text-gray-700 lg:flex-auto dark:bg-white/10 dark:text-gray-300">
                            {{-- Desktop Month Grid --}}
                            <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
                                <template x-for="(day, index) in getMonthDays()" :key="index">
                                    <div
                                        :class="{
                                            'bg-white dark:bg-gray-900': day.isCurrentMonth,
                                            'bg-gray-50 dark:bg-gray-900/75': !day.isCurrentMonth
                                        }"
                                        class="group relative px-3 py-2 min-h-[100px]"
                                    >
                                        <time
                                            :datetime="day.date.toISOString().split('T')[0]"
                                            :class="{
                                                'flex size-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white dark:bg-indigo-500': isToday(day.date),
                                                'text-gray-900 dark:text-white': day.isCurrentMonth && !isToday(day.date),
                                                'text-gray-500 dark:text-gray-400': !day.isCurrentMonth
                                            }"
                                            x-text="day.date.getDate()"
                                        ></time>
                                        <ol class="mt-2">
                                            <template x-for="event in getEventsForDate(day.date)" :key="event.id">
                                                <li>
                                                    <button @click="openEventDetail(event)" class="group flex w-full text-left">
                                                        <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400" x-text="event.title"></p>
                                                        <time
                                                            :datetime="event.start"
                                                            class="ml-3 hidden flex-none text-gray-500 group-hover:text-indigo-600 xl:block dark:text-gray-400 dark:group-hover:text-indigo-400"
                                                            x-text="event.start && event.start.includes('T') ? formatTime(parseInt(event.start.split('T')[1].split(':')[0]), parseInt(event.start.split('T')[1].split(':')[1] || 0)) : ''"
                                                        ></time>
                                                    </button>
                                                </li>
                                            </template>
                                            <li x-show="getEventCountForDate(day.date) > 3" class="text-gray-500 dark:text-gray-400">
                                                + <span x-text="getEventCountForDate(day.date) - 3"></span> más
                                            </li>
                                        </ol>
                                    </div>
                                </template>
                            </div>

                            {{-- Mobile Month Grid --}}
                            <div class="isolate grid w-full grid-cols-7 grid-rows-6 gap-px lg:hidden">
                                <template x-for="(day, index) in getMonthDays()" :key="'mobile-' + index">
                                    <button
                                        type="button"
                                        @click="setCurrentDate(day.date); setView('day')"
                                        :class="{
                                            'bg-white dark:bg-gray-900': day.isCurrentMonth,
                                            'bg-gray-50 dark:bg-gray-900/75': !day.isCurrentMonth
                                        }"
                                        class="group relative flex h-14 flex-col px-3 py-2 hover:bg-gray-100 focus:z-10 dark:hover:bg-gray-900/50"
                                    >
                                        <time
                                            :datetime="day.date.toISOString().split('T')[0]"
                                            :class="{
                                                'flex size-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white dark:bg-indigo-500': isToday(day.date),
                                                'text-gray-900 dark:text-white font-semibold': day.isCurrentMonth && !isToday(day.date),
                                                'text-gray-500 dark:text-gray-400': !day.isCurrentMonth
                                            }"
                                            class="ml-auto"
                                            x-text="day.date.getDate()"
                                        ></time>
                                        <span x-show="getEventCountForDate(day.date) > 0" class="-mx-0.5 mt-auto flex flex-wrap-reverse">
                                            <template x-for="i in Math.min(getEventCountForDate(day.date), 3)" :key="i">
                                                <span class="mx-0.5 mb-1 size-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                                            </template>
                                        </span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Right side: Mini Calendar (Desktop Only) - Only show in Day view --}}
            <div x-show="currentView === 'day'" class="hidden w-1/2 max-w-md flex-none border-l border-gray-100 px-8 py-10 md:block dark:border-white/10">
                {{-- Mini Calendar Header --}}
                <div class="flex items-center text-center text-gray-900 dark:text-white">
                    <button
                        type="button"
                        @click="previousMonth()"
                        class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-white"
                    >
                        <span class="sr-only">Mes anterior</span>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                            <path d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="flex-auto text-sm font-semibold" x-text="getMiniCalendarTitle()"></div>
                    <button
                        type="button"
                        @click="nextMonth()"
                        class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-white"
                    >
                        <span class="sr-only">Mes siguiente</span>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                            <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                {{-- Mini Calendar Day Headers --}}
                <div class="mt-6 grid grid-cols-7 text-center text-xs/6 text-gray-500 dark:text-gray-400">
                    <div>L</div>
                    <div>M</div>
                    <div>X</div>
                    <div>J</div>
                    <div>V</div>
                    <div>S</div>
                    <div>D</div>
                </div>

                {{-- Mini Calendar Grid --}}
                <div class="isolate mt-2 grid grid-cols-7 gap-px rounded-lg bg-gray-200 text-sm shadow-sm ring-1 ring-gray-200 dark:bg-white/10 dark:shadow-none dark:ring-white/10">
                    <template x-for="(day, index) in getMiniCalendarDays()" :key="index">
                        <button
                            type="button"
                            @click="setCurrentDate(day.date)"
                            :class="[
                                'py-1.5 focus:z-10',
                                index === 0 ? 'rounded-tl-lg' : '',
                                index === 6 ? 'rounded-tr-lg' : '',
                                index === 35 ? 'rounded-bl-lg' : '',
                                index === 41 ? 'rounded-br-lg' : '',
                                day.isCurrentMonth
                                    ? 'bg-white dark:bg-gray-900/90 hover:bg-gray-100 dark:hover:bg-gray-900/50'
                                    : 'bg-gray-50 dark:bg-gray-900/75 hover:bg-gray-100 dark:hover:bg-gray-900/25',
                                isSameDay(day.date, currentDate)
                                    ? 'font-semibold text-white'
                                    : isToday(day.date)
                                        ? 'font-semibold text-indigo-600 dark:text-indigo-400'
                                        : day.isCurrentMonth
                                            ? 'text-gray-900 dark:text-white'
                                            : 'text-gray-400 dark:text-gray-500'
                            ]"
                        >
                            <time
                                :datetime="day.date.toISOString().split('T')[0]"
                                :class="[
                                    'mx-auto flex size-7 items-center justify-center rounded-full',
                                    isSameDay(day.date, currentDate) && isToday(day.date)
                                        ? 'bg-indigo-600 dark:bg-indigo-500'
                                        : isSameDay(day.date, currentDate)
                                            ? 'bg-gray-900 dark:bg-white'
                                            : ''
                                ]"
                                x-text="day.date.getDate()"
                            ></time>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Loading Indicator --}}
        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-900/50">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>
    </div>

    {{-- Dialog wrappers for Livewire components --}}
    <x-dialog-wrapper id="create-event-dialog" max-width="4xl">
        @livewire('calendar.create-event-dialog')
    </x-dialog-wrapper>

    <x-dialog-wrapper id="event-detail-dialog" max-width="4xl">
        @livewire('calendar.event-detail-dialog')
    </x-dialog-wrapper>

    <x-dialog-wrapper id="edit-event-dialog" max-width="4xl">
        @livewire('calendar.edit-event-dialog')
    </x-dialog-wrapper>

    <x-dialog-wrapper id="attendance-dialog" max-width="3xl">
        @livewire('calendar.attendance-dialog')
    </x-dialog-wrapper>

    {{-- Task Detail Dialog --}}
    <x-dialog-wrapper id="task-detail-dialog" max-width="4xl">
        @livewire('tasks.task-detail-dialog')
    </x-dialog-wrapper>
</x-dashboard-layout>

@push('scripts')
<script>
// Listen for Livewire events to refresh calendar
document.addEventListener('livewire:initialized', () => {
    // Helper function to refresh the calendar
    const refreshCalendar = () => {
        const calendarEl = document.querySelector('[x-data="calendarApp"]');
        if (calendarEl && calendarEl._x_dataStack) {
            const alpineData = calendarEl._x_dataStack[0];
            if (alpineData && alpineData.fetchEvents) {
                alpineData.fetchEvents();
            }
        }
    };

    // Refresh calendar when events are created/updated
    Livewire.on('calendar-event-created', refreshCalendar);
    Livewire.on('calendar-event-updated', refreshCalendar);
    Livewire.on('attendance-recorded', refreshCalendar);
});
</script>
@endpush
