<?= $this->extend('backend/student/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Class Materials</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('student/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">My Classes</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Class Materials
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-30">
        <div class="card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div style="background-color: #0078D7; color: white; padding: 15px 20px; position: relative;">
                <button class="btn btn-link" style="position: absolute; right: 5px; top: 5px; color: white;">
                    <i class="icon-copy dw dw-more"></i>
                </button>
                <h5 class="mb-0">Mathematics</h5>
                <p class="mb-0">10 Rose</p>
                <p class="mb-0"></p>
            </div>
            <div style="height: 150px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                <img src="/backend/vendors/images/math-icon.svg" alt="Mathematics" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: white; border-top: none; padding: 10px 20px;">
                <a href="#" class="text-dark"><i class="icon-copy dw dw-image"></i></a>
                <a href="#" class="text-dark"><i class="icon-copy dw dw-folder"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-30">
        <div class="card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div style="background-color: #2D7D9A; color: white; padding: 15px 20px; position: relative;">
                <button class="btn btn-link" style="position: absolute; right: 5px; top: 5px; color: white;">
                    <i class="icon-copy dw dw-more"></i>
                </button>
                <h5 class="mb-0">Science</h5>
                <p class="mb-0">10 Rose</p>
                <p class="mb-0"></p>
            </div>
            <div style="height: 150px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                <img src="/backend/vendors/images/science-icon.svg" alt="Science" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: white; border-top: none; padding: 10px 20px;">
                <a href="#" class="text-dark"><i class="icon-copy dw dw-image"></i></a>
                <a href="#" class="text-dark"><i class="icon-copy dw dw-folder"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-30">
        <div class="card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div style="background-color: #5D4037; color: white; padding: 15px 20px; position: relative;">
                <button class="btn btn-link" style="position: absolute; right: 5px; top: 5px; color: white;">
                    <i class="icon-copy dw dw-more"></i>
                </button>
                <h5 class="mb-0">English</h5>
                <p class="mb-0">10 Rose</p>
                <p class="mb-0"></p>
            </div>
            <div style="height: 150px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                <img src="/backend/vendors/images/english-icon.svg" alt="English" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: white; border-top: none; padding: 10px 20px;">
                <a href="#" class="text-dark"><i class="icon-copy dw dw-image"></i></a>
                <a href="#" class="text-dark"><i class="icon-copy dw dw-folder"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-30">
        <div class="card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div style="background-color: #FF7043; color: white; padding: 15px 20px; position: relative;">
                <button class="btn btn-link" style="position: absolute; right: 5px; top: 5px; color: white;">
                    <i class="icon-copy dw dw-more"></i>
                </button>
                <h5 class="mb-0">Filipino</h5>
                <p class="mb-0">10 Rose</p>
                <p class="mb-0"></p>
            </div>
            <div style="height: 150px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                <img src="/backend/vendors/images/filipino-icon.svg" alt="Filipino" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: white; border-top: none; padding: 10px 20px;">
                <a href="#" class="text-dark"><i class="icon-copy dw dw-image"></i></a>
                <a href="#" class="text-dark"><i class="icon-copy dw dw-folder"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-30">
        <div class="card subject-card esp-card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div style="background-color: #1565C0; color: white; padding: 15px 20px; position: relative;">
                <button class="btn btn-link" style="position: absolute; right: 5px; top: 5px; color: white;">
                    <i class="icon-copy dw dw-more"></i>
                </button>
                <h5 class="mb-0">E.S.P</h5>
                <p class="mb-0">10 Rose</p>
                <p class="mb-0"></p>
            </div>
            <div style="height: 150px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                <img src="/backend/vendors/images/itec-icon.svg" alt="ITEC" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: white; border-top: none; padding: 10px 20px;">
                <a href="#" class="text-dark"><i class="icon-copy dw dw-image"></i></a>
                <a href="#" class="text-dark"><i class="icon-copy dw dw-folder"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-30">
        <div class="card subject-card long-title-card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div style="background-color: #455A64; color: white; padding: 15px 20px; position: relative;">
                <button class="btn btn-link" style="position: absolute; right: 5px; top: 5px; color: white;">
                    <i class="icon-copy dw dw-more"></i>
                </button>
                <h5 class="mb-0">Araling Panlipunan</h5>
                <p class="mb-0">10 Rose</p>
                <p class="mb-0"></p>
            </div>
            <div style="height: 150px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                <img src="/backend/vendors/images/math-icon.svg" alt="Quantitative Methods" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: white; border-top: none; padding: 10px 20px;">
                <a href="#" class="text-dark"><i class="icon-copy dw dw-image"></i></a>
                <a href="#" class="text-dark"><i class="icon-copy dw dw-folder"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Base card styles */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    /* Responsive adjustments for card content */
    @media (max-width: 767px) {
        .card h5.mb-0 {
            font-size: 1rem;
        }
        .card p.mb-0 {
            font-size: 0.85rem;
        }
        .card-footer a {
            padding: 5px;
        }
        /* Adjust header padding on medium screens */
        .card div[style*="padding: 15px 20px"] {
            padding: 12px 15px !important;
        }
    }
    
    /* Ensure images scale properly on small screens */
    .card img {
        max-width: 100%;
        height: auto;
        max-height: 80px;
    }
    
    /* Adjust card height on smaller screens */
    @media (max-width: 576px) {
        .card div[style*="height: 150px"] {
            height: 120px !important;
        }
        /* Further reduce header padding on small screens */
        .card div[style*="padding: 15px 20px"] {
            padding: 10px 12px !important;
        }
        /* Adjust more button position */
        .card .btn.btn-link {
            right: 2px;
            top: 2px;
        }
        /* Ensure long subject names don't overflow */
        .card h5.mb-0 {
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 85%;
        }
    }
    
    /* Make cards take full width on extra small screens */
    @media (max-width: 400px) {
        .col-12 {
            padding-left: 10px;
            padding-right: 10px;
        }
        /* Further adjustments for very small screens */
        .card-footer {
            padding: 8px 15px !important;
        }
        .card div[style*="height: 150px"] {
            height: 100px !important;
        }
    }
    
    /* Special handling for long subject names */
    @media (max-width: 767px) {
        /* Add specific class for long subject names */
        .card h5.mb-0 {
            max-width: 85%;
        }
        /* Reduce font size for all subject names on smaller screens */
        .card h5.mb-0 {
            font-size: 0.85rem;
            line-height: 1.2;
        }
    }
    
    /* Fix for hover effect on touch devices */
    @media (hover: none) {
        .card:hover {
            transform: none !important;
        }
    }
</style>

<script>
    // Add hover effects to cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
            });
        });
    });
</script>

<?= $this->endSection() ?>