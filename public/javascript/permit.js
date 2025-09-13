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
