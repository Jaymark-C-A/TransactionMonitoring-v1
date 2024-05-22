<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('includes.head')
    <style>
        /* CSS styles for card layout */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card {
            flex: 0 0 calc(150% - 6px); /* Adjust the width as needed */
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        /* Styles for queue and ticket display */
        .queue-info {
            display: flex;
            justify-content: space-between;
            margin: 1.5rem;
        }

        .queue-list, .current-ticket {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
            width: 48%;
        }
        .current-ticket {
            text-align: center;
        }

        .current-ticket .ticket-number {
            font-size: 3em;
            margin: 20px 0;
        }

        .current-ticket .service-time {
            font-size: 1.2em;
            color: #666;
        }

        .current-ticket .actual-time {
            font-size: 1.5em;
            color: #d9534f;
            margin-bottom: 20px;
        }

        .current-ticket .actions button {
            background-color: #5bc0de;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .current-ticket .actions button.no-show {
            background-color: #d9534f;
        }

        .current-ticket .actions button.recall {
            background-color: #f0ad4e;
        }

        .current-ticket .actions button.transfer {
            background-color: #0275d8;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="text-sm">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-lg navbar-light bg-light">
            @include('includes.nav')
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            @include('includes.sidebar.sidebar-offices')  
        </aside>
        <div class="content-wrapper bg-light">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4 class="m-0">Offices Dashboard</h4>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->
            <div class="queue-info">
                <!-- My Queue List -->
                <div class="queue-list">
                    <h5>Queue List :</h5>
                    <div id="data-container"></div>
                </div>
                
                <!-- Current Ticket Info -->
                <div class="current-ticket">
                    <div class="ticket-number">---</div>
                    <div class="actual-time">Actual Service Time: 00:12:00</div>
                    <div class="actions">
                        <button class="next">Next</button>
                        <button class="no-show">No Show</button>
                        <button class="recall">Recall</button>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            @include('includes.footer')
        </footer>
    </div>
    <!-- ./wrapper -->
    <script>
        $(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    function fetchData() {
        $.ajax({
            url: '/fetch-data',
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(data) {
                var filteredData = data.filter(function(item) {
                    return item.status !== 'completed' && item.status !== 'canceled' && item.department;
                });
                var html = '<div class="card-container w-50 pl-4 pb-3">';
                for (var i = 0; i < Math.min(filteredData.length, 2); i++) {
                    var visitor = filteredData[i];
                    html += '<div class="card">';
                    html += '<p class="mb-0" style="font-size:20px;"><strong>' + visitor.ticket_number + '</strong></p>';
                    html += '<p class="mb-0"><strong>Department:</strong> ' + visitor.department + '</p>';
                    html += '<p class="mb-0"><strong>Purpose:</strong> ' + visitor.purpose + '</p>';
                    html += '</div>';
                }
                html += '</div>';
                $('#data-container').html(html);
                
                // Update the current ticket number
                if (filteredData.length > 0) {
                    $('.current-ticket .ticket-number').text(filteredData[0].ticket_number);
                } else {
                    // If there are no more tickets in the queue, display "---"
                    $('.current-ticket .ticket-number').text('---');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    // Action handlers
    $('.current-ticket .actions button.next').click(function() {
        $.ajax({
            url: '/next-ticket',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                fetchData();
            },
            error: function(xhr, status, error) {
                console.error('Error updating ticket status:', error);
            }
        });
    });

    $('.current-ticket .actions button.no-show').click(function() {
        $.ajax({
            url: '/cancel-ticket',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                fetchData();
            },
            error: function(xhr, status, error) {
                console.error('Error cancelling ticket:', error);
            }
        });
    });

    // Fetch data initially
    fetchData();

    // Fetch data every 5 seconds
    setInterval(fetchData, 2000);
});

    </script>

<script>
    $(document).ready(function() {
        // Toggle sidebar menu
        $('.nav-link').click(function() {
            var parent = $(this).parent();
            if ($(parent).hasClass('menu-open')) {
                $(parent).removeClass('menu-open');
            } else {
                $(parent).addClass('menu-open');
            }
        });
    });
</script>

    
    
</body>
</html>
