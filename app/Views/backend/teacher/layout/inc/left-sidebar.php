<div class="left-side-bar"> 
    <div class="brand-logo"> 
        <a href="index.html" style="display: inline-flex; align-items: center;"> 
            <img src="/backend/vendors/images/logo-login.png" alt="Logo" width="40" height="40" /> 
            <span style="color: #ffffff; font-size: 25px; font-family: 'Roboto', Arial, sans-serif; font-weight: 900; margin-left: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);"> 
                SJNHS 
            </span> 
        </a>    
        <div class="close-sidebar" data-toggle="left-sidebar-close"> 
            <i class="ion-close-round"></i> 
        </div> 
    </div> 
    <div class="menu-block customscroll"> 
        <div class="sidebar-menu"> 
            <ul id="accordion-menu"> 
                <li> 
                    <a href="<?= site_url('teacher/home'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="home"> 
                        <span class="micon dw dw-home"></span>
                        <span class="mtext">Home</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= site_url('teacher/classroom'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="classroom"> 
                        <span class="micon dw dw-mortarboard"></span>
                        <span class="mtext">Classroom</span> 
                    </a> 
                </li>
                <li class="dropdown"> 
                    <a href="#" class="dropdown-toggle sidebar-link" data-toggle="dropdown" data-page="manage-students"> 
                        <span class="micon dw dw-user-2"></span>
                        <span class="mtext">Manage Student</span> 
                    </a> 
                    <ul class="submenu"> 
                        <li><a href="<?= site_url('teacher/manage-students/report-cards'); ?>">Report Cards</a></li> 
                        <li><a href="<?= site_url('teacher/manage-students/attendance-records'); ?>">Attendance Record</a></li> 
                    </ul> 
                </li>  
                <li> 
                    <a href="<?= site_url('teacher/exam-schedule'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="exam-schedule"> 
                        <span class="micon dw dw-edit-file"></span>
                        <span class="mtext">Exam Schedule</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= site_url('teacher/announcements'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="announcements"> 
                        <span class="micon dw dw-megaphone"></span> 
                        <span class="mtext">Announcements</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= site_url('teacher/events'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="events"> 
                        <span class="micon dw dw-calendar"></span>
                        <span class="mtext">Events</span> 
                    </a> 
                </li>

            </ul> 
        </div> 
    </div> 
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all sidebar links
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    
    // Get current page from URL or set a default method to determine active page
    const currentPath = window.location.pathname;
    
    // Function to set active link
    function setActiveLink(clickedLink) {
        // Remove active class from all links
        sidebarLinks.forEach(link => {
            link.classList.remove('active');
        });
        
        // Add active class to clicked link
        clickedLink.classList.add('active');
        
        // Store active page in localStorage to persist across page loads
        const pageData = clickedLink.getAttribute('data-page');
        localStorage.setItem('activePage', pageData);
    }
    
    // Function to restore active state on page load
    function restoreActiveState() {
        const activePage = localStorage.getItem('activePage');
        
        if (activePage) {
            const activeLink = document.querySelector(`[data-page="${activePage}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        } else {
            // If no stored active page, you can set a default (e.g., home)
            const homeLink = document.querySelector('[data-page="home"]');
            if (homeLink) {
                homeLink.classList.add('active');
            }
        }
    }
    
    // Add click event listeners to all sidebar links
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Don't prevent default to allow navigation
            setActiveLink(this);
        });
    });
    
    // Restore active state on page load
    restoreActiveState();
    
    // Alternative method: Set active based on current URL
    // You can use this instead of localStorage if you prefer URL-based detection
    /*
    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (currentPath.includes(href) || (href.includes('teacher/home') && currentPath.includes('/teacher'))) {
            link.classList.add('active');
        }
    });
    */
});
</script>