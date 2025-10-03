<?= $this->extend('backend/student/layout/pages-layout') ?>

<?= $this->section('stylesheets') ?>
<link rel="stylesheet" type="text/css" href="/backend/src/plugins/fullcalendar/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/apexcharts/apexcharts.min.js"></script>
<script src="/backend/src/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script>
// Custom Calendar functionality - moved to scripts section
class Calendar {
    constructor() {
        console.log('=== Calendar Constructor Debug ===');
        this.currentDate = new Date();
        this.events = {
            // Sample events - replace with actual data
            '2025-01-05': 2, // 2 events on Jan 5th
            '2025-01-12': 1, // 1 event on Jan 12th
            '2025-01-18': 3, // 3 events on Jan 18th
            '2025-01-25': 1, // 1 event on Jan 25th
            '2025-01-30': 2, // 2 events on Jan 30th
            '2025-05-05': 1, // 1 event on May 5th
            '2025-05-12': 2, // 2 events on May 12th
            '2025-05-18': 1, // 1 event on May 18th
            '2025-05-25': 3  // 3 events on May 25th
        };
        // Set to current month (May 2025) to match the page header
        this.currentMonth = 4; // May is month 4 (0-indexed)
        this.currentYear = 2025;
        console.log('Calendar initialized with month:', this.currentMonth, 'year:', this.currentYear);
        this.init();
    }

    init() {
        this.generateCalendar();
        this.bindEvents();
    }

    bindEvents() {
        const prevBtn = document.getElementById('prevMonth');
        const nextBtn = document.getElementById('nextMonth');
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                this.currentMonth--;
                if (this.currentMonth < 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                }
                this.generateCalendar();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                this.currentMonth++;
                if (this.currentMonth > 11) {
                    this.currentMonth = 0;
                    this.currentYear++;
                }
                this.generateCalendar();
            });
        }
    }

    generateCalendar() {
        console.log('=== generateCalendar Debug ===');
        console.log('Generating calendar for month:', this.currentMonth, 'year:', this.currentYear);
        
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Update month header
        const monthHeader = document.getElementById('currentMonth');
        if (monthHeader) {
            monthHeader.textContent = `${monthNames[this.currentMonth]} ${this.currentYear}`;
            console.log('Month header updated to:', monthHeader.textContent);
        } else {
            console.error('Month header element not found!');
        }

        // Get first day of month and number of days
        const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
        const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        const daysInPrevMonth = new Date(this.currentYear, this.currentMonth, 0).getDate();

        let html = '';
        let date = 1;
        let nextMonthDate = 1;

        // Generate 6 weeks (42 days)
        for (let week = 0; week < 6; week++) {
            html += '<tr>';
            
            for (let day = 0; day < 7; day++) {
                const cellIndex = week * 7 + day;
                
                if (cellIndex < firstDay) {
                    // Previous month dates
                    const prevDate = daysInPrevMonth - firstDay + cellIndex + 1;
                    html += `<td class="text-muted py-2">
                        <div class="d-flex flex-column align-items-center">
                            <span style="font-size: 13px;">${prevDate}</span>
                        </div>
                    </td>`;
                } else if (date <= daysInMonth) {
                    // Current month dates
                    const dateStr = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                    const eventCount = this.events[dateStr] || 0;
                    const isToday = this.isToday(date);
                    
                    html += `<td class="py-2">
                        <div class="d-flex flex-column align-items-center position-relative">
                            <span class="${isToday ? 'bg-primary text-white rounded-circle px-2 py-1 fw-bold' : ''}" style="font-size: 14px; min-width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">${date}</span>
                            ${this.generateEventDots(eventCount)}
                        </div>
                    </td>`;
                    date++;
                } else {
                    // Next month dates
                    html += `<td class="text-muted py-2">
                        <div class="d-flex flex-column align-items-center">
                            <span style="font-size: 13px;">${nextMonthDate}</span>
                        </div>
                    </td>`;
                    nextMonthDate++;
                }
            }
            
            html += '</tr>';
            
            // Break if we've filled all days of current month and some next month days
            if (date > daysInMonth && nextMonthDate > 7) break;
        }

        const calendarBody = document.getElementById('calendarBody');
        console.log('Generated HTML length:', html.length);
        console.log('Generated HTML preview:', html.substring(0, 200) + '...');
        
        if (calendarBody) {
            calendarBody.innerHTML = html;
            console.log('Calendar HTML successfully inserted into DOM');
            console.log('Calendar body now contains:', calendarBody.children.length, 'rows');
            
            // Add visible debug confirmation
            if (calendarBody.children.length === 0) {
                calendarBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px; color: orange; font-weight: bold;">DEBUG: Calendar HTML generated but no rows created</td></tr>';
            }
        } else {
            console.error('Calendar body element not found! Cannot insert HTML.');
            // Add visible error message
            const debugElement = document.createElement('div');
            debugElement.innerHTML = 'ERROR: Calendar body element not found!';
            debugElement.style.cssText = 'color: red; font-weight: bold; text-align: center; padding: 20px;';
            document.body.appendChild(debugElement);
        }
    }

    generateEventDots(count) {
        if (count === 0) return '';
        
        let dots = '<div class="d-flex justify-content-center mt-1">';
        
        // Limit to maximum 3 dots for visual clarity
        const displayCount = Math.min(count, 3);
        
        for (let i = 0; i < displayCount; i++) {
            const colors = ['bg-primary', 'bg-success', 'bg-warning'];
            const color = colors[i % colors.length];
            dots += `<span class="${color} rounded-circle me-1" style="width: 6px; height: 6px; display: inline-block;"></span>`;
        }
        
        // If more than 3 events, show a "+" indicator
        if (count > 3) {
            dots += '<span class="text-muted small ms-1">+</span>';
        }
        
        dots += '</div>';
        return dots;
    }

    isToday(date) {
        const today = new Date();
        return date === today.getDate() && 
               this.currentMonth === today.getMonth() && 
               this.currentYear === today.getFullYear();
    }
}
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header bg-white rounded-3 p-4 mb-4">
	<div class="row align-items-center">
		<div class="col-md-6 col-sm-12">
			<div class="d-flex align-items-center">
				<div class="me-3">
					<h4 class="text-primary fw-bold mb-1">Dashboard</h4>
					<nav aria-label="breadcrumb" role="navigation">
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="<?= site_url('student/dashboard') ?>" class="text-decoration-none">Home</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page">
								Student Dashboard
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
			<button class="btn btn-primary rounded-pill px-4 ms-auto">
				May 2025
			</button>
		</div>
	</div>
