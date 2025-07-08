<!-- Unified Form Styles Template -->
<style>
.form-container {
    max-width: 600px;
    margin: 0 auto;
}

.form-section {
    margin-bottom: var(--spacing-xl);
}

.form-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-sm);
    border-bottom: 2px solid var(--border-light);
}

.form-group {
    margin-bottom: var(--spacing-lg);
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: var(--spacing-sm);
    font-size: 0.95rem;
}

.form-label.required::after {
    content: " *";
    color: var(--danger);
}

.form-control {
    width: 100%;
    padding: var(--spacing-md);
    border: 2px solid var(--border-light);
    border-radius: var(--radius-md);
    font-size: 0.95rem;
    transition: all var(--transition-fast);
    background: var(--bg-main);
    color: var(--text-primary);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

.form-control:disabled {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    cursor: not-allowed;
}

.form-control.is-invalid {
    border-color: var(--danger);
    box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
}

.form-control.is-valid {
    border-color: var(--success);
    box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
}

.form-text {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-top: var(--spacing-sm);
}

.form-text.text-danger {
    color: var(--danger);
}

.form-text.text-success {
    color: var(--success);
}

.form-select {
    width: 100%;
    padding: var(--spacing-md);
    border: 2px solid var(--border-light);
    border-radius: var(--radius-md);
    font-size: 0.95rem;
    transition: all var(--transition-fast);
    background: var(--bg-main);
    color: var(--text-primary);
    cursor: pointer;
}

.form-select:focus {
    outline: none;
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

.form-check {
    display: flex;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.form-check-input {
    width: 18px;
    height: 18px;
    margin-right: var(--spacing-sm);
    cursor: pointer;
}

.form-check-label {
    font-size: 0.95rem;
    color: var(--text-primary);
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: flex-end;
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--border-light);
}

.form-actions.center {
    justify-content: center;
}

.form-actions.full-width {
    justify-content: stretch;
}

.form-actions.full-width .btn {
    flex: 1;
}

.input-group {
    display: flex;
    align-items: stretch;
}

.input-group .form-control {
    border-radius: var(--radius-md) 0 0 var(--radius-md);
}

.input-group-text {
    background: var(--bg-tertiary);
    border: 2px solid var(--border-light);
    border-left: none;
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    padding: var(--spacing-md);
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.input-group .btn {
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    border-left: none;
}

.file-upload-area {
    border: 2px dashed var(--border-medium);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    text-align: center;
    background: var(--bg-secondary);
    transition: all var(--transition-normal);
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: var(--primary-blue);
    background: var(--primary-blue-lighter);
}

.file-upload-area.dragover {
    border-color: var(--primary-blue);
    background: var(--primary-blue-lighter);
}

.file-upload-icon {
    font-size: 3rem;
    color: var(--text-secondary);
    margin-bottom: var(--spacing-md);
}

.file-upload-text {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.file-upload-text strong {
    color: var(--primary-blue);
}

.form-row {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.form-row .form-group {
    flex: 1;
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .form-container {
        max-width: 100%;
    }
    
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .form-row .form-group {
        margin-bottom: var(--spacing-lg);
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>

<!-- Form Templates -->

<!-- Basic Form Template -->
<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>Form Title
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-section">
                    <h6 class="form-section-title">Basic Information</h6>
                    
                    <div class="form-group">
                        <label class="form-label required" for="field1">Field Label</label>
                        <input type="text" class="form-control" id="field1" name="field1" required>
                        <div class="form-text">Helper text goes here</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="field2">Field 2</label>
                            <input type="text" class="form-control" id="field2" name="field2">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="field3">Field 3</label>
                            <select class="form-select" id="field3" name="field3">
                                <option value="">-- Pilih --</option>
                                <option value="option1">Option 1</option>
                                <option value="option2">Option 2</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="field4">Textarea Field</label>
                        <textarea class="form-control" id="field4" name="field4" rows="4"></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="agreement" name="agreement" required>
                        <label class="form-check-label" for="agreement">
                            I agree to the terms and conditions
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Search Form Template -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-search me-2"></i>Filter & Pencarian
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="">
            <input type="hidden" name="page" value="<?php echo $_GET['page'] ?? ''; ?>">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="search">Kata Kunci</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                           placeholder="Masukkan kata kunci...">
                </div>
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">-- Semua Status --</option>
                        <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($_GET['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="date_from">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="<?php echo $_GET['date_from'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="date_to">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="<?php echo $_GET['date_to'] ?? ''; ?>">
                </div>
            </div>
            <div class="form-actions center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Cari
                </button>
                <a href="?page=<?php echo $_GET['page'] ?? ''; ?>" class="btn btn-secondary">
                    <i class="fas fa-undo me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- File Upload Form Template -->
<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-upload me-2"></i>Upload File
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label required" for="file">Pilih File</label>
                    <div class="file-upload-area" onclick="document.getElementById('file').click()">
                        <div class="file-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="file-upload-text">
                            <strong>Klik untuk memilih file</strong> atau drag & drop file di sini<br>
                            <small>Format yang didukung: JPG, PNG, PDF (Max: 5MB)</small>
                        </div>
                        <input type="file" class="form-control" id="file" name="file" 
                               accept=".jpg,.jpeg,.png,.pdf" required style="display: none;">
                    </div>
                    <div class="form-text">File akan diupload ke server secara otomatis</div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });
    
    // Real-time validation
    const inputs = document.querySelectorAll('.form-control[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
    
    // File upload drag & drop
    const fileUploadAreas = document.querySelectorAll('.file-upload-area');
    fileUploadAreas.forEach(area => {
        area.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        area.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        
        area.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const fileInput = this.querySelector('input[type="file"]');
                fileInput.files = files;
                
                // Update display
                const fileName = files[0].name;
                const fileText = this.querySelector('.file-upload-text');
                fileText.innerHTML = `<strong>${fileName}</strong> dipilih<br><small>Siap untuk diupload</small>`;
            }
        });
    });
});
</script>
