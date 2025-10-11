<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<!-- Teacher Search Functions - Defined Early -->
<script>
// Global variables
var teacherSearchAjaxUrl = '<?= site_url(route_to('admin.department.teachers')) ?>';
var teachersData = <?= json_encode($teachers ?? []) ?>;


// Debug function - comprehensive testing
function debugTeacherSearch() {
    console.log('=== DEBUG TEACHER SEARCH ===');
    console.log('AJAX URL:', teacherSearchAjaxUrl);
    console.log('Teachers data:', teachersData);
    
    // Test AJAX call directly and show results
    if (typeof $ !== 'undefined') {
        // Show loading state
        $('#teacher-search-results').show();
        $('#teacher-search-list').html('<div class="search-loading">Debug: Testing search...</div>');
        
        $.ajax({
            url: teacherSearchAjaxUrl,
            method: 'GET',
            data: { search: 'Test' },
            dataType: 'json',
            beforeSend: function() {
                console.log('AJAX request starting...');
            },
            success: function(response) {
                console.log('AJAX Success Response:', response);
                if (response && response.results) {
                    console.log('Found teachers:', response.results.length);
                    
                    // Display results in the UI
                    var html = '';
                    response.results.forEach(function(teacher, index) {
                        console.log('Teacher ' + (index + 1) + ':', teacher);
                        html += '<div class="teacher-search-item" data-id="' + teacher.id + '" data-text="' + teacher.text + '" data-name="' + teacher.full_name + '" onclick="selectTeacher(' + teacher.id + ', \'' + teacher.full_name + '\', \'' + teacher.text + '\')">';
                        html += '<div class="teacher-name">' + teacher.full_name + '</div>';
                        html += '<div class="teacher-details">' + teacher.position + ' - ' + teacher.status + '</div>';
                        html += '</div>';
                    });
                    $('#teacher-search-list').html(html);
                    console.log('Results displayed in UI with onclick handlers');
                    console.log('HTML created:', html);
                    
                    // Test if click handler is working
                    setTimeout(function() {
                        var items = $('.teacher-search-item');
                        console.log('Found ' + items.length + ' clickable items');
                        if (items.length > 0) {
                            console.log('First item data:', {
                                id: items.first().data('id'),
                                name: items.first().data('name'),
                                text: items.first().data('text')
                            });
                            
                            // Add click handler directly to each item
                            items.each(function(index) {
                                $(this).off('click').on('click', function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    
                                    console.log('Direct click handler triggered for item:', index);
                                    
                                    var teacherId = $(this).data('id');
                                    var teacherText = $(this).data('text');
                                    var teacherName = $(this).data('name');
                                    
                                    console.log('Teacher data from direct handler:', {
                                        id: teacherId,
                                        text: teacherText,
                                        name: teacherName
                                    });
                                    
                                    if (teacherId) {
                                        handleSearchSelection(teacherId, teacherText, teacherName, 'add');
                                    }
                                });
                            });
                            
                            console.log('Direct click handlers attached to all items');
                        }
                    }, 100);
                } else {
                    console.log('No results in response');
                    $('#teacher-search-list').html('<div class="search-no-results">No teachers found</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);
                $('#teacher-search-list').html('<div class="search-no-results">Error: ' + error + '</div>');
            }
        });
    } else {
        console.error('jQuery not loaded');
        alert('jQuery not loaded');
    }
}


// Simple selectTeacher function for onclick handlers
function selectTeacher(teacherId, teacherName, teacherText) {
    console.log('=== SELECT TEACHER CALLED ===');
    console.log('Parameters:', {
        teacherId: teacherId,
        teacherName: teacherName,
        teacherText: teacherText
    });
    
    // Update the select dropdown
    var selectElement = $('#dept_head_select');
    if (selectElement.length > 0) {
        selectElement.val(teacherId).trigger('change');
        console.log('Select dropdown updated with value:', teacherId);
    } else {
        console.error('Select element not found');
    }
    
    // Hide search results
    var resultsContainer = $('#teacher-search-results');
    if (resultsContainer.length > 0) {
        resultsContainer.hide();
        console.log('Search results hidden');
    } else {
        console.error('Results container not found');
    }
    
    // Clear search input
    var searchInput = $('#teacher-search-input');
    if (searchInput.length > 0) {
        searchInput.val('');
        console.log('Search input cleared');
    } else {
        console.error('Search input not found');
    }
    
    // Show success message
    alert('Teacher selected: ' + teacherName + ' (ID: ' + teacherId + ')');
    console.log('Teacher selection completed');
}

// Global handleSearchSelection function
function handleSearchSelection(teacherId, teacherText, teacherName, modalType) {
    console.log('=== HANDLE SEARCH SELECTION CALLED ===');
    console.log('Parameters:', {
        teacherId: teacherId,
        teacherText: teacherText,
        teacherName: teacherName,
        modalType: modalType
    });
    
    var isAdd = modalType === 'add';
    var selectId = isAdd ? '#dept_head_select' : '#edit_dept_head_select';
    var searchInputId = isAdd ? '#teacher-search-input' : '#edit-teacher-search-input';
    var resultsContainerId = isAdd ? '#teacher-search-results' : '#edit-teacher-search-results';
    
    console.log('Target elements:', {
        selectId: selectId,
        searchInputId: searchInputId,
        resultsContainerId: resultsContainerId
    });
    
    // Check if elements exist
    var selectElement = $(selectId);
    var searchInputElement = $(searchInputId);
    var resultsContainerElement = $(resultsContainerId);
    
    console.log('Element existence check:', {
        selectExists: selectElement.length > 0,
        searchInputExists: searchInputElement.length > 0,
        resultsContainerExists: resultsContainerElement.length > 0
    });
    
    if (selectElement.length > 0) {
        // Update the select dropdown to show the selected teacher
        selectElement.val(teacherId).trigger('change');
        console.log('Select dropdown updated with value:', teacherId);
    } else {
        console.error('Select element not found:', selectId);
    }
    
    if (resultsContainerElement.length > 0) {
        // Hide search results
        resultsContainerElement.hide();
        console.log('Search results hidden');
    } else {
        console.error('Results container not found:', resultsContainerId);
    }
    
    if (searchInputElement.length > 0) {
        // Clear search input
        searchInputElement.val('');
        console.log('Search input cleared');
    } else {
        console.error('Search input not found:', searchInputId);
    }
    
    // Show success message
    console.log('Teacher selection completed - ID:', teacherId, 'Name:', teacherName);
    alert('Teacher selected: ' + teacherName + ' (ID: ' + teacherId + ')');
}

// Global handleFormSubmission function
function handleFormSubmission() {
    console.log('=== HANDLE FORM SUBMISSION CALLED ===');
    
    const selectedTeacherId = $('#dept_head_select').val();
    const selectedTeacherName = $('#dept_head_select option:selected').text();
    
    console.log('=== FORM SUBMISSION ===');
    console.log('Selected Teacher ID:', selectedTeacherId);
    console.log('Selected Teacher Name:', selectedTeacherName);
    console.log('Department Name:', $('#department_name').val());
    console.log('Description:', $('#description').val());
    
    // Validate required fields
    if (!selectedTeacherId || selectedTeacherId === '') {
        alert('Please select a department head before submitting.');
        return;
    }
    
    if (!$('#department_name').val().trim()) {
        alert('Please enter a department name.');
        return;
    }
    
    const formData = {
        department_name: $('#department_name').val().trim(),
        description: $('#description').val().trim(),
        head_id: selectedTeacherId,
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    };
    
    console.log('Form data being submitted:', formData);
    
    $.ajax({
        url: '<?= site_url(route_to('admin.department.store')) ?>',
        method: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            console.log('Sending AJAX request to:', '<?= site_url(route_to('admin.department.store')) ?>');
            console.log('Request data:', formData);
        },
        success: function(response) {
            console.log('AJAX Success Response:', response);
            if (response.success) {
                alert('Department created successfully');
                $('#addDepartmentModal').modal('hide');
                location.reload();
            } else {
                let errorMsg = 'Failed to create department';
                if (response.errors) {
                    errorMsg += ': ' + Object.values(response.errors).join(', ');
                }
                if (response.message) {
                    errorMsg += ': ' + response.message;
                }
                console.error('Form submission failed:', response);
                alert(errorMsg);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            alert('Failed to create department: ' + error + '\nStatus: ' + xhr.status + '\nResponse: ' + xhr.responseText);
        }
    });
}

// Test click function
function testClick() {
    console.log('=== TESTING CLICK ===');
    var items = $('.teacher-search-item');
    console.log('Found', items.length, 'clickable items');
    
    if (items.length > 0) {
        console.log('First item element:', items.first()[0]);
        console.log('First item data:', items.first().data());
        
        // Try direct function call instead of trigger
        var firstItem = items.first();
        var teacherId = firstItem.data('id');
        var teacherText = firstItem.data('text');
        var teacherName = firstItem.data('name');
        
        console.log('Direct data extraction:', {
            id: teacherId,
            text: teacherText,
            name: teacherName
        });
        
        if (teacherId) {
            console.log('Calling selectTeacher directly...');
            selectTeacher(teacherId, teacherName, teacherText);
        } else {
            console.error('No teacher ID found in first item');
        }
    } else {
        console.log('No clickable items found. Click Debug first to load search results.');
        alert('No clickable items found. Click Debug first to load search results.');
    }
}

// Test form submit function
function testFormSubmit() {
    console.log('=== TESTING FORM SUBMIT ===');
    
    // Set test data
    $('#department_name').val('Test Department');
    $('#description').val('Test Description');
    $('#dept_head_select').val('29'); // Use the teacher ID from your debug response
    
    console.log('Form data set:', {
        department_name: $('#department_name').val(),
        description: $('#description').val(),
        head_id: $('#dept_head_select').val()
    });
    
    // Test AJAX call directly first
    testAjaxCall();
}

// Test AJAX call directly
function testAjaxCall() {
    console.log('=== TESTING AJAX CALL DIRECTLY ===');
    
    var testData = {
        department_name: 'Test Department Direct',
        description: 'Test Description Direct',
        head_id: '29',
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    };
    
    console.log('Test data:', testData);
    console.log('AJAX URL:', '<?= site_url(route_to('admin.department.store')) ?>');
    
    $.ajax({
        url: '<?= site_url(route_to('admin.department.store')) ?>',
        method: 'POST',
        data: testData,
        dataType: 'json',
        beforeSend: function() {
            console.log('Direct AJAX request starting...');
        },
        success: function(response) {
            console.log('Direct AJAX Success Response:', response);
            if (response.success) {
                alert('Direct AJAX test successful! Department created.');
            } else {
                console.error('Direct AJAX failed:', response);
                alert('Direct AJAX test failed: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Direct AJAX Error:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            alert('Direct AJAX test error: ' + error + '\nStatus: ' + xhr.status);
        }
    });
}

// Verify functions are defined
console.log('Teacher search functions loaded:', {
    testTeacherSearch: typeof testTeacherSearch,
    debugTeacherSearch: typeof debugTeacherSearch,
    testClick: typeof testClick,
    ajaxUrl: teacherSearchAjaxUrl,
    teachersCount: teachersData ? teachersData.length : 0
});
</script>

<?php
// Helper function to get teacher name from available fields
function getTeacherName($teacher) {
    if (isset($teacher['name']) && !empty($teacher['name'])) {
        return $teacher['name'];
    } elseif (isset($teacher['first_name']) && isset($teacher['last_name'])) {
        $firstName = $teacher['first_name'] ?? '';
        $lastName = $teacher['last_name'] ?? '';
        return trim($firstName . ' ' . $lastName);
    } elseif (isset($teacher['first_name'])) {
        return $teacher['first_name'] ?? 'Unknown';
    } elseif (isset($teacher['last_name'])) {
        return $teacher['last_name'] ?? 'Unknown';
    }
    return 'Unknown Teacher';
}

// Helper function to get teacher subjects/position
function getTeacherPosition($teacher) {
    if (isset($teacher['subjects']) && !empty($teacher['subjects'])) {
        return $teacher['subjects'];
    } elseif (isset($teacher['position']) && !empty($teacher['position'])) {
        return $teacher['position'];
    } elseif (isset($teacher['specialization']) && !empty($teacher['specialization'])) {
        return $teacher['specialization'];
    }
    return 'Teacher';
}
?>

<div class="page-header bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
    <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <h4 class="text-primary fw-bold mb-1">Department Management</h4>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?= route_to('admin.home') ?>" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Departments
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addDepartmentModal">
                    <i class="icon-copy bi bi-plus-lg"></i> Add Department
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-3">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="card-box p-3 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-primary"><?= count($departments) ?></div>
                <div class="icon text-primary">
                    <i class="icon-copy bi bi-building" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Total Departments</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="card-box p-3 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-success"><?= count(array_filter($departments, function($dept) { return !empty($dept['head_id']); })) ?></div>
                <div class="icon text-success">
                    <i class="icon-copy bi bi-person-check" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">With Heads</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="card-box p-3 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-warning"><?= count(array_filter($departments, function($dept) { return empty($dept['head_id']); })) ?></div>
                <div class="icon text-warning">
                    <i class="icon-copy bi bi-person-x" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Without Heads</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="card-box p-3 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-info"><?= count($teachers) ?></div>
                <div class="icon text-info">
                    <i class="icon-copy bi bi-people" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Available Teachers</div>
        </div>
    </div>
</div>

<!-- Main Container -->
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Departments List</h4>
    </div>
    
    <!-- Filter, Search, and Export Buttons Section -->
    <div class="pd-20 pt-0">
        <div class="row align-items-end">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Search Departments:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by name or description" onkeyup="filterTable()">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;">
                            <i class="icon-copy bi bi-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Filter Status:</label>
                    <select class="form-control" id="statusFilter" onchange="filterTable()">
                        <option value="">All Status</option>
                        <option value="with_head">With Head</option>
                        <option value="without_head">Without Head</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end">
                        <div class="dt-buttons btn-group flex-wrap">
                            <button id="copyBtn" class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="DataTables_Table_2" type="button" onclick="handleCopyClick(this)">
                                <i class="icon-copy bi bi-clipboard"></i> <span>Copy</span>
                            </button>
                            <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="DataTables_Table_2" type="button">
                                <i class="icon-copy bi bi-filetype-csv"></i> <span>CSV</span>
                            </button>
                            <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="DataTables_Table_2" type="button">
                                <i class="icon-copy bi bi-file-pdf"></i> <span>PDF</span>
                            </button>
                            <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="DataTables_Table_2" type="button">
                                <i class="icon-copy bi bi-printer"></i> <span>Print</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table hover multiple-select-row data-table-export nowrap" id="departmentsTable">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">No.</th>
                        <th>Department</th>
                        <th>Description</th>
                        <th>Head</th>
                        <th>Status</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($departments)): ?>
                        <?php foreach ($departments as $key => $department): ?>
                            <tr>
                                <td class="table-plus"><?= $key + 1 ?></td>
                                <td>
                                    <div class="weight-600"><?= esc($department['department_name']) ?></div>
                                    <div class="font-12 color-text-color-2">Department</div>
                                </td>
                                <td>
                                    <?php if (!empty($department['description'])): ?>
                                        <?= esc($department['description']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">No description</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($department['head_id']): ?>
                                        <?php
                                        $headTeacher = null;
                                        foreach ($teachers as $teacher) {
                                            if ($teacher['id'] == $department['head_id']) {
                                                $headTeacher = $teacher;
                                                break;
                                            }
                                        }
                                        ?>
                                        <?php if ($headTeacher): ?>
                                            <div class="weight-600 font-14"><?= esc(getTeacherName($headTeacher)) ?></div>
                                            <div class="font-12 color-text-color-2">Head</div>
                                        <?php else: ?>
                                            <span class="text-muted">Teacher not found</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No head assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($department['head_id']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="#" onclick="editDepartment(<?= $department['id'] ?>)">
                                                <i class="dw dw-edit2"></i> Edit
                                            </a>
                                            <a class="dropdown-item text-danger" href="#" onclick="deleteDepartment(<?= $department['id'] ?>)">
                                                <i class="dw dw-delete-3"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="py-4"> 
                                    <i class="dw dw-building font-48 text-muted"></i> 
                                    <h5 class="text-muted mt-3">No departments found</h5> 
                                    <p class="text-muted">Start by adding your first department to the system.</p> 
                                    <button class="btn btn-success" data-toggle="modal" data-target="#addDepartmentModal"> 
                                        <i class="icon-copy bi bi-plus-lg"></i> Add First Department 
                                    </button> 
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addDepartmentForm" method="POST" action="javascript:void(0);" onsubmit="return false;">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="department_name">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="department_name" name="department_name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="dept_head_select">Department Head</label>
                        <select class="form-control" id="dept_head_select" name="head_id">
                            <option value="">Select Department Head</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['id'] ?>" data-name="<?= esc(getTeacherName($teacher)) ?>">
                                    <?= esc(getTeacherName($teacher)) ?> (<?= esc(getTeacherPosition($teacher)) ?> - <?= esc($teacher['status'] ?? 'Active') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <!-- Teacher Search Section -->
                        <div class="mt-3">
                            <label>Or search for a teacher:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="teacher-search-input" placeholder="Type teacher name...">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="debugTeacherSearch()" title="Load Search Results">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="testClick()" title="Test Selection">
                                        <i class="fas fa-mouse-pointer"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="testFormSubmit()" title="Test Form Submit">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="teacher-search-results" class="search-results" style="display: none;">
                                <div id="teacher-search-list"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" onclick="handleFormSubmission(); return false;">Add Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDepartmentForm">
                <input type="hidden" id="edit_department_id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_department_name">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_department_name" name="department_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_dept_head_select">Department Head</label>
                        <select class="form-control" id="edit_dept_head_select" name="head_id">
                            <option value="">Select Department Head</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['id'] ?>" data-name="<?= esc(getTeacherName($teacher)) ?>">
                                    <?= esc(getTeacherName($teacher)) ?> (<?= esc(getTeacherPosition($teacher)) ?> - <?= esc($teacher['status'] ?? 'Active') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <!-- Teacher Search Section -->
                        <div class="mt-3">
                            <label>Or search for a teacher:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="edit-teacher-search-input" placeholder="Type teacher name...">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="testTeacherSearch()">Test Search</button>
                                </div>
                            </div>
                            <div id="edit-teacher-search-results" class="search-results" style="display: none;">
                                <div id="edit-teacher-search-list"></div>
                            </div>
                        </div>
                        
                        <input type="hidden" id="edit_dept_head_id" name="head_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- CSS Styles for Teacher Search -->
<style>
.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: block !important;
}

#teacher-search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 100%;
    margin-top: 2px;
}

#edit-teacher-search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.teacher-search-item {
    padding: 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

.teacher-search-item:hover {
    background-color: #007bff;
    color: white;
    border-left-color: #0056b3;
    transform: translateX(2px);
    box-shadow: 0 2px 4px rgba(0,123,255,0.3);
}

.teacher-search-item:active {
    background-color: #0056b3;
    color: white;
    transform: translateX(0);
    box-shadow: 0 1px 2px rgba(0,123,255,0.5);
}

.teacher-search-item:last-child {
    border-bottom: none;
}

.teacher-name {
    font-weight: bold;
    color: #333;
}

.teacher-details {
    font-size: 0.9em;
    color: #666;
}

.search-loading {
    padding: 10px;
    text-align: center;
    color: #666;
}

.search-no-results {
    padding: 10px;
    text-align: center;
    color: #999;
    font-style: italic;
}

.input-group {
    position: relative;
}

.mt-3 {
    position: relative;
}
</style>

<!-- Main Teacher Search Script -->
<script>
console.log('Main script loading...');

$(document).ready(function() {
    console.log('DOM ready, initializing teacher search...');
    
    // Use the global variables defined earlier
    var ajaxUrl = teacherSearchAjaxUrl;
    console.log('Using AJAX URL:', ajaxUrl);
    
    // Function to perform teacher search
    function performTeacherSearch(searchTerm, modalType) {
        console.log('performTeacherSearch called:', searchTerm, modalType);
        
        if (!searchTerm || searchTerm.trim().length < 2) {
            console.log('Search term too short');
            return;
        }
        
        var isAdd = modalType === 'add';
        var searchInputId = isAdd ? '#teacher-search-input' : '#edit-teacher-search-input';
        var resultsContainerId = isAdd ? '#teacher-search-results' : '#edit-teacher-search-results';
        var searchListId = isAdd ? '#teacher-search-list' : '#edit-teacher-search-list';
        var hiddenInputId = isAdd ? '#dept_head_id' : '#edit_dept_head_id';
        var selectId = isAdd ? '#dept_head_select' : '#edit_dept_head_select';
        
        console.log('Search elements:', {
            searchInputId: searchInputId,
            resultsContainerId: resultsContainerId,
            searchListId: searchListId,
            hiddenInputId: hiddenInputId,
            selectId: selectId
        });
        
        // Show loading state
        console.log('Showing loading state for:', resultsContainerId);
        $(searchListId).html('<div class="search-loading">Searching teachers...</div>');
        $(resultsContainerId).show();
        console.log('Results container shown:', $(resultsContainerId).is(':visible'));
        
        // Perform AJAX search
        $.ajax({
            url: ajaxUrl,
            method: 'GET',
            data: { search: searchTerm.trim() },
            dataType: 'json',
            beforeSend: function() {
                console.log('AJAX request starting to:', ajaxUrl);
            },
            success: function(response) {
                console.log('AJAX success - Response:', response);
                console.log('Target elements:', {
                    searchListId: searchListId,
                    resultsContainerId: resultsContainerId,
                    searchListExists: $(searchListId).length,
                    resultsContainerExists: $(resultsContainerId).length
                });
                
                if (response && response.results && response.results.length > 0) {
                    var html = '';
                    response.results.forEach(function(teacher) {
                        html += '<div class="teacher-search-item" data-id="' + teacher.id + '" data-text="' + teacher.text + '" data-name="' + teacher.full_name + '">';
                        html += '<div class="teacher-name">' + teacher.full_name + '</div>';
                        html += '<div class="teacher-details">' + teacher.position + ' - ' + teacher.status + '</div>';
                        html += '</div>';
                    });
                    $(searchListId).html(html);
                    console.log('Search results HTML set:', html);
                    console.log('Results container visible after update:', $(resultsContainerId).is(':visible'));
                    console.log('Search results displayed:', response.results.length);
                    
                    // Add direct click handlers to search results
                    setTimeout(function() {
                        var items = $(searchListId + ' .teacher-search-item');
                        console.log('Adding direct click handlers to', items.length, 'items');
                        
                        items.each(function(index) {
                            $(this).off('click').on('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                
                                console.log('Search result clicked:', index);
                                
                                var teacherId = $(this).data('id');
                                var teacherText = $(this).data('text');
                                var teacherName = $(this).data('name');
                                
                                console.log('Teacher data:', {
                                    id: teacherId,
                                    text: teacherText,
                                    name: teacherName
                                });
                                
                                if (teacherId) {
                                    handleSearchSelection(teacherId, teacherText, teacherName, modalType);
                                }
                            });
                        });
                        
                        console.log('Direct click handlers attached to search results');
                    }, 50);
                } else {
                    $(searchListId).html('<div class="search-no-results">No teachers found matching "' + searchTerm + '". Try "Test" or add more teachers.</div>');
                    console.log('No search results found');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                console.error('Response text:', xhr.responseText);
                
                // Fallback to local search using dropdown options
                var fallbackTeachers = [];
                $(selectId + ' option').each(function() {
                    if ($(this).val() !== '') {
                        fallbackTeachers.push({
                            id: $(this).val(),
                            text: $(this).text(),
                            full_name: $(this).data('name') || $(this).text().split(' (')[0]
                        });
                    }
                });
                
                var filteredTeachers = fallbackTeachers.filter(function(teacher) {
                    return teacher.text.toLowerCase().includes(searchTerm.toLowerCase());
                });
                
                if (filteredTeachers.length > 0) {
                    var html = '';
                    filteredTeachers.forEach(function(teacher) {
                        var teacherName = teacher.full_name;
                        var teacherDetails = teacher.text.split(' (')[1] ? teacher.text.split(' (')[1].replace(')', '') : '';
                        
                        html += '<div class="teacher-search-item" data-id="' + teacher.id + '" data-text="' + teacher.text + '" data-name="' + teacherName + '">';
                        html += '<div class="teacher-name">' + teacherName + '</div>';
                        html += '<div class="teacher-details">' + teacherDetails + '</div>';
                        html += '</div>';
                    });
                    $(searchListId).html(html);
                    console.log('Fallback results loaded:', filteredTeachers.length);
                } else {
                    $(searchListId).html('<div class="search-no-results">No teachers found matching "' + searchTerm + '" (fallback).</div>');
                }
            }
        });
    }
    
    // Handle teacher selection from search results
    function handleSearchSelection(teacherId, teacherText, teacherName, modalType) {
        const isAdd = modalType === 'add';
        const hiddenInputId = isAdd ? '#dept_head_id' : '#edit_dept_head_id';
        const selectId = isAdd ? '#dept_head_select' : '#edit_dept_head_select';
        const searchInputId = isAdd ? '#teacher-search-input' : '#edit-teacher-search-input';
        const resultsContainerId = isAdd ? '#teacher-search-results' : '#edit-teacher-search-results';
        
        // Set the hidden input value
        $(hiddenInputId).val(teacherId);
        
        // Update the select dropdown to show the selected teacher
        $(selectId).val(teacherId).trigger('change');
        
        // Hide search results
        $(resultsContainerId).hide();
        
        // Clear search input
        $(searchInputId).val('');
        
        console.log('Teacher selected:', {id: teacherId, name: teacherName, modal: modalType});
    }
    
    // Setup click handlers for search results
    function setupSearchClickHandlers() {
        $(document).on('click', '.teacher-search-item', function() {
            const teacherId = $(this).data('id');
            const teacherText = $(this).data('text');
            const teacherName = $(this).data('name');
            
            // Determine which modal this is for based on the container
            const modalType = $(this).closest('#teacher-search-results').length > 0 ? 'add' : 'edit';
            
            handleSearchSelection(teacherId, teacherText, teacherName, modalType);
        });
    }
    
    
    
    // Clear selected teacher
    function clearSelectedTeacher(modalType) {
        const isAdd = modalType === 'add';
        const hiddenInputId = isAdd ? '#dept_head_id' : '#edit_dept_head_id';
        const selectId = isAdd ? '#dept_head_select' : '#edit_dept_head_select';
        const searchInputId = isAdd ? '#teacher-search-input' : '#edit-teacher-search-input';
        const resultsContainerId = isAdd ? '#teacher-search-results' : '#edit-teacher-search-results';
        
        $(hiddenInputId).val('');
        $(selectId).val('').trigger('change');
        $(searchInputId).val('');
        $(resultsContainerId).hide();
        
        console.log('Cleared teacher selection for modal:', modalType);
    }
    
    // Initialize search functionality
    setupSearchClickHandlers();
    
    // Debounce for input events (updated)
    let debounceTimerAdd, debounceTimerEdit;
    $('#teacher-search-input').on('input', function() {
        clearTimeout(debounceTimerAdd);
        const term = $(this).val().trim();
        debounceTimerAdd = setTimeout(() => {
            if (term.length >= 2) performTeacherSearch(term, 'add');
        }, 300);
    });
    
    $('#edit-teacher-search-input').on('input', function() {
        clearTimeout(debounceTimerEdit);
        const term = $(this).val().trim();
        debounceTimerEdit = setTimeout(() => {
            if (term.length >= 2) performTeacherSearch(term, 'edit');
        }, 300);
    });
    
    // Department CRUD Operations
    function editDepartment(id) {
        $.ajax({
            url: '<?= base_url('admin/department/show') ?>/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const dept = response.data;
                    $('#edit_department_id').val(dept.id);
                    $('#edit_department_name').val(dept.department_name);
                    $('#edit_description').val(dept.description);
                    $('#edit_dept_head_select').val(dept.head_id || '');
                    $('#edit_dept_head_id').val(dept.head_id || '');
                    $('#editDepartmentModal').modal('show');
                } else {
                    alert('Failed to load department data: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading department:', error);
                alert('Failed to load department data');
            }
        });
    }
    
    function deleteDepartment(id) {
        if (confirm('Are you sure you want to delete this department?')) {
            $.ajax({
                url: '<?= base_url('admin/department/delete') ?>/' + id,
                method: 'POST',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Department deleted successfully');
                        location.reload();
                    } else {
                        alert('Failed to delete department: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting department:', error);
                    alert('Failed to delete department');
                }
            });
        }
    }
    
    // Add click handler to submit button for debugging
    $('button[type="submit"]').on('click', function(e) {
        console.log('=== SUBMIT BUTTON CLICKED ===');
        console.log('Button element:', this);
        console.log('Form element:', $(this).closest('form')[0]);
    });
    
    // Form submissions - using both submit event and button click
    $('#addDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('=== FORM SUBMIT EVENT TRIGGERED ===');
        handleFormSubmission();
        return false;
    });
    
    // Also handle button click directly
    $('#addDepartmentForm button[type="submit"]').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('=== SUBMIT BUTTON CLICKED ===');
        handleFormSubmission();
        return false;
    });
});
    
    $('#editDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#edit_department_id').val();
        const formData = {
            department_name: $('#edit_department_name').val(),
            description: $('#edit_description').val(),
            head_id: $('#edit_dept_head_id').val() || $('#edit_dept_head_select').val(),
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        };
        
        $.ajax({
            url: '<?= base_url('admin/department/update') ?>/' + id,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Department updated successfully');
                    $('#editDepartmentModal').modal('hide');
                    location.reload();
                } else {
                    let errorMsg = 'Failed to update department';
                    if (response.errors) {
                        errorMsg += ': ' + Object.values(response.errors).join(', ');
                    }
                    alert(errorMsg);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating department:', error);
                alert('Failed to update department');
            }
        });
    });
    
    // Modal cleanup
    $('#addDepartmentModal').on('hidden.bs.modal', function() {
        $('#addDepartmentForm')[0].reset();
        $('#dept_head_id').val('');
        $('#teacher-search-results').hide();
        $('#teacher-search-input').val('');
    });
    
    $('#editDepartmentModal').on('hidden.bs.modal', function() {
        $('#edit-teacher-search-results').hide();
        $('#edit-teacher-search-input').val('');
    });
    
    // Click outside to close search results
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.input-group, .search-results').length) {
            $('#teacher-search-results, #edit-teacher-search-results').hide();
        }
    });
    
    // Handle teacher selection from search results
    function handleSearchSelection(teacherId, teacherText, teacherName, modalType) {
        console.log('=== HANDLE SEARCH SELECTION CALLED ===');
        console.log('Parameters:', {
            teacherId: teacherId,
            teacherText: teacherText,
            teacherName: teacherName,
            modalType: modalType
        });
        
        var isAdd = modalType === 'add';
        var selectId = isAdd ? '#dept_head_select' : '#edit_dept_head_select';
        var searchInputId = isAdd ? '#teacher-search-input' : '#edit-teacher-search-input';
        var resultsContainerId = isAdd ? '#teacher-search-results' : '#edit-teacher-search-results';
        
        console.log('Target elements:', {
            selectId: selectId,
            searchInputId: searchInputId,
            resultsContainerId: resultsContainerId
        });
        
        // Check if elements exist
        var selectElement = $(selectId);
        var searchInputElement = $(searchInputId);
        var resultsContainerElement = $(resultsContainerId);
        
        console.log('Element existence check:', {
            selectExists: selectElement.length > 0,
            searchInputExists: searchInputElement.length > 0,
            resultsContainerExists: resultsContainerElement.length > 0
        });
        
        if (selectElement.length > 0) {
            // Update the select dropdown to show the selected teacher
            selectElement.val(teacherId).trigger('change');
            console.log('Select dropdown updated with value:', teacherId);
        } else {
            console.error('Select element not found:', selectId);
        }
        
        if (resultsContainerElement.length > 0) {
            // Hide search results
            resultsContainerElement.hide();
            console.log('Search results hidden');
        } else {
            console.error('Results container not found:', resultsContainerId);
        }
        
        if (searchInputElement.length > 0) {
            // Clear search input
            searchInputElement.val('');
            console.log('Search input cleared');
        } else {
            console.error('Search input not found:', searchInputId);
        }
        
        // Show success message
        console.log('Teacher selection completed - ID:', teacherId, 'Name:', teacherName);
        alert('Teacher selected: ' + teacherName + ' (ID: ' + teacherId + ')');
    }
    
    // Setup click handlers for search results - using event delegation for dynamic content
    $(document).on('click', '.teacher-search-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('=== TEACHER ITEM CLICKED ===');
        console.log('Clicked element:', this);
        console.log('Element classes:', this.className);
        
        var teacherId = $(this).data('id');
        var teacherText = $(this).data('text');
        var teacherName = $(this).data('name');
        
        console.log('Teacher data:', {
            id: teacherId,
            text: teacherText,
            name: teacherName
        });
        
        if (!teacherId) {
            console.error('No teacher ID found!');
            alert('Error: Could not get teacher ID. Please try again.');
            return;
        }
        
        // Determine which modal this is for based on the container
        var modalType = $(this).closest('#teacher-search-results').length > 0 ? 'add' : 'edit';
        console.log('Modal type:', modalType);
        
        handleSearchSelection(teacherId, teacherText, teacherName, modalType);
    });
    
    // Input event handlers with debounce
    var debounceTimerAdd, debounceTimerEdit;
    
    $('#teacher-search-input').on('input keyup', function() {
        clearTimeout(debounceTimerAdd);
        var term = $(this).val().trim();
        console.log('Input event triggered, term:', term);
        
        if (term.length >= 2) {
            console.log('Triggering search for add modal:', term);
            performTeacherSearch(term, 'add');
        } else if (term.length === 0) {
            // Hide results when input is empty
            $('#teacher-search-results').hide();
        }
    });
    
    $('#edit-teacher-search-input').on('input', function() {
        clearTimeout(debounceTimerEdit);
        var term = $(this).val().trim();
        debounceTimerEdit = setTimeout(function() {
            if (term.length >= 2) {
                console.log('Triggering search for edit modal:', term);
                performTeacherSearch(term, 'edit');
            }
        }, 300);
    });
    
    // Test form existence
    console.log('Form elements check:', {
        addDepartmentForm: $('#addDepartmentForm').length,
        submitButton: $('button[type="submit"]').length,
        departmentName: $('#department_name').length,
        deptHeadSelect: $('#dept_head_select').length
    });
    
    // Search and filter functionality
    function filterTable() {
        var searchTerm = $('#searchInput').val().toLowerCase();
        var statusFilter = $('#statusFilter').val();
        
        $('#departmentsTable tbody tr').each(function() {
            var departmentName = $(this).find('td:eq(1)').text().toLowerCase();
            var description = $(this).find('td:eq(2)').text().toLowerCase();
            var head = $(this).find('td:eq(3)').text().toLowerCase();
            var statusCell = $(this).find('td:eq(4)');
            var hasHead = statusCell.find('.badge-success').length > 0;
            
            var matchesSearch = searchTerm === '' || 
                departmentName.includes(searchTerm) || 
                description.includes(searchTerm) || 
                head.includes(searchTerm);
            
            var matchesStatus = statusFilter === '' || 
                (statusFilter === 'with_head' && hasHead) || 
                (statusFilter === 'without_head' && !hasHead);
            
            if (matchesSearch && matchesStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    $('#searchInput').on('keyup', filterTable);
    $('#statusFilter').on('change', filterTable);
    
    console.log('Teacher search initialized successfully. Test with "Test" or check console.');

</script>

// Export functionality
function handleCopyClick(button) {
    // Simple copy functionality - you can enhance this with DataTables export
    var table = document.getElementById('departmentsTable');
    var range = document.createRange();
    range.selectNode(table);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
    
    // Visual feedback
    var originalText = button.innerHTML;
    button.innerHTML = '<i class="icon-copy bi bi-check"></i> <span>Copied!</span>';
    button.classList.remove('btn-secondary');
    button.classList.add('btn-success');
    
    setTimeout(function() {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-secondary');
    }, 2000);
}
</script>
<form id="deleteDepartmentForm" method="post" action="#" style="display:none;">
    <?= csrf_field() ?>
</form>
