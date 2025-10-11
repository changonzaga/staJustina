<div class="left-side-bar"> 
    <div class="brand-logo"> 
        <a href="index.html" style="display: inline-flex; align-items: center;"> 
            <img src="/backend/vendors/images/logo-login.png" alt="Logo" width="40" height="40" /> 
            <span style="color: #ffffff; font-size: 25px; font-family: 'Roboto', Arial, sans-serif; font-weight: 600; margin-left: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);"> 
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
                    <a href="<?= route_to('admin.home'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="home"> 
                        <span class="micon dw dw-home"></span>
                        <span class="mtext">Home</span> 
                    </a> 
                </li>
                <li> 
                    <a href="<?= route_to('admin.enrollment'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="enrollment"> 
                        <span class="micon dw dw-user-13"></span>
                        <span class="mtext">Enrollments</span> 
                    </a> 
                </li>  
                <li> 
                    <a href="<?= route_to('admin.student'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="student"> 
                        <span class="micon dw dw-mortarboard"></span>
                        <span class="mtext">Students</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= route_to('admin.teacher'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="teacher"> 
                        <span class="micon bi bi-person"></span>
                        <span class="mtext">Teachers</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= route_to('admin.parent'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="parent"> 
                        <span class="micon bi bi-people"></span>
                        <span class="mtext">Parents/Guardians</span> 
                    </a> 
                </li>
                <li> 
                    <a href="<?= route_to('admin.department'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="department"> 
                        <span class="micon bi bi-building"></span>
                        <span class="mtext">Departments</span> 
                    </a> 
                </li>
                <li> 
                    <a href="<?= route_to('admin.section'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="section"> 
                        <span class="micon bi bi-collection"></span>
                        <span class="mtext">Sections</span> 
                    </a> 
                </li>
                <li> 
                    <a href="<?= route_to('admin.class'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="class"> 
                        <span class="micon bi bi-mortarboard"></span>
                        <span class="mtext">Class</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= route_to('admin.subjects'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="subjects"> 
                        <span class="micon bi bi-book"></span>
                        <span class="mtext">Subjects</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= route_to('admin.announcement'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="announcement"> 
                        <span class="micon dw dw-megaphone"></span> 
                        <span class="mtext">Announcements</span> 
                    </a> 
                </li>
                <li> 
                    <a href="<?= route_to('admin.event'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="event"> 
                        <span class="micon dw dw-calendar"></span>
                        <span class="mtext">Events</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= route_to('admin.users'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="users"> 
                        <span class="micon bi bi-person-workspace"></span>
                        <span class="mtext">Users</span> 
                    </a> 
                </li> 
                <li> 
                    <div class="sidebar-small-cap">Settings</div> 
                </li> 
                <li> 
                    <a href="<?= route_to('admin.profile'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="profile"> 
                        <span class="micon dw dw-user1"></span>
                        <span class="mtext">Profile</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= route_to('admin.settings.general'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="settings"> 
                        <span class="micon bi bi-gear"></span>
                        <span class="mtext">General Settings</span> 
                    </a> 
                </li> 
                <li> 
                    <a href="<?= route_to('admin.logout'); ?>" class="dropdown-toggle no-arrow sidebar-link" data-page="logout"> 
                        <span class="micon dw dw-logout"></span>
                        <span class="mtext">Log Out</span> 
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