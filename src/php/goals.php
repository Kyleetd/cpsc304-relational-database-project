<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goals</title>
</head>
<style>
    .header {
        text-align: center;
        font-size: 25px;
        padding: 10px;
        background-color: #f2f2f2;
    }

    table {
        margin: auto;
        border-collapse: collapse;
        width: 80%;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .add-goal-button {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        background-color: #f2f2f2;
        border: 1px solid #ddd;
        border-radius: 3px;
        cursor: pointer;
    }

    .hidden-row {
        display: none;
    }
</style>
<script>
    function showInputForm() {
        var formRow = document.getElementById('form-row');
        formRow.style.display = 'table-row';
    }
</script>
</head>
<body>

    <div class="header">
        <h1>Fitness Goals</h1>
        <div class="add-goal-button" onclick="showInputForm()">+</div>
    </div>

    <?php
    // Establish a connection to the Oracle database
    $db_conn = OCILogon("ora_kyleetd", "a78242021", "dbhost.students.cs.ubc.ca:1522/stu");

    // Check if the connection was successful
    if (!$db_conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    // Prepare and execute the SQL query
    $query = "SELECT * FROM User_FitnessGoal";
    $stmt = oci_parse($db_conn, $query);
    oci_execute($stmt);

    // Display the table
    echo '<table>';
    echo '<tr><th>Goal ID</th><th>Description</th><th>Target Date</th><th>User ID</th></tr>';

    while ($row = oci_fetch_assoc($stmt)) {
        echo '<tr>';
        echo '<td>' . $row['GOALID'] . '</td>';
        echo '<td>' . $row['DESCRIPTION'] . '</td>';
        echo '<td>' . $row['TARGETDATE'] . '</td>';
        echo '<td>' . $row['USERID'] . '</td>';
        echo '</tr>';
    }

    // Display the input form row
    echo '<tr id="form-row" class="hidden-row">';
    echo '<td></td>';
    echo '<td>';
    echo '<form method="post" action="">';
    echo '<input type="text" name="description" placeholder="Enter goal description">';
    echo '</td>';
    echo '<td><input type="text" name="targetDate" placeholder="Enter target date"></td>';
    echo '<td>';
    echo '<input type="submit" name="submit" value="Add">';
    echo '</form>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';

    // Handle form submission
    if (isset($_POST['submit'])) {
        $description = $_POST['description'];
        $targetDate = $_POST['targetDate'];

        // Perform the database insertion
        $insertQuery = "INSERT INTO User_FitnessGoal (DESCRIPTION, TARGETDATE) VALUES (:description, :targetDate)";
        $insertStmt = oci_parse($db_conn, $insertQuery);
        oci_bind_by_name($insertStmt, ":description", $description);
        oci_bind_by_name($insertStmt, ":targetDate", $targetDate);
        oci_execute($insertStmt);

        // Refresh the page to display the updated table
        header("Refresh:0");
    }

    // Close the database connection
    oci_free_statement($stmt);
    oci_close($db_conn);

    ?>

</body>

</html>