</div>

<div class="welcome-section bg-white rounded-3 p-4 mb-4">
	<div class="row align-items-center">
		<div class="col-md-8">
			<h2 class="text-dark fw-bold mb-2">Welcome back! <?= esc(session()->get('name') ?? (session()->get('userdata')['name'] ?? 'Student')) ?></h2>
			<p class="text-muted mb-0">Welcome back! We're excited to see you continue your academic journey. Stay on top of your assignments, track your progress, and reach out if you need any assistance.</p>
		</div>
		<div class="col-md-4 text-end d-flex justify-content-end">
			<?php 
			    $profilePicture = session()->get('profile_picture') ?? (session()->get('userdata')['picture'] ?? null);
			    $profileUrl = $profilePicture ? base_url($profilePicture) : base_url('uploads/students/1754021914_add5539f223a86a90b20.png');
			?>
			<img src="<?= $profileUrl ?>" alt="Student Profile" class="rounded-circle border border-3 border-light" width="100" height="100" style="object-fit: cover;">
		</div>
	</div>
</div>

<div class="row mb-4 g-4">
	<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
		<div class="card rounded-4 border-0 h-100 bg-white shadow-sm position-relative overflow-hidden dashboard-card" data-toggle="tooltip" data-placement="top" title="View detailed grade statistics">
			<div class="card-body p-4 d-flex flex-column align-items-start text-start">
				<!-- Icon Container -->
				<div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 56px; height: 56px; background-color: #e8f5e8;">
					<i class="dw dw-analytics-4 text-success fs-4"></i>
				</div>
				<!-- Category Label -->
				<h6 class="text-muted mb-1 fw-normal">GWA/Average</h6>
				<!-- Main Value -->
				<h5 class="mb-1 fw-bold text-dark count-up" data-count="96">96</h5>
				<small class="text-muted">This 2nd Quarter</small>
				<div class="mt-auto">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<span class="text-muted small">Performance</span>
						<span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">Excellent</span>
					</div>
					<div class="progress rounded-pill" style="height: 6px;">
						<div class="progress-bar bg-success bg-gradient rounded-pill" role="progressbar" style="width: 96%;" aria-valuenow="96" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
		<div class="card rounded-4 border-0 h-100 bg-white shadow-sm position-relative overflow-hidden dashboard-card" data-toggle="tooltip" data-placement="top" title="View attendance history">
			<div class="card-body p-4 d-flex flex-column align-items-start text-start">
				<!-- Icon Container -->
				<div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 56px; height: 56px; background-color: #e3f2fd;">
					<i class="dw dw-wall-clock text-primary fs-4"></i>
				</div>
				<!-- Category Label -->
				<h6 class="text-muted mb-1 fw-normal">Last Attendance</h6>
				<!-- Main Value -->
				<h5 class="mb-1 fw-bold text-dark">Today, 8:30 AM</h5>
				<div class="mt-auto">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<span class="text-muted small">Monthly Rate</span>
						<span class="fw-semibold text-success fs-6">95%</span>
					</div>
					<div class="d-flex align-items-center flex-wrap gap-2">
						<span class="badge bg-success px-2 py-1 rounded-pill small">
							<i class="fa fa-check me-1 small"></i>Present
						</span>
						<small class="text-muted small">On time</small>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
		<div class="card rounded-4 border-0 h-100 bg-white shadow-sm position-relative overflow-hidden dashboard-card" data-toggle="tooltip" data-placement="top" title="View exam schedule">
			<div class="card-body p-4 d-flex flex-column align-items-start text-start">
				<!-- Icon Container -->
				<div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 56px; height: 56px; background-color: #fff3e0;">
					<i class="dw dw-calendar-1 text-warning fs-4"></i>
				</div>
				<!-- Category Label -->
				<h6 class="text-muted mb-1 fw-normal">Next Exam</h6>
				<!-- Main Value -->
				<h5 class="mb-1 fw-bold text-dark">Mathematics</h5>
				<small class="text-muted">May 22, 2025</small>
				<div class="mt-auto">
					<div class="d-flex align-items-center gap-2 mt-3">
						<span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1 small">3 Days Left</span>
						<span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 small">Room 101</span>
					</div>
					<div class="d-flex align-items-center mt-2">
						<div class="avatar-group">
							<div class="avatar avatar-xs" data-bs-toggle="tooltip" data-bs-placement="top" title="Mr. Smith">
								<img src="<?= base_url('assets/images/avatar-1.jpg') ?>" alt="Teacher" class="rounded-circle">
							</div>
						</div>
						<span class="ms-2 text-muted small">Mr. Smith</span>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
		<div class="card rounded-4 border-0 h-100 bg-white shadow-sm position-relative overflow-hidden dashboard-card" data-toggle="modal" data-target="#announcementModal" role="button">
			<div class="card-body p-4 d-flex flex-column align-items-start text-start">
				<!-- Icon Container -->
				<div class="d-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 56px; height: 56px; background-color: #ffebee;">
					<i class="dw dw-megaphone text-danger fs-4"></i>
				</div>
				<!-- Category Label -->
				<h6 class="text-muted mb-1 fw-normal">Latest Announcement</h6>
				<!-- Main Value -->
				<h5 class="mb-1 fw-bold text-dark">End of The Year Concert</h5>
				<small class="text-muted">Posted 2 days ago</small>
				<div class="mt-auto">
					<p class="text-muted mb-3 small lh-sm">Please join us for the end of the year concert on June 2nd at 6:00 pm in the school auditorium.</p>
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-muted small">May 16, 2025</span>
						<div class="d-flex align-items-center gap-2">
							<span class="badge px-2 py-1 text-primary small">
								Read More <i class="fa fa-arrow-right text-primary small"></i>
							</span>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Announcement Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content rounded-4 border-0 shadow">
			<div class="modal-header border-0 pb-0">
				<div class="d-flex align-items-center">
					<div>
						<h5 class="modal-title fw-bold" id="announcementModalLabel">End of The Year Concert</h5>
						<small class="text-muted">Latest Announcement • May 16, 2025</small>
					</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body pt-2">
				<div class="alert alert-primary border-0 rounded-3 mb-4" role="alert">
					<div class="d-flex align-items-center">
						<i class="fa fa-info-circle me-2"></i>
						<span class="fw-semibold">Important Event Notification</span>
					</div>
				</div>
				<p class="mb-4">Please join us for the end of the year concert on June 2nd at 6:00 pm in the school auditorium. This special event will showcase the incredible talents of our students across all grade levels.</p>
				<div class="row g-3 mb-4">
					<div class="col-md-6">
						<div class="d-flex align-items-center">
							<i class="fa fa-calendar text-primary me-2"></i>
							<div>
								<small class="text-muted d-block">Date</small>
								<span class="fw-semibold">June 2nd, 2025</span>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="d-flex align-items-center">
							<i class="fa fa-clock text-primary me-2"></i>
							<div>
								<small class="text-muted d-block">Time</small>
								<span class="fw-semibold">6:00 PM</span>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="d-flex align-items-center">
							<i class="fa fa-map-marker-alt text-primary me-2"></i>
							<div>
								<small class="text-muted d-block">Location</small>
								<span class="fw-semibold">School Auditorium</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer border-0 pt-0">
				<button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary rounded-pill px-4">
					<i class="fa fa-bookmark me-2"></i>Save Event
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Attendance Overview Card -->
<div class="row mb-4">
	<div class="col-12">
		<div class="card rounded-4 border-0 bg-white shadow-sm">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-4">
					<h4 class="h5 mb-0 fw-bold text-dark">Attendance Overview</h4>
					<button type="button" class="btn btn-primary btn-sm rounded-pill px-3" style="font-size: 0.8rem;">View All</button>
				</div>
				<div class="row">
					<div class="col-md-5">
						<div id="attendance-chart"></div>
						<div class="text-center mt-3">
							<div class="d-inline-block me-3">
								<div class="square-indicator bg-success"></div>
								<span>Present</span>
							</div>
							<div class="d-inline-block">
								<div class="square-indicator bg-danger"></div>
								<span>Absent</span>
							</div>
						</div>
					</div>
					<div class="col-md-7">
						<div id="monthly-attendance-chart"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Calendar Card -->
