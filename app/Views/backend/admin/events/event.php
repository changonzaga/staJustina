<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal">
                        <i class="mdi mdi-plus"></i> Add Event
                    </button>
                </div>
                <h4 class="page-title">School Calendar Events</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Calendar Navigation -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" id="prevMonth">
                                    <i class="mdi mdi-chevron-left"></i> Previous
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="today">Today</button>
                                <button type="button" class="btn btn-outline-primary" id="nextMonth">
                                    Next <i class="mdi mdi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <h5 id="currentMonthYear" class="mb-0"></h5>
                        </div>
                    </div>

                    <!-- Event Type Filter -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="eventFilter" id="filterAll" value="all" checked>
                                <label class="btn btn-outline-secondary" for="filterAll">All Events</label>

                                <input type="radio" class="btn-check" name="eventFilter" id="filterAcademic" value="academic">
                                <label class="btn btn-outline-info" for="filterAcademic">Academic</label>

                                <input type="radio" class="btn-check" name="eventFilter" id="filterExam" value="exam">
                                <label class="btn btn-outline-warning" for="filterExam">Exams</label>

                                <input type="radio" class="btn-check" name="eventFilter" id="filterHoliday" value="holiday">
                                <label class="btn btn-outline-success" for="filterHoliday">Holidays</label>

                                <input type="radio" class="btn-check" name="eventFilter" id="filterEvent" value="event">
                                <label class="btn btn-outline-danger" for="filterEvent">Events</label>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="table-responsive">
                        <table class="table table-bordered calendar-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Sunday</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody">
                                <!-- Calendar days will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events Widget -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upcoming Events</h5>
                </div>
                <div class="card-body">
                    <div id="upcomingEvents">
                        <!-- Upcoming events will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="eventForm">
                <div class="modal-body">
                    <input type="hidden" id="eventId" name="event_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="eventTitle" class="form-label">Event Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="eventTitle" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="eventType" class="form-label">Event Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="eventType" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="academic">Academic</option>
                                    <option value="exam">Exam</option>
                                    <option value="holiday">Holiday</option>
                                    <option value="event">School Event</option>
                                    <option value="meeting">Meeting</option>
                                    <option value="sports">Sports</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="startDate" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="startTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="startTime" name="start_time">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="endTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="endTime" name="end_time">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="eventDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="eventLocation" class="form-label">Location</label>
                                <input type="text" class="form-control" id="eventLocation" name="location">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="eventClass" class="form-label">Class/Grade</label>
                                <select class="form-select" id="eventClass" name="class_id">
                                    <option value="">All Classes</option>
                                    <option value="1">Grade 1</option>
                                    <option value="2">Grade 2</option>
                                    <option value="3">Grade 3</option>
                                    <option value="4">Grade 4</option>
                                    <option value="5">Grade 5</option>
                                    <!-- Add more classes as needed -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allDay" name="all_day">
                                    <label class="form-check-label" for="allDay">
                                        All Day Event
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sendNotification" name="send_notification">
                                    <label class="form-check-label" for="sendNotification">
                                        Send Notification
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="eventDetailsContent">
                <!-- Event details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="editEventBtn">Edit</button>
                <button type="button" class="btn btn-danger" id="deleteEventBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-table {
    font-size: 0.9rem;
}

.calendar-table td {
    height: 120px;
    vertical-align: top;
    padding: 5px;
    position: relative;
}

.calendar-table .day-number {
    font-weight: bold;
    margin-bottom: 5px;
}

.calendar-table .other-month {
    color: #ccc;
    background-color: #f8f9fa;
}

.calendar-table .today {
    background-color: #e3f2fd;
}

