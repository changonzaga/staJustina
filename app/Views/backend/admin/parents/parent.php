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
                <div class="container-fluid px-4 mt-3 mb-2"> 
    <div class="row align-items-end"> 
        <div class="col-md-3"> 
            <div class="form-group mb-2 position-relative"> 
                <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by name or email..." onkeyup="filterTable()"> 
                <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;"> 
                    <i class="icon-copy bi bi-search"></i> 
                </span> 
            </div> 
        </div> 
        <div class="col-md-3"> 
            <div class="form-group mb-2"> 
                <select class="form-control" id="categoryFilter" onchange="filterTable()"> 
                    <option value="">All Parents</option> 
                </select> 
            </div> 
        </div> 
        <div class="col-md-6 d-flex justify-content-end"> 
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
 <div class="card-box mb-30"> 
     <div class="pd-20"> 
         <h4 class="text-blue h4">Data Table Simple</h4> 
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
                             <th>Photo</th> 
                             <th>Name</th> 
                             <th>Email</th> 
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
                                     <td> 
                                         <?php if(!empty($parent['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture'])): ?>
                                             <img src="<?= base_url('uploads/parents/' . $parent['profile_picture']) ?>" class="avatar-photo" style="width: 40px; height: 40px; border-radius: 50%;" alt=""> 
                                         <?php else: ?>
                                             <img src="<?= base_url('backend/vendors/images/user.png') ?>" class="avatar-photo" style="width: 40px; height: 40px; border-radius: 50%;" alt=""> 
                                         <?php endif; ?>
                                     </td> 
                                     <td class="table-plus"> 
                                         <strong><?= $parent['name'] ?></strong> 
                                     </td> 
                                     <td><?= $parent['email'] ?></td> 
                                     <td><?= $parent['contact'] ?></td> 
                                     <td><?= date('M d, Y', strtotime($parent['created_at'])) ?></td> 
                                     <td> 
                                         <div class="dropdown"> 
                                             <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"> 
                                                 <i class="dw dw-more"></i> 
                                             </a> 
                                             <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"> 
                                                 <a class="dropdown-item" href="<?= route_to('admin.parent.view', $parent['id']) ?>"><i class="dw dw-eye"></i> View</a> 
                                                 <a class="dropdown-item" href="<?= route_to('admin.parent.edit', $parent['id']) ?>"><i class="dw dw-edit2"></i> Edit</a> 
                                                 <a class="dropdown-item delete-parent" href="#" data-id="<?= $parent['id'] ?>"><i class="dw dw-delete-3"></i> Delete</a> 
                                             </div> 
                                         </div> 
                                     </td>
                                 </tr>
                             <?php endforeach; ?>
                         <?php else: ?>
                             <tr>
                                 <td colspan="7" class="text-center">No parents found</td>
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
        const email = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
        
        // Check if row matches search filter
        const matchesSearch = !searchTerm || name.includes(searchTerm) || email.includes(searchTerm);
        
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
                
                if (index === 1) { // Photo column - skip
                    cellText = 'Photo';
                } else if (index === 2) { // Name column
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
});
</script>

<?= $this->endSection() ?>