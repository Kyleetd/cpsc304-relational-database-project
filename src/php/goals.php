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

    td:first-child {
        width: 100px; /* Set width for the first column (Set Achieved or Delete) */
    }

    td:nth-child(3) {
        width: 40%; /* Set width for the third column (Description) */
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

    .set-achieved-column {
        width: 50px;
    }

    .back-button {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 1px 3px;
    background-color: #f2f2f2;
    border: 1px solid #ddd;
    border-radius: 3px;
    text-decoration: none;
    color: #333;
    font-size: 20px;
    }
</style>
<body>

    <div class="header">
        <h1>Fitness Goals</h1>
        <div class="add-goal-button" onclick="showInputForm()">+</div>
        <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/goalsAndAchievements.php" class="back-button">Back</a>
    </div>

    <?php
    // Establish a connection to the Oracle database
    $db_conn = OCILogon("ora_kyleetd", "a78242021", "dbhost.students.cs.ubc.ca:1522/stu");

    // Check if the connection was successful
    if (!$db_conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    // Define & execute SQL query
    $query = "SELECT * FROM User_FitnessGoal";
    $stmt = oci_parse($db_conn, $query);
    oci_execute($stmt);

    // Display table
    echo '<table>';
    echo '<tr><th>Set Achieved or Delete</th><th>Goal ID</th><th>Description</th><th>Target Date</th><th>User ID</th></tr>';

    echo '<form method="post" action="">'; // Add form element for delete functionality

    while ($row = oci_fetch_assoc($stmt)) {
        // Skip rendering the row if it has been achieved
        if ($row['ACHIEVED'] == 1) {
            continue;
        }
        echo '<tr>';
        echo '<td><input type="checkbox" name="goals[]" value="' . $row['GOALID'] . '"></td>';
        echo '<td>' . $row['GOALID'] . '</td>';
        echo '<td>' . $row['DESCRIPTION'] . '</td>';
        echo '<td>' . $row['TARGETDATE'] . '</td>';
        echo '<td>' . $row['USERID'] . '</td>';
        echo '</tr>';
    }

    // Display input form row (last row) if '+' button is clicked
    echo '<tr id="form-row" class="hidden-row">';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td><input type="text" name="description" placeholder="Enter goal description"></td>';
    echo '<td><input type="text" name="targetDate" placeholder="Enter target date"></td>';
    echo '<td><input type="number" name="userID" placeholder="Enter user ID"></td>';
    echo '<td colspan="2">';
    echo '<input type="submit" name="submit" value="Add">';
    echo '</td>';
    echo '</tr>';

    echo '</table>';

    // Add delete and achieve buttons
    echo '<button type="submit" name="achieved">Achieve</button>';
    echo '<button type="submit" name="delete">Delete</button>';

    echo '</form>'; // Close the form element

    // Handle form submission
    if (isset($_POST['submit'])) {
        $description = $_POST['description'];
        $targetDate = $_POST['targetDate'];
        $userID = (int) $_POST['userID'];

        // Insert goal in User_Achievement table
        $insertQuery = "INSERT INTO User_FitnessGoal (DESCRIPTION, TARGETDATE, USERID) VALUES (:description, :targetDate, :userID)";
        $insertStmt = oci_parse($db_conn, $insertQuery);
        oci_bind_by_name($insertStmt, ":description", $description);
        oci_bind_by_name($insertStmt, ":targetDate", $targetDate);
        oci_bind_by_name($insertStmt, ":userID", $userID);
        oci_execute($insertStmt);

        // Refresh table
        header("Refresh:0");

    } else if (isset($_POST['achieved'])) {
        $selectedGoals = isset($_POST['goals']) ? $_POST['goals'] : [];

        foreach ($selectedGoals as $goalId) {
            // Update User_FitnessGoal table to set ACHIEVED = 1
            $updateQuery = "UPDATE User_FitnessGoal SET ACHIEVED = 1 WHERE GOALID = :goalId";
            $updateStmt = oci_parse($db_conn, $updateQuery);
            oci_bind_by_name($updateStmt, ":goalId", $goalId);
            oci_execute($updateStmt);

            // Get goal information from User_FitnessGoal table
            $query = "SELECT * FROM User_FitnessGoal WHERE GOALID = :goalId";
            $stmt = oci_parse($db_conn, $query);
            oci_bind_by_name($stmt, ":goalId", $goalId);
            oci_execute($stmt);
            $goalRow = oci_fetch_assoc($stmt);

            // Insert goal into User_Achievement table
            $insertQuery = "INSERT INTO User_Achievement (DESCRIPTION, DATEACCOMPLISHED, USERID, GOALID) VALUES (:description, :dateAccomplished, :userID, :goalID)";
            $insertStmt = oci_parse($db_conn, $insertQuery);
            oci_bind_by_name($insertStmt, ":description", $goalRow['DESCRIPTION']);
            oci_bind_by_name($insertStmt, ":dateAccomplished", $goalRow['TARGETDATE']);
            oci_bind_by_name($insertStmt, ":userID", $goalRow['USERID']);
            oci_bind_by_name($insertStmt, ":goalID", $goalRow['GOALID']);
            oci_execute($insertStmt);
        }
        // Refresh table
        header("Refresh:0");

    } else if (isset($_POST['delete'])) {
        $selectedGoals = isset($_POST['goals']) ? $_POST['goals'] : [];

        foreach ($selectedGoals as $goalId) {
            // Delete the goal from User_FitnessGoal table
            $deleteQuery = "DELETE FROM User_FitnessGoal WHERE goalID = :goalId";
            $deleteStmt = oci_parse($db_conn, $deleteQuery);
            oci_bind_by_name($deleteStmt, ":goalId", $goalId);
            oci_execute($deleteStmt);
        }
        // Refresh table
        header("Refresh:0");
    }

    // Close the database connection
    oci_free_statement($stmt);
    oci_close($db_conn);

    ?>

    <script>
        function showInputForm() {
            var formRow = document.getElementById('form-row');
            formRow.style.display = 'table-row';
        }
    </script>

</body>

</html>
