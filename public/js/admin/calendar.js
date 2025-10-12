var filteredEvents = [];

$(document).on("submit", "#filterFormClassSchedule", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    showLoading();
    let lastClickedScheduleId = null;
    let tempHighlightId = null;

    $.ajax({
        url: "/admin/filterClassSchedule",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideLoading();
            if ($("main.instructor-dashboard").length) {
                const instructorSubject = response.instructorSubject || [];
                const todaySchedule = response.todaySchedule || [];

                // Assigned Classes
                let subjectListHTML = "";
                if (instructorSubject.length === 0) {
                    subjectListHTML = `<li class="list-group-item text-muted">No assigned classes</li>`;
                } else {
                    instructorSubject.forEach((subject) => {
                        subjectListHTML += `
                        <li class="list-group-item">
                            <span>${subject.subject_name}</span>
                            <span class="badge bg-light text-dark ms-2">${subject.course_code}</span>
                        </li>
                    `;
                    });
                }

                $(
                    ".instructor-dashboard .card:contains('Assigned Classes') .card-body ul"
                ).html(subjectListHTML);

                // Today's Schedule
                let todayScheduleHTML = "";
                if (todaySchedule.length === 0) {
                    todayScheduleHTML = `<li class="list-group-item text-muted">No schedules for today</li>`;
                } else {
                    todaySchedule.forEach((sched) => {
                        const formatHour = (timeStr) => {
                            const hour = parseInt(timeStr.split(":")[0]);
                            const ampm = hour >= 12 ? "PM" : "AM";
                            const hour12 = hour % 12 === 0 ? 12 : hour % 12;
                            return `${hour12} ${ampm}`;
                        };

                        const startTime = formatHour(sched.start_time);
                        const endTime = formatHour(sched.end_time);
                        todayScheduleHTML += `
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">${startTime} - ${endTime}</span>
                                <span>${sched.subject_name}</span>
                                <span class="badge bg-light text-dark ms-2">${sched.course_code}</span>
                            </div>
                        </li>
                    `;
                    });
                }

                $(
                    '.instructor-dashboard .card:contains("Today\'s Schedule") .card-body ul'
                ).html(todayScheduleHTML);
            }

            filteredEvents = response.events;
            const instructorName = response.instructorName;
            const instructorType = response.instructorType;
            const type = $("#type").val();

            // Construct the label text
            var scheduleLabel = "";
            if (type == "1") {
                scheduleLabel = `Name: ${instructorName}<br>Type: ${instructorType}`;
                $("#instructor_name").html(`Name: ${instructorName}`).show();
                $("#instructor_type").html(`Type: ${instructorType}`).show();
            } else if (type == "2") {
                var roomName = $(
                    "select[name='room_id'] option:selected"
                ).text();
                $("#room_name").html(`Room: ${roomName}`).show();
            } else if (type == "0") {
                var courseName = $(
                    "#courseGetSectionName option:selected"
                ).text();
                var yearLevel = $("#year_level").val();
                var sectionName = $("#sectionSelect option:selected").text();
                $("#course_nameDNONE").html(`Course: ${courseName}`).show();
                $("#year_levelDNONE").html(`Year: ${yearLevel}`).show();
                $("#sectionNameDNONE").html(`Section: ${sectionName}`).show();
            }

            // Show and update the label

            var $calendarEl = $("#calendar");

            if (!$calendarEl.length) return;

            // Fetch events from backend
            var calendar = new FullCalendar.Calendar($calendarEl[0], {
                initialView: "timeGridWeek",
                allDaySlot: false,
                firstDay: 1,
                slotDuration: "00:30:00",
                slotLabelInterval: "00:30:00",
                slotMinTime: "07:00:00",
                slotMaxTime: "18:00:00",
                slotLabelContent: function (arg) {
                    function formatAMPM(date) {
                        let hours = date.getHours();
                        let minutes = date.getMinutes();
                        // const ampm = hours >= 12 ? "PM" : "AM";
                        hours = hours % 12;
                        hours = hours ? hours : 12; // 0 -> 12
                        minutes = minutes < 10 ? "0" + minutes : minutes;
                        return `${hours}:${minutes}`;
                    }

                    const start = arg.date;
                    const end = new Date(arg.date.getTime() + 30 * 60000); // 30 minutes

                    return {
                        html: `${formatAMPM(start)} – ${formatAMPM(end)}`,
                    };
                },

                height: window.innerWidth < 1100 ? 900 : 1000,
                expandRows: true,
                headerToolbar: {
                    left: "",
                    center: "title",
                    right: "",
                },
                slotLabelFormat: {
                    hour: "numeric",
                    minute: "2-digit",
                    meridiem: false,
                    hour12: false,
                },
                events: function (fetchInfo, successCallback, failureCallback) {
                    // Use current filter values here
                    successCallback(filteredEvents);
                },
                dayHeaderContent: function (arg) {
                    return {
                        html: `<span style="color: black; font-weight: bold;">${arg.date.toLocaleDateString(
                            "en-US",
                            {
                                weekday: "short",
                            }
                        )}</span>`,
                    };
                },
                eventDidMount: function (info) {
                    // If it's a background highlight event, skip custom content
                    if (info.event.display === "background") {
                        info.el.style.backgroundColor = "#1efb38ff";
                        info.el.style.border = "none";
                        return;
                    }

                    var ev = info.event.extendedProps;

                    // Build the inner HTML for the event
                    let html = `
        <div style="font-weight:bold; font-size:0.75em; line-height:1.1;">${
            ev.subjectCode || ""
        }</div>
        <div style="font-size:0.75em; line-height:1.1;">${
            ev.subjectName || ""
        }</div>
        <div style="font-size:0.7em; line-height:1.1;">${
            ev.instructorName || ""
        }</div>
        <div style="font-size:0.7em; line-height:1.1;">${
            ev.abbreviation + " " + ev.year_level + " " + ev.section_name || ""
        }</div>
        <div style="font-size:0.7em; line-height:1.1;">${
            ev.roomName || ""
        }</div>
        <div style="font-size:0.7em; line-height:1.1;">${
            ev.type ? ev.type.charAt(0).toUpperCase() + ev.type.slice(1) : ""
        }</div>
    `;

                    info.el.innerHTML = html;
                    info.el.classList.add("fc-event-content-centered");

                    // Appearance
                    info.el.style.backgroundColor = "#F8F7F7";
                    info.el.style.border = "1px solid black";
                    info.el.style.color = "black";
                    info.el.style.padding = "2px 4px";
                    info.el.style.fontWeight = "500";
                    info.el.style.textAlign = "center";
                    info.el.style.cursor = "pointer";
                    info.el.style.transition =
                        "background-color 0.3s, transform 0.2s";

                    // Keep inside its column
                    info.el.style.width = "100%";

                    // Allow wrapping so all content is visible
                    info.el.style.whiteSpace = "normal";

                    // Let height grow to fit the text
                    info.el.style.overflow = "hidden";

                    // No text cut-off
                    info.el.style.textOverflow = "unset";

                    // Tooltip setup
                    info.el.setAttribute("data-bs-toggle", "tooltip");
                    info.el.setAttribute("data-bs-placement", "top");
                    info.el.setAttribute(
                        "title",
                        `${ev.subjectCode || ""} ${ev.subjectName || ""}\n${
                            ev.instructorName || ""
                        }\n${ev.roomName || ""}`
                    );

                    new bootstrap.Tooltip(info.el, { trigger: "hover" });
                },
                eventClick: function (info) {
                    if (info.event.display === "background") {
                        return; // do nothing
                    }

                    if (tempHighlightId) {
                        let prev = calendar.getEventById(tempHighlightId);
                        if (prev) {
                            prev.remove();
                        }
                        tempHighlightId = null;
                    }

                    let schedule_id = info.event.extendedProps.schedule_id;
                    let sideCards = $("#sideCards");
                    let calendarCol = $("#calendarCol");

                    // Remove highlight from all events
                    $("#calendar .fc-event").css("border", "1px solid black");

                    // Highlight the clicked event
                    $(info.el).css("border", "5px solid #2dbe4fff");

                    // Case 1: Click same slot while side card is open → just close, no AJAX
                    if (
                        sideCards.is(":visible") &&
                        lastClickedScheduleId === schedule_id
                    ) {
                        sideCards.addClass("d-none");
                        calendarCol.removeClass("col-lg-9").addClass("col-12");
                        $("#calendar .fc-event").css(
                            "border",
                            "1px solid black"
                        );
                        lastClickedScheduleId = null; // reset
                        return;
                    }

                    // Update last clicked
                    lastClickedScheduleId = schedule_id;

                    // Always show the side card for a new slot
                    sideCards.removeClass("d-none");
                    calendarCol.removeClass("col-12").addClass("col-lg-9");

                    let evTime = info.event;
                    let start = evTime.start.toLocaleTimeString("en-PH", {
                        hour: "2-digit",
                        minute: "2-digit",
                        hour12: true,
                    });
                    let end = evTime.end.toLocaleTimeString("en-PH", {
                        hour: "2-digit",
                        minute: "2-digit",
                        hour12: true,
                    });
                    let date = evTime.start.toLocaleDateString("en-PH", {
                        weekday: "long",
                    });

                    $("#schedule_day_and_time").text(
                        date + " - " + start + " TO " + end
                    );

                    // Get available slots via AJAX (only if new slot clicked)
                    $.ajax({
                        url:
                            "/admin/schedule/" +
                            schedule_id +
                            "/available-slots",
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            var container = $("#available-slots-container");
                            container.empty();

                            if (response.slots.length === 0) {
                                container.append(
                                    '<p class="text-center text-muted m-0">No available slots found</p>'
                                );
                                return;
                            }

                            const days = [
                                "Sunday",
                                "Monday",
                                "Tuesday",
                                "Wednesday",
                                "Thursday",
                                "Friday",
                                "Saturday",
                            ];

                            // Group slots by room name
                            let slotsByDay = {};
                            response.slots.forEach(function (slot) {
                                if (
                                    slot.schedule_day >= 0 &&
                                    slot.schedule_day <= 6
                                ) {
                                    let dayName = days[slot.schedule_day];
                                    if (!slotsByDay[dayName]) {
                                        slotsByDay[dayName] = [];
                                    }
                                    slotsByDay[dayName].push(slot);
                                }
                            });

                            function formatTime(timeStr) {
                                let [hour, minute] = timeStr.split(":");
                                hour = parseInt(hour);
                                let ampm = hour >= 12 ? "PM" : "AM";
                                hour = hour % 12 || 12;
                                return `${hour
                                    .toString()
                                    .padStart(2, "0")}:${minute} ${ampm}`;
                            }

                            let accordionId = "dayAccordion";
                            let accordionHTML = `<div class="accordion" id="${accordionId}">`;

                            Object.keys(slotsByDay).forEach(function (
                                dayName,
                                index
                            ) {
                                let collapseId = `collapseDay${index}`;
                                let headingId = `headingDay${index}`;

                                accordionHTML += `
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="${headingId}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                                                ${dayName}
                                                            </button>
                                                        </h2>
                                                        <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="${headingId}"
                                                            data-bs-parent="#${accordionId}">
                                                            <div class="accordion-body p-2">
                                                `;

                                slotsByDay[dayName].forEach(function (slot) {
                                    var formattedTime = `${formatTime(
                                        slot.start_time
                                    )} TO ${formatTime(slot.end_time)}`;
                                    accordionHTML += `
                                                        <button type="button" 
                                                            class="btn btn-outline-secondary w-100 text-start mb-2 slot-button" 
                                                            data-slot="${dayName} ${formattedTime}" 
                                                            data-room="${slot.room_name}">
                                                            <div class="fw-semibold">${formattedTime}</div>
                                                            <small class="text-muted">Room: ${slot.room_name}</small>
                                                        </button>
                                                    `;
                                });

                                accordionHTML += `
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                            });

                            accordionHTML += `</div>`;
                            container.append(accordionHTML);

                            $(document).on(
                                "click",
                                ".slot-button",
                                function () {
                                    // button UI
                                    $(".slot-button")
                                        .removeClass("active")
                                        .css(
                                            "border",
                                            "1px solid var(--bs-primary)"
                                        )
                                        .find("small")
                                        .removeClass("text-white")
                                        .addClass("text-muted");
                                    $(this)
                                        .addClass("active")
                                        .find("small")
                                        .removeClass("text-muted")
                                        .addClass("text-white");

                                    // parse slot text: "Thursday 10:00 AM TO 12:00 PM"
                                    let slotText = $(this).data("slot");
                                    let roomName = $(this).data("room");
                                    let [dayName] = slotText.split(" ");
                                    let timeRange = slotText.replace(
                                        dayName + " ",
                                        ""
                                    );
                                    let [startTimeStr, endTimeStr] =
                                        timeRange.split(" TO ");

                                    // convert to 24h with seconds
                                    let start24 = moment(startTimeStr, [
                                        "h:mm A",
                                    ]).format("HH:mm:ss");
                                    let end24 = moment(endTimeStr, [
                                        "h:mm A",
                                    ]).format("HH:mm:ss");

                                    // remove previous highlight event (if any)
                                    if (tempHighlightId) {
                                        let prev =
                                            calendar.getEventById(
                                                tempHighlightId
                                            );
                                        if (prev) {
                                            prev.remove();
                                        }
                                        tempHighlightId = null;
                                    }

                                    // find date for the dayName using calendar's current view (more reliable than DOM)
                                    let viewStart =
                                        calendar.view &&
                                        calendar.view.activeStart
                                            ? calendar.view.activeStart
                                            : new Date();
                                    let dayDate = null;
                                    for (let i = 0; i < 7; i++) {
                                        let d = moment(viewStart).add(
                                            i,
                                            "days"
                                        );
                                        if (d.format("dddd") === dayName) {
                                            dayDate = d.format("YYYY-MM-DD");
                                            break;
                                        }
                                    }

                                    if (!dayDate) {
                                        return;
                                    }

                                    // add a background event that highlights the time range
                                    const highlightId =
                                        "slot-highlight-" + Date.now();
                                    calendar.addEvent({
                                        id: highlightId,
                                        title: "Available Slot",
                                        start: `${dayDate}T${start24}`,
                                        end: `${dayDate}T${end24}`,
                                        display: "background", // makes it a background overlay
                                        overlap: false,
                                        textColor: "#000000ff",
                                        classNames: ["available-slot"],
                                    });

                                    tempHighlightId = highlightId;
                                    $(".fc-event-main").hide();
                                }
                            );
                        },
                    });

                    matchSideCardsHeight();
                },
            });
            calendar.render();
            matchSideCardsHeight();
            $(window).on("resize", matchSideCardsHeight);
            $calendarEl.addClass("section-calendar-instructor");
        },
        error: function (xhr) {
            hideLoading();
            if (xhr.status === 409) {
                global_showalert(
                    xhr.responseJSON.message,
                    "Warning!",
                    "orange"
                );
            } else {
                let response = JSON.parse(xhr.responseText);
                let errorMessage = "An error occurred";
                if (response.errors) {
                    errorMessage = "";
                    for (let errorKey in response.errors) {
                        errorMessage += response.errors[errorKey][0] + "\n";
                    }
                }
                global_showalert(errorMessage, "Alert!", "red");
            }
        },
    });
});

$("#toggleSideCards").on("click", function () {});

const downloadBtn = document.getElementById("downloadSchedule");
if (downloadBtn) {
    downloadBtn.addEventListener("click", function () {
        showDownloadLoading();
        const element = document.getElementById("scheduleCardToDownload");

        html2canvas(element, { scale: 2, useCORS: true }).then((canvas) => {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF("landscape", "pt", "a4");

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const imgWidth = canvas.width;
            const imgHeight = canvas.height;
            const ratio = Math.min(
                pageWidth / imgWidth,
                pageHeight / imgHeight
            );

            const scaledWidth = imgWidth * ratio;
            const scaledHeight = imgHeight * ratio;

            const imgData = canvas.toDataURL("image/png");
            const x = (pageWidth - scaledWidth) / 2;
            const y = (pageHeight - scaledHeight) / 2;

            pdf.addImage(imgData, "PNG", x, y, scaledWidth, scaledHeight);
            pdf.save("class_schedule.pdf");
            hideLoading();
        });
    });
}

let maindashboard = $("main.dashboard");
if (maindashboard.length) {
    $('[data-bs-target="#filterOffcanvas"]').on("click", function () {
        if ($("#is_gec").val() == "1") {
            global_showalert("Hindi pa ito nagana", "Access denied", "red");
            return false;
        }
    });
}

function matchSideCardsHeight() {
    var calendarColHeight = $("#calendarCol").outerHeight();
    $("#sideCards").css({
        height: calendarColHeight,
        overflow: "hidden",
    });
    $("#sideCards .card-body").css({
        maxHeight:
            calendarColHeight - $("#sideCards .card-header").outerHeight(),
        overflowY: "auto",
    });
}
