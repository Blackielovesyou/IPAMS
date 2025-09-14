function setActiveLink(activeLinkId, activeLinkIdMobile) {
  $(".sidebar .nav-link, .offcanvas-body .nav-link").removeClass("active");
  $("#" + activeLinkId).addClass("active");
  $("#" + activeLinkIdMobile).addClass("active");
}

function showSystemLogs() {
  document.getElementById("dashboardSection").style.display = "none";
  document.getElementById("systemLogsSection").style.display = "block";
  document.getElementById("systemSettingsSection").style.display = "none";
  setActiveLink("systemLogsLink", "systemLogsLinkMobile");

  // ✅ Remember last active section
  localStorage.setItem("activeSection", "logs");
}

function showDashboard() {
  document.getElementById("dashboardSection").style.display = "block";
  document.getElementById("systemLogsSection").style.display = "none";
  document.getElementById("systemSettingsSection").style.display = "none";
  setActiveLink("applicationLink", "applicationLinkMobile");

  // ✅ Remember last active section
  localStorage.setItem("activeSection", "dashboard");
}

function showSystemSettings() {
  document.getElementById("dashboardSection").style.display = "none";
  document.getElementById("systemLogsSection").style.display = "none";
  document.getElementById("systemSettingsSection").style.display = "block";
  setActiveLink("systemSettingsLink", "systemSettingsLinkMobile");

  // ✅ Remember last active section
  localStorage.setItem("activeSection", "settings");
}

function showSystemInfo() {
  document.getElementById("systemInfoForm").style.display = "block";
  document.getElementById("btnSystemInfo").classList.add("btn-primary");
  document.getElementById("btnSystemInfo").classList.remove("btn-outline-primary");
  document.getElementById("btnChangePass").classList.add("btn-outline-primary");
  document.getElementById("btnChangePass").classList.remove("btn-primary");

  // ✅ Remember last active sub-form
  localStorage.setItem("activeSubForm", "systemInfo");
}

function showChangePass() {
  document.getElementById("changePassForm").style.display = "block";
  document.getElementById("btnChangePass").classList.add("btn-primary");
  document.getElementById("btnChangePass").classList.remove("btn-outline-primary");
  document.getElementById("btnSystemInfo").classList.add("btn-outline-primary");
  document.getElementById("btnSystemInfo").classList.remove("btn-primary");

  // ✅ Remember last active sub-form
  localStorage.setItem("activeSubForm", "changePass");
}

function toggleForm(formId) {
  const form = document.getElementById(formId);
  form.style.display =
    form.style.display === "none" || form.style.display === ""
      ? "block"
      : "none";
}

// ✅ Restore last opened section & sub-form when page loads
document.addEventListener("DOMContentLoaded", function () {
  let activeSection = localStorage.getItem("activeSection") || "dashboard";
  let activeSubForm = localStorage.getItem("activeSubForm") || "systemInfo";

  if (activeSection === "settings") {
    showSystemSettings();
    if (activeSubForm === "changePass") {
      showChangePass();
    } else {
      showSystemInfo();
    }
  } else if (activeSection === "logs") {
    showSystemLogs();
  } else {
    showDashboard();
  }

  // ✅ Handle last restricted page attempt
  let lastPage = localStorage.getItem("lastPage");
  if (lastPage) {
    console.log("Last attempted restricted page:", lastPage);
    // Example: show alert instead of redirect
    alert("You tried to access a restricted page: " + lastPage);
    localStorage.removeItem("lastPage");
  }
});
