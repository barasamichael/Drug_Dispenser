<!DOCTYPE html>
<html>
<head>
    <title>List of Patients</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load initial patient data
            loadPatients();

            // Search functionality
            $('#searchInput').on('keyup', function() {
                searchPatients($(this).val());
            });

            // Sorting functionality
            $('.sortable').on('click', function() {
                var column = $(this).data('column');
                var order = $(this).data('order');
                sortPatients(column, order);
	    });
	    alert("Fetch in progress");
        });

        function loadPatients() {
            $.ajax({
                url: 'get_patients.php',
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    $('#patientTable tbody').html(data);
                },
                error: function() {
                    alert('Error occurred while fetching patients.');
                }
            });
        }

        function searchPatients(keyword) {
            $.ajax({
                url: 'search_patients.php',
                type: 'GET',
                dataType: 'html',
                data: { keyword: keyword },
                success: function(data) {
                    $('#patientTable tbody').html(data);
                },
                error: function() {
                    alert('Error occurred while searching patients.');
                }
            });
        }

        function sortPatients(column, order) {
            $.ajax({
                url: 'sort_patients.php',
                type: 'GET',
                dataType: 'html',
                data: { column: column, order: order },
                success: function(data) {
                    $('#patientTable tbody').html(data);
                },
                error: function() {
                    alert('Error occurred while sorting patients.');
                }
            });
        }
    </script>
</head>
<body>
    <div>
        <h3 style="color: green;" class="page-header">List Of Patients</h3>
        <input type="text" id="searchInput" placeholder="Search by Name">
    </div>
    <table class="table table-striped table-responsive table-hover">
        <thead>
            <tr>
                <th class="sortable" data-column="patientId" data-order="asc">Patient ID</th>
                <th class="sortable" data-column="name" data-order="asc">Name</th>
                <th class="sortable" data-column="email" data-order="asc">Email Address</th>
                <th class="sortable" data-column="phone" data-order="asc">Phone Number</th>
                <th class="sortable" data-column="address" data-order="asc">Residential Address</th>
                <th class="sortable" data-column="socialSecurity" data-order="asc">Social Security</th>
            </tr>
        </thead>
        <tbody id="patientTable">
            <!-- Patients will be loaded dynamically here -->
        </tbody>
    </table>
</body>
</html>
