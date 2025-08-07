<div class="left-side-bar"> 
    <div class="brand-logo"> 
        <a href="index.html" style="display: inline-flex; align-items: center;"> 
            <img src="https://scontent.fmnl13-3.fna.fbcdn.net/v/t1.15752-9/486190369_1714804709415712_2378757229292270536_n.png?_nc_cat=102&ccb=1-7&_nc_sid=0024fc&_nc_eui2=AeEqrWJFfuB7YB8JxW_hcNk8q2NSk0-s-t2rY1KTT6z63W0U5K45PTewWLZ2ewpgYB8JrlYnmEvkgC3ixYo7tyci&_nc_ohc=7Ep537vZeNwQ7kNvwFw_tYE&_nc_oc=Adl4e345h5014RDN5sGoX3pShnmDSp44s4DB936Dinb0WA3OHgXDPJSxknP5BHc-Oaw&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=scontent.fmnl13-3.fna&oh=03_Q7cD2QHMqHk1lpaK_xcKcY_B9FAVYjYWixE2FCtEXacB1acYZA&oe=6856B9B1" alt="Logo" width="40" height="40" /> 
            <span style="color: maroon; font-size: 25px; font-family: sans-serif; font-weight: 900; margin-left: 10px;"> 
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
                    <a href="<?= site_url('student/dashboard'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="home"> 
                        <span class="micon dw dw-home"></span>
                        <span class="mtext">Dashboard</span> 
                    </a> 
                </li> 
                <li class="dropdown"> 
                    <a href="#" class="dropdown-toggle sidebar-link" data-toggle="dropdown" data-page="classes"> 
                        <span class="micon dw dw-mortarboard"></span>
                        <span class="mtext">My Classes</span> 
                    </a> 
                    <ul class="submenu"> 
                        <li><a href="<?= site_url('student/classes/timetable'); ?>">Class Timetable</a></li> 
                        <li><a href="<?= site_url('student/classes/materials'); ?>">Class Materials</a></li> 
                        <li><a href="<?= site_url('student/classes/report-card'); ?>">Report Card</a></li> 
                        <li><a href="<?= site_url('student/classes/attendance'); ?>">Attendance Record</a></li> 
                    </ul> 
                </li> 
                <li> 
                    <a href="<?= site_url('student/exams'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="exams"> 
                        <span class="micon bi bi-file-text"></span>
                        <span class="mtext">Exam Schedule</span> 
                    </a> 
                </li> 
               
                <li> 
                    <a href="<?= site_url('student/announcements'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="announcements"> 
                        <span class="micon dw dw-megaphone"></span> 
                        <span class="mtext">Announcements</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= site_url('student/events'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="events"> 
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
        if (currentPath.includes(href) || (href.includes('admin.home') && currentPath.includes('/admin'))) {
            link.classList.add('active');
        }
    });
    */
});
</script>