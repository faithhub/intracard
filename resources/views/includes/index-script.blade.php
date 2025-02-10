<style>
    canvas {
        width: 100%;
        max-width: 300px;
        height: auto;
        margin: 0 auto;
        display: block;
    }

    .fw-bold {
        font-weight: 700;
    }

    .text-gray-800 {
        color: #343a40;
    }
</style>

<style>
    /* Change cursor to pointer when hovering over events */
    .fc-event {
        cursor: pointer;
    }

    /* Optional: Add hover effect to highlight the event */
    .fc-event:hover {
        box-shadow: 0px 4px 6px rgba(206, 202, 202, 0.2);
    }

    .card .card-body {
        padding: 0rem;
    }

    .form-switch .form-check-input {
        transition: background-position .40s cubic-bezier(0.18, 0.42, 0.24, 0.55) !important;
    }

    .form-check-input {
        border: 0px;
        height: 2.25rem !important;
    }

    .form-switch .form-check-input {
        width: 5.25rem;
    }

    .card {
        width: 100%;
        /* Adjust the percentage as needed */
        margin: auto;
        /* Center the card */
        padding: 1rem;
    }

    #kt_app_content_container {
        /* max-width: 1200px; */
        /* Adjust as needed */
        margin: auto;
        /* Center the container */
    }

    .card-1 {
        /* color: white; */
        /* margin: 1rem; */
        box-shadow: inset 1px 15px 20px 8px rgb(11 33 40 / 89%);
        -webkit-box-shadow: 1px 6px 10px 1px rgb(93 125 135 / 40%);
        /* -webkit-box-shadow: -1px 10px 20px 12px rgb(93 125 135 / 40%); */
        -moz-box-shadow: -1px 13px 30px 0px rgba(93, 125, 135, 0.78);
    }

    .card-2 {
        background-color: #43367b;
        color: white;
        margin: 1rem;
        box-shadow: inset 1px 15px 20px 8px rgb(11 33 40 / 89%);
        -webkit-box-shadow: -1px 10px 20px 12px rgb(93 125 135 / 40%);
        -moz-box-shadow: -1px 13px 30px 0px rgba(93, 125, 135, 0.78);
    }

    .card-3 {
        background-color: #43367b;
        color: white;
        margin: 1rem;
        box-shadow: 1px 11px 39px 16px rgba(159, 131, 225, 0.76);
        -webkit-box-shadow: 1px 11px 39px 16px rgba(159, 131, 225, 0.76);
        -moz-box-shadow: 1px 11px 39px 16px rgba(159, 131, 225, 0.76);
    }

    .form-check-input {
        border-color: #a3b1aa8c;
        background-color: #a3b1aa8c;
    }

    .form-check-input:checked {
        background-color: #000;
        border-color: #000;
    }

    .border-dashed {
        border-style: dashed !important;
    }

    .fw-light {
        font-weight: 300 !important;
    }

    .fw-bold {
        font-weight: 600 !important;
    }

    #calendar {
        /* max-width: 1100px; */
        margin: auto;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .fc-view-multiMonthFourMonth {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        /* Show 2 months per row */
        gap: 20px;
        /* Spacing between months */
    }

    .fc-license-message {
        display: none !important;
    }

    .nav-item .nav-link {
        display: flex;
        align-items: center;
        gap: 8px;
        /* Space between the icon and text */
    }

    .nav-item span {
        border-radius: 4px;
        /* Rounded corners for the indicators */
    }
</style>