.event-item {
    background-color: #007bff;
    color: white;
    padding: 2px 5px;
    margin: 1px 0;
    border-radius: 3px;
    font-size: 0.75rem;
    cursor: pointer;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.event-item.academic {
    background-color: #17a2b8;
}

.event-item.exam {
    background-color: #ffc107;
    color: #000;
}

.event-item.holiday {
    background-color: #28a745;
}

.event-item.event {
    background-color: #dc3545;
}

.event-item.meeting {
    background-color: #6f42c1;
}

.event-item.sports {
    background-color: #fd7e14;
}

.upcoming-event {
    border-left: 4px solid #007bff;
    padding: 10px;
    margin-bottom: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
}

.upcoming-event.academic {
    border-left-color: #17a2b8;
}

.upcoming-event.exam {
    border-left-color: #ffc107;
}

.upcoming-event.holiday {
    border-left-color: #28a745;
}

.upcoming-event.event {
    border-left-color: #dc3545;
}

.upcoming-event.meeting {
    border-left-color: #6f42c1;
}

.upcoming-event.sports {
    border-left-color: #fd7e14;
}
</style>

<script>
class SchoolCalendar {
    constructor() {
        this.currentDate = new Date();
        this.currentMonth = this.currentDate.getMonth();
        this.currentYear = this.currentDate.getFullYear();
        this.events = [];
        this.filteredEvents = [];
        this.activeFilter = 'all';
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadEvents();
        this.renderCalendar();
        this.loadUpcomingEvents();
    }

    setupEventListeners() {
        // Navigation buttons
        document.getElementById('prevMonth').addEventListener('click', () => this.previousMonth());
        document.getElementById('nextMonth').addEventListener('click', () => this.nextMonth());
        document.getElementById('today').addEventListener('click', () => this.goToToday());

        // Event form
        document.getElementById('eventForm').addEventListener('submit', (e) => this.saveEvent(e));

        // All day checkbox
        document.getElementById('allDay').addEventListener('change', (e) => this.toggleTimeFields(e));

        // Filter buttons
        document.querySelectorAll('input[name="eventFilter"]').forEach(radio => {
            radio.addEventListener('change', (e) => this.filterEvents(e.target.value));
        });

        // Edit and delete buttons
        document.getElementById('editEventBtn').addEventListener('click', () => this.editEvent());
        document.getElementById('deleteEventBtn').addEventListener('click', () => this.deleteEvent());
    }

    loadEvents() {
        // Sample events data - replace with actual API call
        this.events = [
            {
                id: 1,
                title: "First Day of School",
                type: "academic",
                start_date: "2025-05-26",
                end_date: "2025-05-26",
                start_time: "08:00",
                end_time: "15:00",
                description: "Welcome back students!",
                location: "Main Campus",
                class_id: null,
                all_day: false
            },
            {
                id: 2,
                title: "Mathematics Exam",
                type: "exam",
                start_date: "2025-05-28",
                end_date: "2025-05-28",
                start_time: "09:00",
                end_time: "11:00",
                description: "Grade 5 Mathematics final exam",
                location: "Room 101",
                class_id: 5,
                all_day: false
            },
            {
                id: 3,
                title: "Memorial Day",
                type: "holiday",
                start_date: "2025-05-26",
                end_date: "2025-05-26",
                description: "School Holiday",
                all_day: true
            },
            {
                id: 4,
                title: "Parent-Teacher Conference",
                type: "meeting",
                start_date: "2025-05-30",
                end_date: "2025-05-30",
                start_time: "14:00",
                end_time: "17:00",
                description: "Individual meetings with parents",
                location: "Various Classrooms",
                all_day: false
            }
        ];

        this.applyFilter();
    }

    applyFilter() {
        if (this.activeFilter === 'all') {
            this.filteredEvents = [...this.events];
        } else {
            this.filteredEvents = this.events.filter(event => event.type === this.activeFilter);
        }
    }

    filterEvents(filter) {
        this.activeFilter = filter;
        this.applyFilter();
        this.renderCalendar();
        this.loadUpcomingEvents();
    }

    renderCalendar() {
        const firstDay = new Date(this.currentYear, this.currentMonth, 1);
        const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        // Update month/year display
        document.getElementById('currentMonthYear').textContent = 
            firstDay.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

        const calendarBody = document.getElementById('calendarBody');
        calendarBody.innerHTML = '';

        let currentDate = new Date(startDate);
        
        for (let week = 0; week < 6; week++) {
            const row = document.createElement('tr');
            
            for (let day = 0; day < 7; day++) {
                const cell = document.createElement('td');
                const dayNumber = document.createElement('div');
                dayNumber.className = 'day-number';
                dayNumber.textContent = currentDate.getDate();

                // Add classes for styling
                if (currentDate.getMonth() !== this.currentMonth) {
                    cell.className = 'other-month';
                }
                
                if (this.isToday(currentDate)) {
                    cell.className += ' today';
                }

                // Add events for this day
                const dayEvents = this.getEventsForDate(currentDate);
                const eventsContainer = document.createElement('div');
                
                dayEvents.forEach(event => {
                    const eventElement = document.createElement('div');
                    eventElement.className = `event-item ${event.type}`;
                    eventElement.textContent = event.title;
                    eventElement.addEventListener('click', () => this.showEventDetails(event));
                    eventsContainer.appendChild(eventElement);
                });

                cell.appendChild(dayNumber);
                cell.appendChild(eventsContainer);
                row.appendChild(cell);

                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            calendarBody.appendChild(row);
        }
    }

    getEventsForDate(date) {
        const dateStr = date.toISOString().split('T')[0];
        return this.filteredEvents.filter(event => {
            const startDate = event.start_date;
            const endDate = event.end_date || event.start_date;
            return dateStr >= startDate && dateStr <= endDate;
        });
    }

    isToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }

    previousMonth() {
        this.currentMonth--;
        if (this.currentMonth < 0) {
            this.currentMonth = 11;
            this.currentYear--;
        }
        this.renderCalendar();
    }

    nextMonth() {
        this.currentMonth++;
        if (this.currentMonth > 11) {
            this.currentMonth = 0;
            this.currentYear++;
        }
        this.renderCalendar();
    }

    goToToday() {
        const today = new Date();
        this.currentMonth = today.getMonth();
        this.currentYear = today.getFullYear();
        this.renderCalendar();
    }

    showEventDetails(event) {
        const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
        const content = document.getElementById('eventDetailsContent');
        
        const startDate = new Date(event.start_date).toLocaleDateString();
        const endDate = event.end_date ? new Date(event.end_date).toLocaleDateString() : startDate;
        
        content.innerHTML = `
            <div class="mb-3">
                <h6 class="fw-bold">Title:</h6>
                <p>${event.title}</p>
            </div>
            <div class="mb-3">
                <h6 class="fw-bold">Type:</h6>
                <span class="badge bg-primary">${event.type.charAt(0).toUpperCase() + event.type.slice(1)}</span>
            </div>
            <div class="mb-3">
                <h6 class="fw-bold">Date:</h6>
                <p>${startDate}${endDate !== startDate ? ' - ' + endDate : ''}</p>
            </div>
            ${event.start_time ? `
            <div class="mb-3">
                <h6 class="fw-bold">Time:</h6>
                <p>${event.start_time}${event.end_time ? ' - ' + event.end_time : ''}</p>
            </div>
            ` : ''}
            ${event.description ? `
            <div class="mb-3">
                <h6 class="fw-bold">Description:</h6>
                <p>${event.description}</p>
            </div>
            ` : ''}
            ${event.location ? `
            <div class="mb-3">
                <h6 class="fw-bold">Location:</h6>
                <p>${event.location}</p>
            </div>
            ` : ''}
        `;

        // Store current event for edit/delete actions
        document.getElementById('eventDetailsModal').setAttribute('data-event-id', event.id);
        modal.show();
    }

    editEvent() {
        const eventId = document.getElementById('eventDetailsModal').getAttribute('data-event-id');
        const event = this.events.find(e => e.id == eventId);
        
        if (event) {
            // Populate form with event data
            document.getElementById('eventId').value = event.id;
            document.getElementById('eventTitle').value = event.title;
            document.getElementById('eventType').value = event.type;
            document.getElementById('startDate').value = event.start_date;
            document.getElementById('endDate').value = event.end_date || '';
            document.getElementById('startTime').value = event.start_time || '';
            document.getElementById('endTime').value = event.end_time || '';
            document.getElementById('eventDescription').value = event.description || '';
            document.getElementById('eventLocation').value = event.location || '';
            document.getElementById('eventClass').value = event.class_id || '';
            document.getElementById('allDay').checked = event.all_day;

            // Update modal title
            document.getElementById('eventModalLabel').textContent = 'Edit Event';

            // Hide details modal and show edit modal
            bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
            new bootstrap.Modal(document.getElementById('eventModal')).show();
        }
    }

    deleteEvent() {
        const eventId = document.getElementById('eventDetailsModal').getAttribute('data-event-id');
        
        if (confirm('Are you sure you want to delete this event?')) {
            // Remove from events array
            this.events = this.events.filter(e => e.id != eventId);
            this.applyFilter();
            
            // Hide modal and refresh calendar
            bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
            this.renderCalendar();
            this.loadUpcomingEvents();
            
            // In real application, make API call to delete from database
            console.log('Event deleted:', eventId);
        }
    }

    saveEvent(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const eventData = Object.fromEntries(formData.entries());
        
        // Basic validation
        if (!eventData.title || !eventData.type || !eventData.start_date) {
            alert('Please fill in all required fields.');
            return; 
        }

        if (eventData.event_id) {
            // Update existing event
            const index = this.events.findIndex(e => e.id == eventData.event_id);
            if (index !== -1) {
                this.events[index] = { ...this.events[index], ...eventData, id: parseInt(eventData.event_id) };
            }
        } else {
            // Add new event
            const newEvent = {
                ...eventData,
                id: Date.now(), // Simple ID generation
                all_day: eventData.all_day === 'on'
            };
            this.events.push(newEvent);
        }

        this.applyFilter();
        this.renderCalendar();
        this.loadUpcomingEvents();
        
        // Reset form and hide modal
        e.target.reset();
        bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
        document.getElementById('eventModalLabel').textContent = 'Add Event';
        
        // In real application, make API call to save to database
        console.log('Event saved:', eventData);
    }

    toggleTimeFields(e) {
        const startTime = document.getElementById('startTime');
        const endTime = document.getElementById('endTime');
        
        if (e.target.checked) {
            startTime.disabled = true;
            endTime.disabled = true;
            startTime.value = '';
            endTime.value = '';
        } else {
            startTime.disabled = false;
            endTime.disabled = false;
        }
    }

    loadUpcomingEvents() {
        const today = new Date();
        const nextWeek = new Date();
        nextWeek.setDate(nextWeek.getDate() + 7);
        
        const upcomingEvents = this.filteredEvents.filter(event => {
            const eventDate = new Date(event.start_date);
            return eventDate >= today && eventDate <= nextWeek;
        }).sort((a, b) => new Date(a.start_date) - new Date(b.start_date));

        const container = document.getElementById('upcomingEvents');
        
        if (upcomingEvents.length === 0) {
            container.innerHTML = '<p class="text-muted">No upcoming events in the next 7 days.</p>';
            return;
        }

        container.innerHTML = upcomingEvents.map(event => {
            const eventDate = new Date(event.start_date).toLocaleDateString();
            const timeStr = event.all_day ? 'All Day' : 
                           event.start_time ? `${event.start_time}${event.end_time ? ' - ' + event.end_time : ''}` : '';
            
            return `
                <div class="upcoming-event ${event.type}" onclick="calendar.showEventDetails(${JSON.stringify(event).replace(/"/g, '&quot;')})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${event.title}</h6>
                            <small class="text-muted">${eventDate} ${timeStr}</small>
                            ${event.location ? `<br><small class="text-muted"><i class="mdi mdi-map-marker"></i> ${event.location}</small>` : ''}
                        </div>
                        <span class="badge bg-secondary">${event.type}</span>
                    </div>
                </div>
            `;
        }).join('');
    }
}

// Initialize calendar when page loads
document.addEventListener('DOMContentLoaded', function() {
    window.calendar = new SchoolCalendar();
});
</script>

<?= $this->endSection() ?>