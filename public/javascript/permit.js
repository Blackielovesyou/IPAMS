function goBack() {
    window.location.href = 'main_page.php';
}

function handleFileUpload(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);

    if (file) {
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            input.value = '';
            preview.style.display = 'none';
            return;
        }

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

// --- Generic AJAX form submission handler ---
function handlePermitFormSubmission(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // prevent normal form submission

        const submitBtn = form.querySelector('#submitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="bi bi-arrow-repeat spin me-2"></i>Submitting...`;
        }

        const formData = new FormData(form);

        fetch('permit_submit.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<i class="bi bi-send me-2"></i>Submit Application`;
                }

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted!',
                        text: `Permit application (#${data.application_number}) submitted successfully!`,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        form.reset();
                        // reset file previews
                        form.querySelectorAll('.file-preview').forEach(preview => preview.style.display = 'none');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Something went wrong. Please try again.'
                    });
                }
            })
            .catch(err => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<i class="bi bi-send me-2"></i>Submit Application`;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to submit the application. Please try again.'
                });
                console.error(err);
            });
    });
}

// --- Initialize all permit forms ---
['buildingPermitForm', 'electricalPermitForm', 'occupancyPermitForm', 'plumbingPermitForm']
    .forEach(handlePermitFormSubmission);