<script>
    // TransUnion Speedometer
    // new RadialGauge({
    //     renderTo: 'transunionGauge', // ID of the canvas
    //     width: 300,
    //     height: 300,
    //     units: "Credit Score",
    //     minValue: 300, // Minimum score
    //     maxValue: 900, // Maximum score
    //     majorTicks: ["300", "400", "500", "600", "700", "800", "900"], // Ticks on the gauge
    //     minorTicks: 2,
    //     strokeTicks: true,
    //     highlights: [{
    //             from: 300,
    //             to: 500,
    //             color: '#FF4D4F'
    //         }, // Poor range (red)
    //         {
    //             from: 500,
    //             to: 700,
    //             color: '#FFC53D'
    //         }, // Fair range (yellow)
    //         {
    //             from: 700,
    //             to: 900,
    //             color: '#40C057'
    //         }, // Good range (green)
    //     ],
    //     colorPlate: "#f0f0f0", // Background color
    //     colorMajorTicks: "#000",
    //     colorMinorTicks: "#333",
    //     colorNumbers: "#000",
    //     colorNeedle: "black",
    //     colorNeedleEnd: "black",
    //     needleType: "arrow",
    //     needleWidth: 3,
    //     animationDuration: 1500, // Animation duration
    //     animationRule: "bounce",
    //     value: 750, // Current credit score
    // }).draw();

    // // Equifax Speedometer
    // new RadialGauge({
    //     renderTo: 'equifaxGauge', // ID of the canvas
    //     width: 300,
    //     height: 300,
    //     units: "Credit Score",
    //     minValue: 300, // Minimum score
    //     maxValue: 900, // Maximum score
    //     majorTicks: ["300", "400", "500", "600", "700", "800", "900"], // Ticks on the gauge
    //     minorTicks: 2,
    //     strokeTicks: true,
    //     highlights: [{
    //             from: 300,
    //             to: 500,
    //             color: '#FF4D4F'
    //         }, // Poor range (red)
    //         {
    //             from: 500,
    //             to: 700,
    //             color: '#FFC53D'
    //         }, // Fair range (yellow)
    //         {
    //             from: 700,
    //             to: 900,
    //             color: '#40C057'
    //         }, // Good range (green)
    //     ],
    //     colorPlate: "#f0f0f0", // Background color
    //     colorMajorTicks: "#000",
    //     colorMinorTicks: "#333",
    //     colorNumbers: "#000",
    //     colorNeedle: "black",
    //     colorNeedleEnd: "black",
    //     needleType: "arrow",
    //     needleWidth: 3,
    //     animationDuration: 1500, // Animation duration
    //     animationRule: "bounce",
    //     value: 680, // Current credit score
    // }).draw();
</script>

