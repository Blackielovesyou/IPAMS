$(document).ready(function () {
    $("#filterRoomSchedule").on("submit", function (e) {
        e.preventDefault();

        let type = $("#type").val();
        let room_id = $('select[name="room_id"]').val();
        let instructorID = $('select[name="instructorID"]').val();
        let section_id = $('select[name="section_id"]').val();
        let school_year = $('select[name="school_year"]').val();
        let semester = $('select[name="semester"]').val();

        if (type == 2) {
            if (!room_id || !school_year || !semester) {
                alert("Please select all required filters.");
                return;
            }
        } else if (type == 1) {
            if (!instructorID || !school_year || !semester) {
                alert("Please select all required filters.");
                return;
            }
        } else {
        }

        $.ajax({
            url: "/admin/getAvailableSlots",
            type: "GET",
            data: {
                type: type,
                room_id: room_id,
                school_year: school_year,
                semester: semester,
                instructorID: instructorID,
                section_id: section_id,
            },
            success: function (response) {
                var filteredEvents = response.events;
                var $calendarEl = $("#calendar");

                if (!$calendarEl.length) return;
                var initialView =
                    window.innerWidth < 1100 ? "timeGridDay" : "timeGridWeek";

                // Fetch events from backend
                var calendar = new FullCalendar.Calendar($calendarEl[0], {
                    initialView: initialView,
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
                    events: function (
                        fetchInfo,
                        successCallback,
                        failureCallback
                    ) {
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
                        var ev = info.event.extendedProps;
                        let html = `
                    <div style="font-weight:bold; font-size:0.75em; line-height:1.1;">${
                        ev.title || ""
                    }</div>
                    
                `;
                        info.el.innerHTML = html;

                        // Flexbox centering
                        info.el.style.display = "flex";
                        info.el.style.flexDirection = "column";
                        info.el.style.justifyContent = "center"; // vertical center
                        info.el.style.alignItems = "center"; // horizontal center

                        // Add these styles for better fit
                        info.el.style.backgroundColor = "yellow";
                        info.el.style.border = "1px solid black";
                        info.el.style.color = "black";
                        info.el.style.padding = "2px 4px";
                        info.el.style.fontWeight = "500";
                        info.el.style.textAlign = "center";
                        info.el.style.cursor = "pointer";
                        info.el.style.transition =
                            "background-color 0.3s, transform 0.2s";
                        info.el.style.overflow = "hidden";
                        info.el.style.textOverflow = "ellipsis";
                        info.el.style.whiteSpace = "normal";
                        info.el.style.wordBreak = "break-word";

                        info.el.setAttribute("data-bs-toggle", "tooltip");
                        info.el.setAttribute("data-bs-placement", "top");
                        info.el.setAttribute("title", `${ev.title || ""}`);

                        // Bootstrap tooltip
                        var tooltip = new bootstrap.Tooltip(info.el, {
                            trigger: "hover",
                        });
                        var style = document.createElement("style");

                        document.head.appendChild(style);
                    },
                    eventClick: function (info) {
                        var evTime = info.event;
                        var ev = info.event.extendedProps;

                        var start = evTime.start.toLocaleTimeString("en-PH", {
                            hour: "2-digit",
                            minute: "2-digit",
                            hour12: true,
                        });
                        var end = evTime.end.toLocaleTimeString("en-PH", {
                            hour: "2-digit",
                            minute: "2-digit",
                            hour12: true,
                        });
                        var date = evTime.start.toLocaleDateString("en-PH", {
                            weekday: "long",
                            year: "numeric",
                            month: "short",
                            day: "numeric",
                        });

                        var modal = new bootstrap.Modal(
                            document.getElementById("eventModal")
                        );
                        modal.show();
                    },
                });
                calendar.render();
            },
            error: function (xhr) {},
        });
    });
});

$("#move-schedule-table").on("click", ".schedule-row", function () {
    var scheduleId = $(this).data("id");

    // 1. Load available slots
    $.ajax({
        url: "/admin/schedule/" + scheduleId + "/available-slots",
        type: "GET",
        dataType: "json",
        success: function (response) {
            var tbody = $("#available-slots-table tbody");
            tbody.empty();
            if (response.slots.length === 0) {
                tbody.append(
                    '<tr><td colspan="3" class="text-center">No available slots found</td></tr>'
                );
                return;
            }

            $.each(response.slots, function (index, slot) {
                var row =
                    "<tr><td>" +
                    slot.schedule_day +
                    "</td><td>" +
                    slot.start_time +
                    " - " +
                    slot.end_time +
                    "</td><td>" +
                    slot.room_name +
                    "</td></tr>";
                tbody.append(row);
            });
        },
    });
});