<div class="row mb-4">
	<div class="col-lg-6 col-md-12 col-sm-12 mb-3">
		<div class="card rounded-4 border-0 h-100 bg-white shadow-sm">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-4">
					<h4 class="h5 mb-0 fw-bold text-dark" id="currentMonth">May 2025</h4>
					<div class="d-flex align-items-center">
						<button type="button" class="btn btn-primary btn-sm rounded-pill px-3" style="font-size: 0.8rem;">View All</button>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-borderless text-center" id="calendarTable">
						<thead>
							<tr class="border-bottom">
								<th class="text-muted fw-semibold py-2">Sun</th>
								<th class="text-muted fw-semibold py-2">Mon</th>
								<th class="text-muted fw-semibold py-2">Tue</th>
								<th class="text-muted fw-semibold py-2">Wed</th>
								<th class="text-muted fw-semibold py-2">Thu</th>
								<th class="text-muted fw-semibold py-2">Fri</th>
								<th class="text-muted fw-semibold py-2">Sat</th>
							</tr>
						</thead>
						<tbody id="calendarBody">
							<!-- Calendar dates will be generated by JavaScript -->
							<tr><td colspan="7" style="text-align: center; padding: 20px; color: red; font-weight: bold;">DEBUG: Calendar not initialized yet</td></tr>
						</tbody>
					</table>
				</div>
				<div class="d-flex justify-content-end mt-3">
					<div class="btn-group" role="group">
						<button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" id="prevMonth" style="width: 32px; height: 32px; border-top-right-radius: 0; border-bottom-right-radius: 0;"> 
 							<i class="fa fa-chevron-left" style="font-size: 0.75rem;"></i> 
 						</button>
						<button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" id="nextMonth" style="width: 32px; height: 32px; border-top-left-radius: 0; border-bottom-left-radius: 0;"> 
 							<i class="fa fa-chevron-right" style="font-size: 0.75rem;"></i> 
 						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Calendar Initialization - Moved to Content Section for Proper Timing -->
	<script>
	console.log('=== INLINE CALENDAR INITIALIZATION ===');
	
	// Calendar class definition (copied from scripts section)
	class InlineCalendar {
		constructor() {
			console.log('InlineCalendar constructor called');
			this.currentDate = new Date();
			this.events = {
				'2025-05-05': 1, '2025-05-12': 2, '2025-05-18': 1, '2025-05-25': 3
			};
			this.currentMonth = 4; // May
			this.currentYear = 2025;
			console.log('Calendar initialized with month:', this.currentMonth, 'year:', this.currentYear);
			this.init();
		}
		
		init() {
			this.generateCalendar();
			this.bindEvents();
		}
		
		bindEvents() {
			const prevBtn = document.getElementById('prevMonth');
			const nextBtn = document.getElementById('nextMonth');
			
			if (prevBtn) {
				prevBtn.addEventListener('click', () => {
					this.currentMonth--;
					if (this.currentMonth < 0) {
						this.currentMonth = 11;
						this.currentYear--;
					}
					this.generateCalendar();
				});
			}
			
			if (nextBtn) {
				nextBtn.addEventListener('click', () => {
					this.currentMonth++;
					if (this.currentMonth > 11) {
						this.currentMonth = 0;
						this.currentYear++;
					}
					this.generateCalendar();
				});
			}
		}
		
		generateCalendar() {
			console.log('generateCalendar called for month:', this.currentMonth, 'year:', this.currentYear);
			
			const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
			
			// Update month header
			const monthHeader = document.getElementById('currentMonth');
			if (monthHeader) {
				monthHeader.textContent = `${monthNames[this.currentMonth]} ${this.currentYear}`;
				console.log('Month header updated to:', monthHeader.textContent);
			}
			
			// Get first day of month and number of days
			const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
			const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
			const daysInPrevMonth = new Date(this.currentYear, this.currentMonth, 0).getDate();
			
			let html = '';
			let date = 1;
			let nextMonthDate = 1;
			
			// Generate 6 weeks (42 days)
			for (let week = 0; week < 6; week++) {
				html += '<tr>';
				
				for (let day = 0; day < 7; day++) {
					const cellIndex = week * 7 + day;
					
					if (cellIndex < firstDay) {
						// Previous month dates
						const prevDate = daysInPrevMonth - firstDay + cellIndex + 1;
						html += `<td class="text-muted py-2"><div class="d-flex flex-column align-items-center"><span style="font-size: 13px;">${prevDate}</span></div></td>`;
					} else if (date <= daysInMonth) {
						// Current month dates
						const dateStr = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
						const eventCount = this.events[dateStr] || 0;
						const isToday = this.isToday(date);
						
						html += `<td class="py-2"><div class="d-flex flex-column align-items-center position-relative"><span class="${isToday ? 'bg-primary text-white rounded-circle px-2 py-1 fw-bold' : ''}" style="font-size: 14px; min-width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">${date}</span>${this.generateEventDots(eventCount)}</div></td>`;
						date++;
					} else {
						// Next month dates
						html += `<td class="text-muted py-2"><div class="d-flex flex-column align-items-center"><span style="font-size: 13px;">${nextMonthDate}</span></div></td>`;
						nextMonthDate++;
					}
				}
				
				html += '</tr>';
				
				// Break if we've filled all days of current month and some next month days
				if (date > daysInMonth && nextMonthDate > 7) break;
			}
			
			const calendarBody = document.getElementById('calendarBody');
			console.log('Generated HTML length:', html.length);
			
			if (calendarBody) {
				calendarBody.innerHTML = html;
				console.log('Calendar HTML successfully inserted into DOM');
				console.log('Calendar body now contains:', calendarBody.children.length, 'rows');
			} else {
				console.error('Calendar body element not found!');
			}
		}
		
		generateEventDots(count) {
			if (count === 0) return '';
			
			let dots = '<div class="d-flex justify-content-center mt-1">';
			const displayCount = Math.min(count, 3);
			
			for (let i = 0; i < displayCount; i++) {
				const colors = ['bg-primary', 'bg-success', 'bg-warning'];
				const color = colors[i % colors.length];
				dots += `<span class="${color} rounded-circle me-1" style="width: 6px; height: 6px; display: inline-block;"></span>`;
			}
			
			if (count > 3) {
				dots += '<span class="text-muted small ms-1">+</span>';
			}
			
			dots += '</div>';
			return dots;
		}
		
		isToday(date) {
			const today = new Date();
			return date === today.getDate() && this.currentMonth === today.getMonth() && this.currentYear === today.getFullYear();
		}
	}
	
	// Initialize calendar immediately since DOM elements are available
	if (document.getElementById('calendarBody')) {
		console.log('Calendar elements found, initializing inline calendar...');
		const calendar = new InlineCalendar();
		console.log('Inline calendar created successfully');
	} else {
		console.error('Calendar elements not found in inline script');
	}
	</script>

	<!-- Daily Schedule Card -->
	<div class="col-lg-6 col-md-12 col-sm-12 mb-3">
		<div class="card rounded-4 border-0 h-100 bg-white shadow-sm">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-4">
					<h4 class="h5 mb-0 fw-bold text-dark">Daily Schedule</h4>
					<button type="button" class="btn btn-primary btn-sm rounded-pill px-3" style="font-size: 0.8rem;">View All</button>
				</div>
				<div class="schedule-list">
					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<div class="flex-grow-1">
							<div class="fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.3;">8:00 AM - 9:30 AM</div>
							<small class="text-muted" style="font-size: 0.8rem;">Grade 7 - Sampaguita</small>
						</div>
						<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2" style="font-size: 0.75rem;">Mathematics</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<div class="flex-grow-1">
							<div class="fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.3;">9:45 AM - 11:15 AM</div>
							<small class="text-muted" style="font-size: 0.8rem;">Grade 11 - Jupiter</small>
						</div>
						<span class="badge bg-success-subtle text-success rounded-pill px-3 py-2" style="font-size: 0.75rem;">Physics</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<div class="flex-grow-1">
							<div class="fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.3;">11:30 AM - 1:00 PM</div>
							<small class="text-muted" style="font-size: 0.8rem;">Grade 7 - Rose</small>
						</div>
						<span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2" style="font-size: 0.75rem;">Chemistry</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<div class="flex-grow-1">
							<div class="fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.3;">1:00 PM - 2:00 PM</div>
							<small class="text-muted" style="font-size: 0.8rem;">Lunch Break</small>
						</div>
						<span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2" style="font-size: 0.75rem;">Break</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-3">
						<div class="flex-grow-1">
							<div class="fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.3;">2:00 PM - 3:30 PM</div>
							<small class="text-muted" style="font-size: 0.8rem;">Free Period</small>
						</div>
						<span class="badge bg-light text-muted rounded-pill px-3 py-2" style="font-size: 0.75rem;">Vacant</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Notice Board Card -->