<script>
    // var gauge = new RadialGauge({
    //     renderTo: 'canvas-id',
    //     width: 300,
    //     height: 300,
    //     units: "Km/h",
    //     minValue: 0,
    //     maxValue: 220,
    //     majorTicks: [
    //         "0",
    //         "20",
    //         "40",
    //         "60",
    //         "80",
    //         "100",
    //         "120",
    //         "140",
    //         "160",
    //         "180",
    //         "200",
    //         "220"
    //     ],
    //     minorTicks: 2,
    //     strokeTicks: true,
    //     highlights: [{
    //         "from": 160,
    //         "to": 220,
    //         "color": "rgba(200, 50, 50, .75)"
    //     }],
    //     colorPlate: "#fff",
    //     borderShadowWidth: 0,
    //     borders: false,
    //     needleType: "arrow",
    //     needleWidth: 2,
    //     needleCircleSize: 7,
    //     needleCircleOuter: true,
    //     needleCircleInner: false,
    //     animationDuration: 1500,
    //     animationRule: "linear",
    //     value: 80 // Set the initial value to 80
    // }).draw();

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar2');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap5',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridDay,listMonth,multiMonthFourMonth'
            },
            views: {
                multiMonthFourMonth: {
                    type: 'multiMonth',
                    duration: {
                        months: 12
                    },
                    buttonText: 'Year', // Label for the button
                }
            },
            events: "{{ route('getCalendarEvents') }}",
            eventClick: function(info) {
                // Prevent the default behavior
                info.jsEvent.preventDefault();

                // Populate modal with event details
                document.getElementById('eventTitle').innerText = info.event.title;
                document.getElementById('eventDescription').innerText =
                    info.event.extendedProps.description || 'No description provided.';
                document.getElementById('eventStart').innerText = info.event.start.toLocaleString();
                document.getElementById('eventEnd').innerText = info.event.end ?
                    info.event.end.toLocaleString() :
                    'No end time';
                // document.getElementById('eventLocation').innerText =
                //     info.event.extendedProps.location || 'No location provided.';

                // Show "All Day" badge if the event is all-day
                const allDayBadge = document.getElementById('allDayBadge');
                if (info.event.allDay) {
                    allDayBadge.style.display = 'inline';
                } else {
                    allDayBadge.style.display = 'none';
                }

                // Show the modal
                const eventModal = new bootstrap.Modal(document.getElementById('eventModal'), {
                    keyboard: true,
                });
                eventModal.show();
            },
        });

        calendar.render();
    });

    // document.addEventListener('DOMContentLoaded', function() {
    //     const billTypeSelect = document.getElementById('billTypeSelect');

    //     function updateBillDetails() {
    //     // Hide all bill details sections
    //     document.querySelectorAll('.bill-details').forEach(function(element) {
    //         element.classList.add('d-none');
    //     });

    //     // Get the selected value
    //     const selectedValue = document.getElementById('billTypeSelect').value;

    //     // Show the corresponding bill details based on selection
    //     if (selectedValue === "Car Bill") {
    //         document.getElementById('carBillDetails').classList.remove('d-none');
    //     } else if (selectedValue === "Utility Bill") {
    //         document.getElementById('utilityBillDetails').classList.remove('d-none');
    //     } else if (selectedValue === "Phone Bill") {
    //         document.getElementById('phoneBillDetails').classList.remove('d-none');
    //     } else if (selectedValue === "Internet Bill") {
    //         document.getElementById('internetBillDetails').classList.remove('d-none');
    //     }
    // }

    //     // Add event listener for dropdown changes
    //     billTypeSelect.addEventListener('change', updateBillDetails);

    //     // Initial update
    //     updateBillDetails();
    // });

    // Function to handle bill display logic
    // function updateBillDetails() {
    //     // Hide all bill details sections
    //     document.querySelectorAll('.bill-details').forEach(function(element) {
    //         element.classList.add('d-none');
    //     });

    //     // Get the selected value
    //     const selectedValue = document.getElementById('billTypeSelect').value;

    //     // Show the corresponding bill details based on selection
    //     if (selectedValue === "Car Bill") {
    //         document.getElementById('carBillDetails').classList.remove('d-none');
    //     } else if (selectedValue === "Utility Bill") {
    //         document.getElementById('utilityBillDetails').classList.remove('d-none');
    //     } else if (selectedValue === "Phone Bill") {
    //         document.getElementById('phoneBillDetails').classList.remove('d-none');
    //     } else if (selectedValue === "Internet Bill") {
    //         document.getElementById('internetBillDetails').classList.remove('d-none');
    //     }
    // }

    // // Add event listener for changes to the select element
    // document.getElementById('billTypeSelect').addEventListener('change', function() {
    //     updateBillDetails()
    // });

    // Run the update logic on page load
    // document.addEventListener('DOMContentLoaded', function() {
    //     var scoreToggle = document.getElementById('scoreToggle');
    //     var equifaxCard = document.getElementById('equifaxCard');
    //     var transunionCard = document.getElementById('transunionCard');

    //     // Function to handle card visibility
    //     function updateCardsVisibility() {
    //         if (scoreToggle.checked) {
    //             equifaxCard.classList.add('d-none');
    //             transunionCard.classList.remove('d-none');
    //         } else {
    //             equifaxCard.classList.remove('d-none');
    //             transunionCard.classList.add('d-none');
    //         }
    //     }

    //     // Initialize visibility on page load
    //     updateCardsVisibility();

    //     // Add event listener for the toggle
    //     scoreToggle.addEventListener('change', function() {
    //         updateCardsVisibility();
    //     });
    // });

    document.addEventListener('DOMContentLoaded', function() {
        const scoreToggle = document.getElementById('scoreToggle');
        const creditScoreLabel = document.getElementById('creditScoreLabel');
        const creditGauge = new RadialGauge({
            renderTo: 'creditGauge', // Target canvas ID
            width: 300,
            height: 300,
            units: "Credit Score",
            minValue: 300,
            maxValue: 900,
            majorTicks: ["300", "400", "500", "600", "700", "800", "900"],
            minorTicks: 2,
            strokeTicks: true,
            highlights: [{
                    from: 300,
                    to: 500,
                    color: "#FF4D4F"
                }, // Poor range (red)
                {
                    from: 500,
                    to: 700,
                    color: "#FFC53D"
                }, // Fair range (yellow)
                {
                    from: 700,
                    to: 900,
                    color: "#40C057"
                }, // Good range (green)
            ],
            colorPlate: "#f0f0f0",
            colorMajorTicks: "#000",
            colorMinorTicks: "#333",
            colorNumbers: "#000",
            colorNeedle: "black",
            colorNeedleEnd: "black",
            needleType: "arrow",
            needleWidth: 3,
            animationDuration: 9000, // Slower animation duration (3 seconds)
            animationRule: "linear",
            value: 300, // Start at the minimum value
        }).draw();

        // Function to update the gauge and label
        function updateGauge(creditScore) {
            creditGauge.update({
                value: creditScore
            });
            creditScoreLabel.textContent = creditScore;
        }

        // Initialize toggle behavior
        function updateCardsVisibility() {
            if (scoreToggle.checked) {
                // Display TransUnion score
                updateGauge(560); // TransUnion score
            } else {
                // Display Equifax score
                updateGauge(600); // Equifax score
            }
        }

        // Set initial visibility and animation
        updateCardsVisibility();

        // Add event listener for the toggle switch
        scoreToggle.addEventListener('change', function() {
            updateCardsVisibility();
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-gauges@2.1.7/gauge.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.js"></script>
{{-- <script src="https://preview.keenthemes.com/good/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script> --}}
