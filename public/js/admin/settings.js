
$(document).ready(function () {
    $(".summernote_input").summernote({
        height: 300,
        placeholder: "Enter anything here..",
        background: "white"
    });
    $('#logoinput').change(function(event) {
        const [file] = event.target.files;
        if (file) {
            $('#logopreview').attr('src', URL.createObjectURL(file));
        }
    });

    $('#bannerinput').change(function(event) {
        const [file] = event.target.files;
        if (file) {
            $('#bannerpreview').attr('src', URL.createObjectURL(file));
        }
    });
    
    $('#pdl_img').change(function(event) {
        const [file] = event.target.files;
        if (file) {
            $('#preview_profile_img').attr('src', URL.createObjectURL(file));
        }
    });
});
$(document).on('submit', '.settingsform', function (e) { 
    e.preventDefault();
    let formData = new FormData(this);
    
    // Show loading indicator
    showLoading();
    
    $.ajax({
        type: "POST",
        url: "/admin/update_app_info",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {  
            hideLoading();
            global_showalert(response.message, 'Update Success', 'green');
        },
        error: function (xhr) {
            hideLoading();
            let response = JSON.parse(xhr.responseText);
            let errorMessage = 'An error occurred';
            if (response.errors) {
                errorMessage = '';
                for (let errorKey in response.errors) {
                    errorMessage += response.errors[errorKey][0] + '\n';
                }
            }
            global_showalert(errorMessage, 'Alert!', 'red');
        }
    });
});