<div class="row mb-4">
	<div class="col-lg-6 col-md-12 col-sm-12 mb-3">
		<div class="card rounded-4 border-0 h-100 bg-white shadow-sm">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-4">
					<h4 class="h5 mb-0 fw-bold text-dark">Notice Board</h4>
					<button type="button" class="btn btn-primary btn-sm rounded-pill px-3" style="font-size: 0.8rem;">View All</button>
				</div>
				<div class="notice-list">
					<div class="py-3 border-bottom">
 						<div class="d-flex align-items-start">
							<div class="me-5 d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 bg-light" style="width: 40px; height: 40px; position: relative; margin-right: 25px !important;">
								<i class="fa fa-bullhorn text-primary" style="font-size: 14px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
							</div>
							<div class="flex-grow-1 min-w-0">
								<h6 class="mb-2 fw-semibold text-primary" style="font-size: 0.9rem; line-height: 1.3;">Urgent Meeting</h6>
								<p class="mb-2 text-dark" style="font-size: 0.85rem; line-height: 1.4;">Department meeting scheduled at 3 PM in the conference room.</p>
								<small class="text-muted" style="font-size: 0.75rem;">3 days ago</small>
							</div>
						</div>
					</div>
					<div class="py-3 border-bottom">
						<div class="d-flex align-items-start">
							<div class="me-5 d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 bg-light" style="width: 40px; height: 40px; position: relative; margin-right: 25px !important;">
								<i class="fa fa-calendar text-primary" style="font-size: 14px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
							</div>
							<div class="flex-grow-1 min-w-0">
								<h6 class="mb-2 fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.3;">Upcoming Event</h6>
								<p class="mb-2 text-dark" style="font-size: 0.85rem; line-height: 1.4;">Annual sports festival starts next week. Don't miss it!</p>
								<small class="text-muted" style="font-size: 0.75rem;">5 days ago</small>
							</div>
						</div>
					</div>
					<div class="py-3">
						<div class="d-flex align-items-start">
							<div class="me-5 d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 bg-light" style="width: 40px; height: 40px; position: relative; margin-right: 25px !important;">
								<i class="fa fa-exclamation-triangle text-warning" style="font-size: 14px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
							</div>
							<div class="flex-grow-1 min-w-0">
								<h6 class="mb-2 fw-semibold text-dark" style="font-size: 0.9rem; line-height: 1.3;">System Maintenance</h6>
								<p class="mb-2 text-dark" style="font-size: 0.85rem; line-height: 1.4;">The portal will be down for maintenance this Saturday from 1 AM to 3 AM.</p>
								<small class="text-muted" style="font-size: 0.75rem;">1 week ago</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Grade by Subject Card -->
	<div class="col-lg-6 col-md-12 col-sm-12 mb-3">
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h4 class="h4 mb-0">Grade by Subject</h4>
					<button type="button" class="btn btn-primary btn-sm">View All</button>
				</div>
				<div>
					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">Mathematics</span>
							<span class="fw-semibold">95%</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-primary" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">Science</span>
							<span class="fw-semibold">88%</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-success" role="progressbar" style="width: 88%" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">English</span>
							<span class="fw-semibold">92%</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-info" role="progressbar" style="width: 92%" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">History</span>
							<span class="fw-semibold">78%</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-warning" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="mb-0">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">Physical Education</span>
							<span class="fw-semibold">90%</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-danger" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Assignment Section -->
