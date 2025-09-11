document.addEventListener("DOMContentLoaded", () => {
  // File 1: Toggle between Login & Register

  const loginCard = document.getElementById("loginCard");
  const registerCard = document.getElementById("registerCard");
  const showRegister = document.getElementById("showRegister");
  const showLogin = document.getElementById("showLogin");

  if (showRegister && showLogin) {
    showRegister.addEventListener("click", (e) => {
      e.preventDefault();
      loginCard?.classList.add("d-none");
      registerCard?.classList.remove("d-none");
    });

    showLogin.addEventListener("click", (e) => {
      e.preventDefault();
      registerCard?.classList.add("d-none");
      loginCard?.classList.remove("d-none");
    });
  }

  // // File 2: Login Form Submit
  // const loginForm = document.getElementById("loginForm");
  // if (loginForm) {
  //   loginForm.addEventListener("submit", (e) => {
  //     e.preventDefault(); // prevent refresh
  //     window.location.href = "main_page.php"; // redirect
  //   });
  // }

  // File 3: Toggle Password Visibility

  const togglePassword = document.querySelector("#togglePassword");
  const passwordInput = document.querySelector("#password");
  const toggleIcon = document.querySelector("#toggleIcon");

  if (togglePassword && passwordInput && toggleIcon) {
    togglePassword.addEventListener("click", () => {
      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);

      // Change icon
      toggleIcon.classList.toggle("bi-eye");
      toggleIcon.classList.toggle("bi-eye-slash");
    });
  }
});
