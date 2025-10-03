// Debug Enrollment Approval - Run this in browser console
console.log('🔍 Starting enrollment approval debug...');

// Function to test approval
async function testApproval() {
    try {
        // First, let's check if we're authenticated
        console.log('📋 Checking authentication status...');
        
        // Look for pending enrollments in the table
        const pendingRows = document.querySelectorAll('tr[data-status="pending"]');
        console.log(`📊 Found ${pendingRows.length} pending enrollments`);
        
        if (pendingRows.length === 0) {
            console.log('⚠️ No pending enrollments found. Creating a test enrollment...');
            await createTestEnrollment();
            return;
        }
        
        // Get the first pending enrollment
        const firstRow = pendingRows[0];
        const enrollmentId = firstRow.getAttribute('data-id') || firstRow.querySelector('[data-enrollment-id]')?.getAttribute('data-enrollment-id');
        
        if (!enrollmentId) {
            console.log('❌ Could not find enrollment ID in the row');
            console.log('Row HTML:', firstRow.outerHTML.substring(0, 200) + '...');
            return;
        }
        
        console.log(`🎯 Testing approval for enrollment ID: ${enrollmentId}`);
        
        // Test the approval endpoint
        const response = await fetch('/admin/enrollment/approve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                enrollment_id: enrollmentId
            })
        });
        
        console.log(`📡 Response status: ${response.status}`);
        console.log(`📡 Response headers:`, Object.fromEntries(response.headers.entries()));
        
        const responseText = await response.text();
        console.log(`📄 Raw response:`, responseText);
        
        try {
            const jsonResponse = JSON.parse(responseText);
            console.log(`✅ JSON Response:`, jsonResponse);
            
            if (jsonResponse.success) {
                console.log('🎉 Approval successful!');
                // Refresh the page to see changes
                setTimeout(() => location.reload(), 1000);
            } else {
                console.log('❌ Approval failed:', jsonResponse.message);
            }
        } catch (e) {
            console.log('❌ Response is not valid JSON');
            console.log('Response preview:', responseText.substring(0, 500));
        }
        
    } catch (error) {
        console.error('💥 Error during approval test:', error);
    }
}

// Function to create test enrollment
async function createTestEnrollment() {
    try {
        console.log('🏗️ Creating test enrollment...');
        
        const response = await fetch('/admin/enrollment/create-test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                first_name: 'Test',
                last_name: 'Student',
                email: 'test.student@example.com',
                phone: '09123456789',
                course: 'BSIT',
                year_level: '1st Year'
            })
        });
        
        const result = await response.text();
        console.log('Test enrollment creation result:', result);
        
        // Refresh page to see the new enrollment
        setTimeout(() => location.reload(), 1000);
        
    } catch (error) {
        console.error('Error creating test enrollment:', error);
    }
}

// Function to check current page and authentication
function checkPageStatus() {
    console.log('🔍 Current page URL:', window.location.href);
    console.log('🔍 Page title:', document.title);
    
    // Check if we're on the login page
    if (window.location.href.includes('/login')) {
        console.log('⚠️ You are on the login page. Please log in first.');
        return false;
    }
    
    // Check for authentication indicators
    const userInfo = document.querySelector('.user-info, .admin-info, [data-user]');
    if (userInfo) {
        console.log('✅ User authentication detected');
    } else {
        console.log('⚠️ No user authentication indicators found');
    }
    
    return true;
}

// Function to simulate clicking the approve button
function simulateApprovalClick() {
    console.log('🖱️ Looking for approval buttons...');
    
    const approveButtons = document.querySelectorAll('.btn-approve, [data-action="approve"], .approve-btn');
    console.log(`Found ${approveButtons.length} approve buttons`);
    
    if (approveButtons.length > 0) {
        const firstButton = approveButtons[0];
        console.log('🎯 Clicking first approve button...');
        console.log('Button HTML:', firstButton.outerHTML);
        
        // Trigger click event
        firstButton.click();
        
        // Also trigger any event listeners
        const clickEvent = new Event('click', { bubbles: true });
        firstButton.dispatchEvent(clickEvent);
        
        console.log('✅ Button clicked');
    } else {
        console.log('❌ No approve buttons found');
        console.log('Available buttons:', document.querySelectorAll('button').length);
    }
}

// Main execution
console.log('🚀 Starting debug process...');

if (checkPageStatus()) {
    console.log('📋 Choose an option:');
    console.log('1. testApproval() - Test approval via AJAX');
    console.log('2. simulateApprovalClick() - Simulate clicking approve button');
    console.log('3. createTestEnrollment() - Create a test enrollment');
    
    // Auto-run the test
    console.log('🔄 Auto-running approval test in 2 seconds...');
    setTimeout(testApproval, 2000);
}