<div class="row mb-4">
	<div class="col-12">
		<div class="card rounded-4 border-0 bg-white shadow-sm">
			<div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
				<h5 class="text-dark fw-bold mb-0">Assignment</h5>
			</div>
			<div class="card-body p-4">
				<div class="pb-3">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group mb-0">
								<input type="text" class="form-control search-input" placeholder="Search...">
							</div>
						</div>
						<div class="col-md-6 text-right">
							<div class="dropdown">
								<a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
									Filter
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#">All</a>
									<a class="dropdown-item" href="#">Completed</a>
									<a class="dropdown-item" href="#">Pending</a>
									<a class="dropdown-item" href="#">Overdue</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<table class="data-table table stripe hover nowrap">
					<thead>
						<tr role="row">
							<th class="table-plus">Task</th>
							<th>Priority</th>
							<th>Section</th>
							<th>Due Date</th>
							<th class="datatable-nosort">Status</th>
							<th class="datatable-nosort">Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr role="row" class="odd">
							<td class="table-plus sorting_1" tabindex="0">Create Login Page</td>
							<td>High</td>
							<td>Frontend</td>
							<td>2025-08-15</td>
							<td><span class="badge badge-success">Completed</span></td>
							<td>
								<div class="dropdown">
									<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
										<i class="dw dw-more"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
										<a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
										<a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
										<a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
									</div>
								</div>
							</td>
						</tr>
						<tr role="row" class="even">
							<td class="table-plus sorting_1" tabindex="0">Database Schema Design</td>
							<td>Medium</td>
							<td>Backend</td>
							<td>2025-08-20</td>
							<td><span class="badge badge-warning">Pending</span></td>
							<td>
								<div class="dropdown">
									<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
										<i class="dw dw-more"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
										<a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
										<a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
										<a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
									</div>
								</div>
							</td>
						</tr>
						<tr role="row" class="odd">
							<td class="table-plus sorting_1" tabindex="0">Integrate Payment Gateway</td>
							<td>High</td>
							<td>Backend</td>
							<td>2025-08-25</td>
							<td><span class="badge badge-danger">Overdue</span></td>
							<td>
								<div class="dropdown">
									<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
										<i class="dw dw-more"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
										<a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
										<a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
										<a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
