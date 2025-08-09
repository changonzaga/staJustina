<?= $this->extend('backend/teacher/layout/pages-layout') ?>

<?= $this->section('stylesheets') ?>
<link rel="stylesheet" type="text/css" href="/backend/src/plugins/fullcalendar/fullcalendar.css">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/apexcharts/apexcharts.min.js"></script>
<script src="/backend/src/plugins/fullcalendar/fullcalendar.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header bg-white rounded-3 p-4 mb-4">
	<div class="row align-items-center">
		<div class="col-md-8 col-sm-12">
			<div class="d-flex align-items-center">
				<div class="me-3">
					<h4 class="text-primary fw-bold mb-1">Dashboard</h4>
					<nav aria-label="breadcrumb" role="navigation">
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item">
								<a href="<?= site_url('teacher/dashboard') ?>" class="text-decoration-none">Home</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page">
								Attendance Record
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<div class="col-md-4 text-end">
			<button class="btn btn-primary rounded-pill px-4" style="background-color: #6c5ce7;">
				May 2025
			</button>
		</div>
	</div>
</div>

<div class="welcome-section bg-white rounded-3 p-4 mb-4">
	<div class="row align-items-center">
		<div class="col-md-9">
			<h2 class="text-dark fw-bold mb-2">Welcome back! Mrs. Loida Gastilo</h2>
			<p class="text-muted mb-0">Welcome back! We're here to support you as you guide and inspire your students. Step into your classes with confidence and continue shaping the future, one lesson at a time.</p>
		</div>
		<div class="col-md-3 text-end">
			<img src="<?= base_url('public/backend/vendors/images/teacher-profile.jpg') ?>" alt="Teacher Profile" class="rounded-circle border border-3 border-light" width="100" height="100" style="object-fit: cover;">
		</div>
	</div>
</div>

<div class="row mb-4">
	<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex align-items-center mb-3">
					<div class="me-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #00b894;">
						<i class="icon-copy dw dw-graduation-cap text-white" style="font-size: 24px;"></i>
					</div>
					<div>
						<h3 class="fw-bold mb-0 text-dark">405</h3>
						<span class="text-muted">Total Students</span>
					</div>
				</div>
				<div>
					<span class="d-block text-muted mb-2">This Semester</span>
					<div class="progress" style="height: 6px;">
						<div class="progress-bar" role="progressbar" style="width: 85%; background-color: #00b894;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex align-items-center mb-3">
					<div class="me-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #6c5ce7;">
						<i class="icon-copy dw dw-time-1 text-white" style="font-size: 24px;"></i>
					</div>
					<div>
						<h6 class="fw-bold mb-0 text-dark">Yesterday, 9:00 AM</h6>
						<span class="text-muted">Last Attendance</span>
					</div>
				</div>
				<div>
					<span class="d-block text-muted mb-2">Monthly Attendance Rate 89%</span>
					<span class="badge bg-success px-3 py-2 rounded-pill">Present</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex align-items-center mb-3">
					<div class="me-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #a55eea;">
						<i class="icon-copy dw dw-calendar-1 text-white" style="font-size: 24px;"></i>
					</div>
					<div>
						<h6 class="fw-bold mb-0 text-dark">Grade 11 - Jupiter</h6>
						<span class="text-muted">Next Exam</span>
					</div>
				</div>
				<div>
					<span class="d-block text-muted mb-2">May 22, 2025</span>
					<div class="d-flex align-items-center">
						<span class="badge bg-warning text-dark px-3 py-2 rounded-pill me-2">3 Days Left</span>
						<span class="badge bg-primary px-3 py-2 rounded-pill">Upcoming</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex align-items-center mb-3">
					<div class="me-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #ff6b6b;">
						<i class="icon-copy dw dw-megaphone text-white" style="font-size: 24px;"></i>
					</div>
					<div>
						<h6 class="fw-bold mb-0 text-dark">End of The Year Concert</h6>
						<span class="text-muted">Latest Announcement</span>
					</div>
				</div>
				<div>
					<p class="text-muted mb-2 small">Please join us for the end of the year concert on June 2nd at 6:00 pm in...</p>
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-muted small">May 16, 2025</span>
						<div>
							<span class="badge bg-primary me-2">New</span>
							<a href="#" class="btn btn-sm btn-outline-primary rounded-pill">Read More</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Attendance Overview Card -->
