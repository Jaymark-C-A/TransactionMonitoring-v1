<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Monitoring</title>
    <!-- CSS styles for card layout -->
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card {
            flex: 0 0 calc(50% - 10px); /* Adjust the width as needed */
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            width: 190%;
            margin-bottom: 10px;
        }

        /* Styles for queue and ticket display */
        .queue-info {
            display: flex;
            justify-content: space-between;
            margin: 1rem;
            gap: 20px;
        }

        .queue-list, .current-ticket {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
        }
        .queue-list{
            width: 35%;
        }

        .current-ticket {
            text-align: center;
        }

        .current-ticket .ticket-number {
            font-size: 26px;
            margin: 20px 0;
        }

        .current-ticket .actual-time {
            font-size: 1em;
            color: #d9534f;
            margin: 20px;
        }
        .col-md-4 {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px; /* Optional: Add some space between columns */
        }
    </style>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="main-header navbar navbar-expand-lg navbar-light bg-light">
        <!-- Include nav-offices content -->
        @include('includes.nav-offices')
    </nav>
    <div class="queue-info">
        <!-- Queue List -->
        <div class="queue-list">
            <div id="data-container"></div>
        </div>
        
        <!-- Current Ticket Info -->
        <div class="current-ticket">
            <div class="container">
                <div class="row">
                    <div class="col-md-4" style="line-height: 20px;">
                        <div>
                            <p style="font-size:20px; color:blue;">Records</p>
                            <p style="font-size:18px; color:blue;">Currently Serving!</p>
                            <div class="ticket-number" id="records-ticket">---</div>
                            <div class="actual-time">Actual Service Time: 00:12:00</div>
                        </div>
                    </div>
                    <div class="col-md-4" style="line-height: 20px;">
                        <div>
                            <p style="font-size:20px; color:blue;">Accounting</p>
                            <p style="font-size:18px; color:blue;">Currently Serving!</p>
                            <div class="ticket-number" id="accounting-ticket">---</div>
                            <div class="actual-time">Actual Service Time: 00:12:00</div>
                        </div>
                    </div>
                    <div class="col-md-4" style="line-height: 20px;">
                        <div>
                            <p style="font-size:20px; color:blue;">Principal</p>
                            <p style="font-size:18px; color:blue;">Currently Serving!</p>
                            <div class="ticket-number" id="principal-ticket">---</div>
                            <div class="actual-time">Actual Service Time: 00:12:00</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4" style="line-height: 20px;">
                        <div>
                            <p style="font-size:20px; color:blue;">Admin</p>
                            <p style="font-size:18px; color:blue;">Currently Serving!</p>
                            <div class="ticket-number" id="admin-ticket">---</div>
                            <div class="actual-time">Actual Service Time: 00:12:00</div>
                        </div>
                    </div>
                    <div class="col-md-4" style="line-height: 20px;">
                            <a id="hamburger-menu" class="nav-link" data-widget="pushmenu" href="/super-admin/dashboard" role="button">
                                <img src="{{ asset('img/logo.png') }}" alt="logo" style="width: 150px; height: auto;">
                            </a>
                    </div>
                    <div class="col-md-4" style="line-height: 20px;">
                        <div>
                            <p style="font-size:20px; color:blue;">Department Head</p>
                            <p style="font-size:18px; color:blue;">Currently Serving!</p>
                            <div class="ticket-number" id="department-head-ticket">---</div>
                            <div class="actual-time">Actual Service Time: 00:12:00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Your custom script -->
    <script>
        $(document).ready(function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            function fetchData() {
                $.ajax({
                    url: '/monitor-fetch-data',
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(data) {
                        var filteredData = data.filter(function(item) {
                            return item.status !== 'completed' && item.status !== 'canceled';
                        });
                        
                        var html = '<div class="w-50 pl-4 pb-3">';
                        for (var i = 0; i < Math.min(filteredData.length, 4); i++) {
                            var visitor = filteredData[i];
                            html += '<div class="card">';
                            html += '<p class="mb-0" style="font-size:20px;"><strong>' + visitor.ticket_number + '</strong></p>';
                            html += '<p class="mb-0"><strong>Department:</strong> ' + visitor.department + '</p>';
                            html += '<p class="mb-0"><strong>Purpose:</strong> ' + visitor.purpose + '</p>';
                            html += '</div>';
                        }
                        html += '</div>';
                        $('#data-container').html(html);

                        // Update the current ticket number for each department
                        var departmentMap = {
                            'Records': '#records-ticket',
                            'Accounting': '#accounting-ticket',
                            'Principal': '#principal-ticket',
                            'Admin': '#admin-ticket',
                            'DepartmentHead': '#department-head-ticket'
                        };
                        
                        // Reset ticket numbers for all departments
                        for (var key in departmentMap) {
                            $(departmentMap[key]).text('---');
                        }

                        // Create an object to store the first ticket number for each department
                        var firstTickets = {};

                        // Update ticket numbers based on the filtered data
                        filteredData.forEach(function(visitor) {
                            if (!firstTickets[visitor.department]) {
                                firstTickets[visitor.department] = visitor.ticket_number;
                            }
                        });

                        // Set the ticket numbers for each department
                        for (var department in firstTickets) {
                            if (departmentMap[department]) {
                                $(departmentMap[department]).text(firstTickets[department]);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }
            

            // Fetch data initially
            fetchData();

            // Fetch data every 5 seconds
            setInterval(fetchData, 5000);
        });
    </script>
</body>
</html>