.square-indicator {
	display: inline-block;
	width: 12px;
	height: 12px;
	margin-right: 5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Donut chart for attendance percentage
	var options = {
		chart: {
			height: 280,
			type: 'donut',
			toolbar: {
				show: false
			}
		},
		series: [80, 20],
		labels: ['Present', 'Absent'],
		colors: ['#a55eea', '#feca57'],
		plotOptions: {
			pie: {
				donut: {
					size: '75%',
					background: 'transparent',
					labels: {
						show: true,
						name: {
							show: false
						},
						value: {
							show: true,
							fontSize: '28px',
							fontWeight: 700,
							color: '#333',
							offsetY: 5,
							formatter: function (val) {
								return '80%';
							}
						},
						total: {
							show: false
						}
					}
				}
			}
		},
		dataLabels: {
			enabled: false
		},
		legend: {
			show: false
		},
		stroke: {
			width: 0
		}
	};

	var chart = new ApexCharts(
		document.querySelector("#attendance-chart"),
		options
	);

	chart.render();

	// Monthly attendance bar chart with stacked data
	var options2 = {
		chart: {
			height: 280,
			type: 'bar',
			stacked: true,
			toolbar: {
				show: false
			},
			fontFamily: 'Inter, sans-serif',
			background: '#fff'
		},
		plotOptions: {
			bar: {
				horizontal: false,
				columnWidth: '55%',
				borderRadius: 4,
			}
		},
		dataLabels: {
			enabled: false
		},
		series: [
			{
				name: 'Present',
				data: [75, 82, 88, 70, 85, 90, 92, 95, 78, 80]
			},
			{
				name: 'Late',
				data: [10, 8, 5, 12, 5, 3, 2, 1, 8, 5]
			},
			{
				name: 'Absent',
				data: [15, 10, 7, 18, 10, 7, 6, 4, 14, 15]
			}
		],
		xaxis: {
			categories: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
			labels: {
				style: {
					fontSize: '12px',
					fontWeight: 400,
					colors: ['#777']
				}
			}
		},
		yaxis: {
			labels: {
				formatter: function(val) {
					return val + '%';
				}
			}
		},
		grid: {
			borderColor: '#f1f1f1',
			show: true,
			position: 'back'
		},
		colors: ['#6c5ce7', '#00b894', '#ff6b6b'],
		legend: {
			show: false
		},
		tooltip: {
			y: {
				formatter: function(val) {
					return val + '%';
				}
			}
		}
	};

	var chart2 = new ApexCharts(
		document.querySelector("#monthly-attendance-chart"),
		options2
	);

	chart2.render();

	// Initialize calendar with improved styling - TEMPORARILY DISABLED
    /*
    $('#calendar').fullCalendar({
		header: {
			left: '',
			center: 'title',
			right: 'prev,next'
		},
		defaultDate: '2025-05-19',
		navLinks: false,
		editable: false,
		eventLimit: true,
		height: 'auto',
		themeSystem: 'bootstrap4',
		bootstrapFontAwesome: {
			prev: 'fa fa-chevron-left',
			next: 'fa fa-chevron-right'
		},
		events: [
			{
				title: 'Class',
				start: '2025-05-19',
				color: '#6c5ce7'
			},
			{
				title: 'Exam',
				start: '2025-05-22',
				color: '#ff6b6b'
			},
			{
				title: 'Parent Meeting',
				start: '2025-05-10',
				color: '#00b894'
			},
			{
				title: 'School Event',
				start: '2025-05-15',
				color: '#feca57'
			}
		],
		eventRender: function(event, element) {
			element.css({
				'border-radius': '4px',
				'font-size': '0.8rem',
				'font-weight': '500'
			});
		},
		dayRender: function(date, cell) {
			// Highlight the current day (May 19) with a red circle
			if (date.format('YYYY-MM-DD') === '2025-05-19') {
				cell.css({
					'background-color': '#ff6b6b',
					'border-radius': '50%',
					'color': 'white'
				});
			}
		}
	});
    */

	// Add custom styling to calendar - DISABLED
	/*
	$('.fc-toolbar h2').css({
		'font-size': '1.2rem',
		'font-weight': '700',
		'color': '#6c5ce7'
	});

	$('.fc-day-header').css({
		'font-size': '0.8rem',
		'font-weight': '600',
		'padding': '10px 0',
		'text-transform': 'uppercase'
	});
	*/
	
	// Add hover effect to notice board items using JavaScript
	const noticeItems = document.querySelectorAll('.notice-list > div');
	
	noticeItems.forEach(function(item) {
		item.addEventListener('mouseenter', function() {
			this.classList.add('shadow-sm', 'bg-light', 'rounded');
		});
		
		item.addEventListener('mouseleave', function() {
			this.classList.remove('shadow-sm', 'bg-light', 'rounded');
		});
		
		// Add transition effect using JavaScript
		item.style.transition = 'all 0.3s ease';
	});
	
	// Ensure all student cards have the same height
	function equalizeCardHeights() {
		var maxHeight = 0;
		$('.students-section .card').each(function() {
			var cardHeight = $(this).height();
			maxHeight = Math.max(maxHeight, cardHeight);
		});
		$('.students-section .card').height(maxHeight);
	}
	
	// Call on page load and resize
	equalizeCardHeights();
	$(window).resize(function() {
		$('.students-section .card').height('auto'); // Reset height
		equalizeCardHeights();
	});
	
	// Add transition effect for smoother hover
	$('.notice-item, .students-section .card').css('transition', 'all 0.3s ease');
});
</script>

