var csrfToken = $('meta[name="csrf-token"]').attr("content");
let loadingIndicator;

let status_arr = ["pending", "approved", "canceled", "declined"];
function showLoading() {
    loadingIndicator = $.alert({
        title: "Loading...",
        content: "Please wait...",
        theme: "modern",
        type: "blue",
        closeIcon: false,
        onOpen: function () {
            $(document).on("keydown.loadingClose", function (e) {
                if (e.key === "Enter") {
                    loadingIndicator.close();
                    $(document).off("keydown.loadingClose"); // remove listener after closing
                }
            });
        },
        onDestroy: function () {
            $(document).off("keydown.loadingClose");
        },
    });
}


function hideLoading() {
    loadingIndicator.close();
}

function global_showalert(msg, title, type, redirectURL = null) {
    let alertInstance = $.alert({
        title: title,
        content: msg,
        type: type,
        theme: "modern",
        onOpen: function () {
            $(document).on("keydown.globalAlert", function (e) {
                if (e.key === "Enter") {
                    alertInstance.close();
                    $(document).off("keydown.globalAlert");
                }
            });
        },
        onClose: function () {
            $(document).off("keydown.globalAlert");
            if (redirectURL != null) {
                window.location.href = redirectURL;
            }
        },
    });
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