<div class="row mb-4">
	<div class="col-12">
		<div class="card rounded-3 border-0 bg-white">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h4 class="h4 mb-0">Attendance Overview</h4>
					<button type="button" class="btn btn-primary btn-sm">View All</button>
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
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h4 class="h4 mb-0" id="currentMonth">May 2025</h4>
					<div>
						<button type="button" class="btn btn-outline-secondary btn-sm me-1" id="prevMonth">
							<i class="fa fa-chevron-left"></i>
						</button>
						<button type="button" class="btn btn-outline-secondary btn-sm me-2" id="nextMonth">
							<i class="fa fa-chevron-right"></i>
						</button>
						<button type="button" class="btn btn-primary btn-sm">View All</button>
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
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Daily Schedule Card -->
	<div class="col-lg-6 col-md-12 col-sm-12 mb-3">
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h4 class="h4 mb-0">Daily Schedule</h4>
					<button type="button" class="btn btn-primary btn-sm">View All</button>
				</div>
				<div class="schedule-list">
					<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
						<div>
							<div class="fw-semibold">8:00 AM - 9:30 AM</div>
							<small class="text-muted">Grade 7 - Sampaguita</small>
						</div>
						<span class="text-primary fw-semibold">Mathematics</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
						<div>
							<div class="fw-semibold">9:45 AM - 11:15 AM</div>
							<small class="text-muted">Grade 11 - Jupiter</small>
						</div>
						<span class="text-primary fw-semibold">Physics</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
						<div>
							<div class="fw-semibold">11:30 AM - 1:00 PM</div>
							<small class="text-muted">Grade 7 - Rose</small>
						</div>
						<span class="text-primary fw-semibold">Chemistry</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-2 border-bottom">
						<div>
							<div class="fw-semibold">1:00 PM - 2:00 PM</div>
							<small class="text-muted">Lunch Break</small>
						</div>
						<span class="text-muted fw-semibold">Break</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-2">
						<div>
							<div class="fw-semibold">2:00 PM - 3:30 PM</div>
							<small class="text-muted">Free Period</small>
						</div>
						<span class="text-muted fw-semibold">Vacant</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Notice Board Card -->
<div class="row mb-4">
	<div class="col-lg-6 col-md-12 col-sm-12 mb-3">
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h4 class="h4 mb-0">Notice Board</h4>
					<button type="button" class="btn btn-primary btn-sm">View All</button>
				</div>
				<div class="notice-list">
					<div class="py-3 border-bottom">
						<div class="d-flex align-items-start mb-2">
							<i class="fa fa-bullhorn text-primary me-4 mt-1" style="font-size: 14px;"></i>
							<div class="flex-grow-1">
								<h6 class="mb-1 fw-semibold text-primary">Urgent Meeting</h6>
								<p class="mb-1 text-dark">Department meeting scheduled at 3 PM in the conference room.</p>
								<small class="text-muted">3 days ago</small>
							</div>
						</div>
					</div>
					<div class="py-3 border-bottom">
						<div class="d-flex align-items-start mb-2">
							<i class="fa fa-calendar text-primary me-4 mt-1" style="font-size: 14px;"></i>
							<div class="flex-grow-1">
								<h6 class="mb-1 fw-semibold">Upcoming Event</h6>
								<p class="mb-1 text-dark">Annual sports festival starts next week. Don't miss it!</p>
								<small class="text-muted">5 days ago</small>
							</div>
						</div>
					</div>
					<div class="py-3">
						<div class="d-flex align-items-start mb-2">
							<i class="fa fa-exclamation-triangle text-warning me-4 mt-1" style="font-size: 14px;"></i>
							<div class="flex-grow-1">
								<h6 class="mb-1 fw-semibold">System Maintenance</h6>
								<p class="mb-1 text-dark">The portal will be down for maintenance this Saturday from 1 AM to 3 AM.</p>
								<small class="text-muted">1 week ago</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Section Rank Count Statistics Card -->
	<div class="col-lg-6 col-md-12 col-sm-12 mb-3">
		<div class="card rounded-3 border-0 h-100 bg-white">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h4 class="h4 mb-0">Section Rank Count Statistics</h4>
					<button type="button" class="btn btn-primary btn-sm">View All</button>
				</div>
				<div>
					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">Grade 7 - Mercury</span>
							<span class="fw-semibold">32 students</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-primary" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">Grade 8 - Venus</span>
							<span class="fw-semibold">28 students</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">Grade 9 - Earth</span>
							<span class="fw-semibold">35 students</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-info" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">Grade 10 - Mars</span>
							<span class="fw-semibold">26 students</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="mb-0">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-medium">Grade 11 - Jupiter</span>
							<span class="fw-semibold">22 students</span>
						</div>
						<div class="progress" style="height: 10px;">
							<div class="progress-bar bg-danger" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
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

	// Initialize calendar with improved styling
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

	// Add custom styling to calendar
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
// Calendar functionality
class Calendar {
    constructor() {
        this.currentDate = new Date();
        this.currentMonth = this.currentDate.getMonth();
        this.currentYear = this.currentDate.getFullYear();
        this.events = {
            // Sample events - replace with actual data
            '2025-05-05': 2, // 2 events on May 5th
            '2025-05-12': 1, // 1 event on May 12th
            '2025-05-18': 3, // 3 events on May 18th
            '2025-05-25': 1, // 1 event on May 25th
            '2025-05-30': 2  // 2 events on May 30th
        };
        this.init();
    }

    init() {
        this.generateCalendar();
        this.bindEvents();
    }

    bindEvents() {
        document.getElementById('prevMonth').addEventListener('click', () => {
            this.currentMonth--;
            if (this.currentMonth < 0) {
                this.currentMonth = 11;
                this.currentYear--;
            }
            this.generateCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            this.currentMonth++;
            if (this.currentMonth > 11) {
                this.currentMonth = 0;
                this.currentYear++;
            }
            this.generateCalendar();
        });
    }

    generateCalendar() {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Update month header
        document.getElementById('currentMonth').textContent = 
            `${monthNames[this.currentMonth]} ${this.currentYear}`;

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

        document.getElementById('calendarBody').innerHTML = html;
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

// Initialize calendar when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new Calendar();
});
</script>

<?= $this->endSection() ?>