<script>
// Initialize tooltips (Bootstrap 4 syntax)
document.addEventListener('DOMContentLoaded', function() {
	if (typeof $ !== 'undefined') {
		$('[data-toggle="tooltip"]').tooltip();
	}
});

// Count-up animation for numeric values
function animateCountUp(element, target) {
	let current = 0;
	const increment = target / 50;
	const timer = setInterval(() => {
		current += increment;
		if (current >= target) {
			current = target;
			clearInterval(timer);
		}
		element.textContent = Math.floor(current);
	}, 30);
}

// Initialize calendar when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Function to check if calendar elements exist
        function initializeCalendarWhenReady() {
            console.log('=== Calendar Initialization Debug ===');
            const calendarBody = document.getElementById('calendarBody');
            const monthHeader = document.getElementById('currentMonth');
            const prevBtn = document.getElementById('prevMonth');
            const nextBtn = document.getElementById('nextMonth');
            
            console.log('Calendar body element:', calendarBody);
            console.log('Month header element:', monthHeader);
            console.log('Previous button:', prevBtn);
            console.log('Next button:', nextBtn);
            
            if (calendarBody && monthHeader && prevBtn && nextBtn) {
                console.log('All required elements found, initializing calendar...');
                const calendar = new Calendar();
                console.log('Calendar instance created:', calendar);
                return true;
            } else {
                console.error('Calendar elements not found! Retrying...');
                return false;
            }
        }
        
        // Try multiple times with increasing delays
        let attempts = 0;
        const maxAttempts = 10;
        
        function tryInitialize() {
            attempts++;
            console.log(`Calendar initialization attempt ${attempts}/${maxAttempts}`);
            
            if (initializeCalendarWhenReady()) {
                console.log('Calendar successfully initialized!');
                return;
            }
            
            if (attempts < maxAttempts) {
                setTimeout(tryInitialize, 200 * attempts); // Increasing delay
            } else {
                console.error('Failed to initialize calendar after', maxAttempts, 'attempts');
                // Show error message on page
                const errorDiv = document.createElement('div');
                errorDiv.innerHTML = 'ERROR: Calendar failed to initialize - DOM elements not found';
                errorDiv.style.cssText = 'position: fixed; top: 10px; right: 10px; background: red; color: white; padding: 10px; z-index: 9999;';
                document.body.appendChild(errorDiv);
            }
        }
        
        // Start trying to initialize
        tryInitialize();
    
        // Initialize count-up animations
        const countUpElements = document.querySelectorAll('.count-up');
        countUpElements.forEach(element => {
            const target = parseInt(element.getAttribute('data-count'));
            if (target) {
                animateCountUp(element, target);
            }
        });

        // Add hover elevation effect to dashboard cards
        const dashboardCards = document.querySelectorAll('.dashboard-card');
        dashboardCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg');
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'all 0.3s ease';
            });
            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg');
                this.style.transform = 'translateY(0)';
            });
        });
    
        // Add hover effect to notice board items using JavaScript
        const noticeItems = document.querySelectorAll('.notice-list > div');
    
    noticeItems.forEach(function(item) {
        item.addEventListener('mouseenter', function() {
            this.classList.add('shadow-sm', 'bg-light', 'rounded');
        });
        
        item.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-sm', 'bg-light', 'rounded');
        });
        
        // Add transition effect using JavaScript
        item.style.transition = 'all 0.3s ease';
    });
    
    // Ensure proper icon centering
    const icons = document.querySelectorAll('.notice-list .rounded-circle i');
    icons.forEach(function(icon) {
        // Remove any margin that might affect centering
        icon.style.margin = '0';
    });
    
    // Add click animation to clickable cards
    const clickableCards = document.querySelectorAll('[data-toggle="modal"]');
    clickableCards.forEach(function(card) {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'translateY(-5px)';
            }, 150);
        });
    });
    
    // Animate progress bars on page load
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(function(bar) {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.transition = 'width 1.5s ease-in-out';
            bar.style.width = width;
        }, 500);
    });
    
    // Add pulse animation to badges
    const newBadges = document.querySelectorAll('.badge');
    newBadges.forEach(function(badge) {
        if (badge.textContent.includes('New') || badge.textContent.includes('Present')) {
            badge.style.animation = 'pulse 2s infinite';
        }
    });
    
    // Add CSS animations via JavaScript
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .card:hover .position-absolute i {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }
        
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }
        
        .modal.show .modal-dialog {
            transform: scale(1);
        }
    `;
    document.head.appendChild(style);
});

    // Initialize DataTable for task management
    document.addEventListener('DOMContentLoaded', function() {
        // Check if jQuery is available
        if (typeof jQuery !== 'undefined') {
            // Initialize DataTable
            var dataTable = jQuery('.data-table').DataTable({
                scrollCollapse: true,
                autoWidth: false,
                responsive: true,
                columnDefs: [{
                    targets: "datatable-nosort",
                    orderable: false,
                }],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "language": {
                    "info": "_START_-_END_ of _TOTAL_ entries",
                    searchPlaceholder: "Search",
                    paginate: {
                        next: '<i class="ion-chevron-right"></i>',
                        previous: '<i class="ion-chevron-left"></i>'
                    },
                },
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            });

            // Custom search functionality
            jQuery('.search-input').on('keyup', function() {
                dataTable.search(jQuery(this).val()).draw();
            });

            // Filter dropdown functionality
            jQuery('.dropdown-menu .dropdown-item').on('click', function(e) {
                e.preventDefault();
                var filterValue = jQuery(this).text().trim();
                
                if (filterValue === 'All') {
                    dataTable.column(4).search('').draw();
                } else {
                    dataTable.column(4).search(filterValue).draw();
                }
            });
        } else {
            console.log('jQuery is not loaded. Please include jQuery library.');
        }
    });
</script>

<?= $this->endSection() ?>