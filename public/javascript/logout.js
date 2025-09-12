document.getElementById("logoutBtn").addEventListener("click", function () {
  Swal.fire({
    title: "Are you sure you want to log out?",
    text: "You will be logged out of your session.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, log out",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "logout.php"; // redirect to logout script
    }
  });
});
