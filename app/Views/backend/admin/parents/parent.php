<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
	<div>
		<div class="title">
			<h4>All Parents</h4>
		</div>
		<nav aria-label="breadcrumb" role="navigation">
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item">
					<a href="<?= route_to('admin.home')?>">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					Parents
				</li>
			</ol>
		</nav>
	</div>
	<div>
		<a href="<?= route_to('admin.parent.create') ?>" class="btn btn-success btn-sm">
			<i class="icon-copy bi bi-plus-lg"></i> Add Parent
		</a>
	</div>
</div>

<div class="card-box mb-30"> 
    <div class="pd-20"> 
        <h4 class="text-blue h4">Data Table Simple</h4> 
    </div>

<!-- Parent View Modal -->
<div class="modal fade bs-example-modal-lg" id="parentViewModal" tabindex="-1" role="dialog" aria-labelledby="parentViewModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="parentViewModalLabel">
                    Parent Information
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">
                            <div class="profile-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>First Name:</strong></label>
                                            <p id="modal-first-name">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Middle Name:</strong></label>
                                            <p id="modal-middle-name">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Last Name:</strong></label>
                                            <p id="modal-last-name">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Relationship Type:</strong></label>
                                            <p id="modal-relationship-type">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Contact Number:</strong></label>
                                            <p id="modal-contact-number">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Student:</strong></label>
                                            <p id="modal-student-name">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Parent Type:</strong></label>
                                            <p id="modal-parent-type">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Same Address as Student:</strong></label>
                                            <p id="modal-same-address">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><strong>Address Information:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>House Number:</strong></label>
                                            <p id="modal-house-number">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Street:</strong></label>
                                            <p id="modal-street">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Barangay:</strong></label>
                                            <p id="modal-barangay">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Municipality:</strong></label>
                                            <p id="modal-municipality">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Province:</strong></label>
                                            <p id="modal-province">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>ZIP Code:</strong></label>
                                            <p id="modal-zip-code">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Created At:</strong></label>
                                            <p id="modal-created-at">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Updated At:</strong></label>
                                            <p id="modal-updated-at">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <a href="#" id="modal-edit-link" class="btn btn-primary">
                    Edit Parent
                </a>
            </div>
        </div>
    </div>
</div>
    
    <!-- Filter, Search, and Export Buttons Section -->
    <div class="pd-20 pt-0">
        <div class="row align-items-end"> 
            <div class="col-md-3"> 
                <div class="form-group"> 
                    <label>Search Parents:</label>
                    <div class="position-relative"> 
                        <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by name..." onkeyup="filterTable()"> 
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;"> 
                            <i class="icon-copy bi bi-search"></i> 
                        </span> 
                    </div>
                </div> 
            </div> 
            <div class="col-md-3"> 
                <div class="form-group"> 
                    <label>Filter Category:</label>
                    <select class="form-control" id="categoryFilter" onchange="filterTable()"> 
                        <option value="">All Parents</option> 
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
         <div id="DataTables_Table_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer"> 
             <div id="DataTables_Table_2_filter" class="dataTables_filter"> 
                 <label>Search: 
                     <input type="search" class="form-control form-control-sm" placeholder="Search" aria-controls="DataTables_Table_2" onkeyup="filterTable()" id="dtSearchInput"> 
                 </label> 
             </div> 
             <div class="table-responsive"> 
                 <table class="table hover multiple-select-row data-table-export nowrap dataTable no-footer dtr-inline collapsed" id="DataTables_Table_2" role="grid"> 
                     <thead> 
                         <tr> 
                             <th class="table-plus"> 
                                 <input type="checkbox" id="selectAll" onchange="toggleSelectAll()"> 
                             </th> 
                             <th>Name</th> 
                             <th>Contact</th> 
                             <th>Created At</th> 
                             <th>Actions</th> 
                         </tr> 
                     </thead> 
                     <tbody> 
                         <?php if(isset($parents) && !empty($parents)): ?>
                             <?php foreach($parents as $parent): ?>
                                 <tr> 
                                     <td><input type="checkbox" class="parent-checkbox" value="<?= $parent['id'] ?>"></td> 
                                     <td class="table-plus"> 
                                         <strong><?= $parent['full_name'] ?? ($parent['first_name'] . ' ' . $parent['last_name']) ?></strong>
                                         <br><small class="text-muted"><?= ucfirst($parent['relationship_type']) ?> of <?= $parent['student_name'] ?? 'Student' ?></small>
                                     </td> 
                                     <td><?= $parent['contact_number'] ?? 'Not provided' ?></td> 
                                     <td><?= date('M d, Y', strtotime($parent['created_at'])) ?></td> 
                                     <td> 
                                         <div class="dropdown"> 
                                             <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"> 
                                                 <i class="dw dw-more"></i> 
                                             </a> 
                                             <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"> 
                                                 <a class="dropdown-item view-parent" href="#" data-id="<?= $parent['id'] ?>" data-toggle="modal" data-target="#parentViewModal"><i class="dw dw-eye"></i> View</a> 
                                                 <a class="dropdown-item" href="<?= route_to('admin.parent.edit', $parent['id']) ?>"><i class="dw dw-edit2"></i> Edit</a> 
                                                 <a class="dropdown-item delete-parent" href="#" data-id="<?= $parent['id'] ?>"><i class="dw dw-delete-3"></i> Delete</a> 
                                             </div> 
                                         </div> 
                                     </td>
                                 </tr>
                             <?php endforeach; ?>
                         <?php else: ?>
                             <tr>
                                 <td colspan="5" class="text-center">No parents found</td>
                             </tr>
                         <?php endif; ?>
                     </tbody>
                 </table>
             </div>
             <div class="row">
                 <div class="col-sm-12 col-md-12">
                     <div class="dataTables_paginate paging_simple_numbers d-flex justify-content-start ms-2 mt-3" id="DataTables_Table_2_paginate">
                         <ul class="pagination">
                             <li class="paginate_button page-item previous disabled" id="DataTables_Table_2_previous">
                                 <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="0" tabindex="0" class="page-link"><i class="ion-chevron-left"></i></a>
                             </li>
                             <li class="paginate_button page-item active">
                                 <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                             </li>
                             <li class="paginate_button page-item ">
                                 <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                             </li>
                             <li class="paginate_button page-item next" id="DataTables_Table_2_next">
                                 <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="3" tabindex="0" class="page-link"><i class="ion-chevron-right"></i></a>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

