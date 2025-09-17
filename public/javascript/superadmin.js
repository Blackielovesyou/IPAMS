// -----------------------------
// Utility to set active link
// -----------------------------
function setActiveLink(desktopId, mobileId) {
  $(".sidebar .nav-link, .offcanvas-body .nav-link").removeClass("active");
  if (desktopId) $("#" + desktopId).addClass("active");
  if (mobileId) $("#" + mobileId).addClass("active");
}

// -----------------------------
// Section toggles
// -----------------------------
function showDashboard() {
  $("#dashboardSection").show();
  $("#userManagementSection, #systemSettingsSection, #systemLogsSection").hide();
  setActiveLink("applicationLink", "applicationLinkMobile");
  localStorage.setItem("activeSection", "dashboard");
}

function showUserManagement() {
  $("#userManagementSection").show();
  $("#dashboardSection, #systemSettingsSection, #systemLogsSection").hide();
  setActiveLink("userManagementLink", "userManagementLinkMobile");
  localStorage.setItem("activeSection", "userManagement");
}

function showSystemSettings() {
  $("#systemSettingsSection").show();
  $("#dashboardSection, #userManagementSection, #systemLogsSection").hide();
  setActiveLink("systemSettingsLink", "systemSettingsLinkMobile");
  localStorage.setItem("activeSection", "settings");
}

function showSystemLogs() {
  $("#systemLogsSection").show();
  $("#dashboardSection, #userManagementSection, #systemSettingsSection").hide();
  setActiveLink("systemLogsLink", "systemLogsLinkMobile");
  localStorage.setItem("activeSection", "logs");
}

// -----------------------------
// Sub-forms inside System Settings
// -----------------------------
function showSystemInfo() {
  $("#systemInfoForm").show();
  $("#changePassForm").hide();
  $("#btnSystemInfo").addClass("btn-primary").removeClass("btn-outline-primary");
  $("#btnChangePass").addClass("btn-outline-primary").removeClass("btn-primary");
  localStorage.setItem("activeSubForm", "systemInfo");
}

function showChangePass() {
  $("#changePassForm").show();
  $("#systemInfoForm").hide();
  $("#btnChangePass").addClass("btn-primary").removeClass("btn-outline-primary");
  $("#btnSystemInfo").addClass("btn-outline-primary").removeClass("btn-primary");
  localStorage.setItem("activeSubForm", "changePass");
}

// -----------------------------
// Toggle any generic form
// -----------------------------
function toggleForm(formId) {
  const el = document.getElementById(formId);
  el.style.display = (el.style.display === "none" || el.style.display === "") ? "block" : "none";
}

// -----------------------------
// Restore last opened section & sub-form
// -----------------------------
document.addEventListener("DOMContentLoaded", function () {
  const activeSection = localStorage.getItem("activeSection") || "dashboard";
  const activeSubForm = localStorage.getItem("activeSubForm") || "systemInfo";

  switch (activeSection) {
    case "userManagement":
      showUserManagement();
      break;
    case "settings":
      showSystemSettings();
      if (activeSubForm === "changePass") showChangePass();
      else showSystemInfo();
      break;
    case "logs":
      showSystemLogs();
      break;
    default:
      showDashboard();
      break;
  }

  // Handle last restricted page attempt
  const lastPage = localStorage.getItem("lastPage");
  if (lastPage) {
    alert("You tried to access a restricted page: " + lastPage);
    localStorage.removeItem("lastPage");
  }
});
