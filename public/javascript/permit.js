
        function goBack() {
            window.location.href = 'main_page.php';
        }

        function handleFileUpload(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);

            if (file) {
                // Check file size (10MB = 10 * 1024 * 1024 bytes)
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size must be less than 10MB');
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                // Check file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Only PDF, JPG, and PNG files are allowed');
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                preview.style.display = 'block';
                preview.innerHTML = `
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <span class="fw-medium">${file.name}</span>
                    <small class="text-muted ms-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                `;
            } else {
                preview.style.display = 'none';
            }
        }

        // Form submission
        document.getElementById('buildingPermitForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="loading-spinner me-2"></span>
                Submitting Application...
            `;

            // Simulate API call
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                // Show success modal
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            }, 2000);
        });

        // Drag and drop functionality
        document.querySelectorAll('.upload-area').forEach(area => {
            area.addEventListener('dragover', function (e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            area.addEventListener('dragleave', function (e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            area.addEventListener('drop', function (e) {
                e.preventDefault();
                this.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const input = this.querySelector('input[type="file"]');
                    input.files = files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
  