<script>
function filterTable() {
    // Get filter values
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const dtSearchInput = document.getElementById('dtSearchInput').value.toLowerCase();
    
    // Combine search inputs
    const searchTerm = searchInput || dtSearchInput;
    
    // Get all table rows
    const rows = document.querySelectorAll('#DataTables_Table_2 tbody tr');
    
    // Loop through rows and apply filters
    rows.forEach(row => {
        const name = row.querySelector('td.table-plus strong')?.textContent.toLowerCase() || '';
        
        // Check if row matches search filter
        const matchesSearch = !searchTerm || name.includes(searchTerm);
        
        // Show/hide row based on filter results
        row.style.display = matchesSearch ? '' : 'none';
    });
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.parent-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function handleCopyClick(button) {
    // Get table data
    const table = document.getElementById('DataTables_Table_2');
    const rows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
    
    // Create text to copy
    let copyText = '';
    
    // Add headers
    const headers = table.querySelectorAll('thead th');
    const headerTexts = [];
    headers.forEach((header, index) => {
        if (index > 0 && index < headers.length - 1) { // Skip checkbox and actions columns
            headerTexts.push(header.textContent.trim());
        }
    });
    copyText += headerTexts.join('\t') + '\n';
    
    // Add row data
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const rowData = [];
        
        cells.forEach((cell, index) => {
            if (index > 0 && index < cells.length - 1) { // Skip checkbox and actions columns
                // Get text content, handling nested elements
                let cellText = '';
                
                if (index === 1) { // Name column (photo column removed)
                    cellText = cell.querySelector('strong')?.textContent.trim() || '';
                } else {
                    cellText = cell.textContent.trim();
                }
                
                rowData.push(cellText);
            }
        });
        
        copyText += rowData.join('\t') + '\n';
    });
    
    // Copy to clipboard
    navigator.clipboard.writeText(copyText).then(() => {
        // Show success message
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="icon-copy bi bi-check"></i> <span>Copied!</span>';
        
        // Reset button after 2 seconds
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}

// Add event listeners for delete buttons
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to delete buttons
    const deleteButtons = document.querySelectorAll('.delete-parent');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const parentId = this.getAttribute('data-id');
            
            if (confirm('Are you sure you want to delete this parent?')) {
                // Create a form to submit the delete request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= site_url('admin/parent/delete/') ?>' + parentId;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Add event listeners to view buttons for modal
    const viewButtons = document.querySelectorAll('.view-parent');
    viewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const parentId = this.getAttribute('data-id');
            
            // Show loading state
            document.getElementById('modal-first-name').textContent = 'Loading...';
            document.getElementById('modal-middle-name').textContent = 'Loading...';
            document.getElementById('modal-last-name').textContent = 'Loading...';
            document.getElementById('modal-relationship-type').textContent = 'Loading...';
            document.getElementById('modal-contact-number').textContent = 'Loading...';
            document.getElementById('modal-student-name').textContent = 'Loading...';
            document.getElementById('modal-parent-type').textContent = 'Loading...';
            document.getElementById('modal-same-address').textContent = 'Loading...';
            document.getElementById('modal-house-number').textContent = 'Loading...';
            document.getElementById('modal-street').textContent = 'Loading...';
            document.getElementById('modal-barangay').textContent = 'Loading...';
            document.getElementById('modal-municipality').textContent = 'Loading...';
            document.getElementById('modal-province').textContent = 'Loading...';
            document.getElementById('modal-zip-code').textContent = 'Loading...';
            document.getElementById('modal-created-at').textContent = 'Loading...';
            document.getElementById('modal-updated-at').textContent = 'Loading...';
            
            // Fetch parent data from database via AJAX
            fetch('<?= site_url('admin/parent/data/') ?>' + parentId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const parent = data.data;
                        
                        // Populate modal fields with database data
                        document.getElementById('modal-first-name').textContent = parent.first_name || '-';
                        document.getElementById('modal-middle-name').textContent = parent.middle_name || '-';
                        document.getElementById('modal-last-name').textContent = parent.last_name || '-';
                        document.getElementById('modal-relationship-type').textContent = parent.relationship_type || '-';
                        document.getElementById('modal-contact-number').textContent = parent.contact_number || '-';
                        document.getElementById('modal-student-name').textContent = parent.student_name || '-';
                        document.getElementById('modal-parent-type').textContent = parent.parent_type || '-';
                        document.getElementById('modal-same-address').textContent = parent.is_same_as_student || '-';
                        document.getElementById('modal-house-number').textContent = parent.house_number || '-';
                        document.getElementById('modal-street').textContent = parent.street || '-';
                        document.getElementById('modal-barangay').textContent = parent.barangay || '-';
                        document.getElementById('modal-municipality').textContent = parent.municipality || '-';
                        document.getElementById('modal-province').textContent = parent.province || '-';
                        document.getElementById('modal-zip-code').textContent = parent.zip_code || '-';
                        document.getElementById('modal-created-at').textContent = parent.created_at || '-';
                        document.getElementById('modal-updated-at').textContent = parent.updated_at || '-';
                        
                        // Set edit link
                        const editLink = document.getElementById('modal-edit-link');
                        editLink.href = '<?= site_url('admin/parent/edit/') ?>' + parentId;
                    } else {
                        // Handle error
                        document.getElementById('modal-first-name').textContent = 'Error loading data';
                        document.getElementById('modal-middle-name').textContent = '-';
                        document.getElementById('modal-last-name').textContent = '-';
                        document.getElementById('modal-relationship-type').textContent = '-';
                        document.getElementById('modal-contact-number').textContent = '-';
                        document.getElementById('modal-student-name').textContent = '-';
                        document.getElementById('modal-parent-type').textContent = '-';
                        document.getElementById('modal-same-address').textContent = '-';
                        document.getElementById('modal-house-number').textContent = '-';
                        document.getElementById('modal-street').textContent = '-';
                        document.getElementById('modal-barangay').textContent = '-';
                        document.getElementById('modal-municipality').textContent = '-';
                        document.getElementById('modal-province').textContent = '-';
                        document.getElementById('modal-zip-code').textContent = '-';
                        document.getElementById('modal-created-at').textContent = '-';
                        document.getElementById('modal-updated-at').textContent = '-';
                        console.error('Error fetching parent data:', data.message);
                    }
                })
                .catch(error => {
                    // Handle network error
                    document.getElementById('modal-first-name').textContent = 'Network error';
                    document.getElementById('modal-middle-name').textContent = '-';
                    document.getElementById('modal-last-name').textContent = '-';
                    document.getElementById('modal-relationship-type').textContent = '-';
                    document.getElementById('modal-contact-number').textContent = '-';
                    document.getElementById('modal-student-name').textContent = '-';
                    document.getElementById('modal-parent-type').textContent = '-';
                    document.getElementById('modal-same-address').textContent = '-';
                    document.getElementById('modal-house-number').textContent = '-';
                    document.getElementById('modal-street').textContent = '-';
                    document.getElementById('modal-barangay').textContent = '-';
                    document.getElementById('modal-municipality').textContent = '-';
                    document.getElementById('modal-province').textContent = '-';
                    document.getElementById('modal-zip-code').textContent = '-';
                    document.getElementById('modal-created-at').textContent = '-';
                    document.getElementById('modal-updated-at').textContent = '-';
                    console.error('Network error:', error);
                });
        });
    });
});
</script>

<?= $this->endSection() ?>