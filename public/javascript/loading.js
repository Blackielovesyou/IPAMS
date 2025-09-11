document.addEventListener("DOMContentLoaded", function () {
  const registerForm = document.getElementById("registerForm");

  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      // Show loading modal when form is submitted
      var loadingModal = new bootstrap.Modal(document.getElementById("loadingModal"));
      loadingModal.show();
    });
  }
});
