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
        background-color: transparent;
    }
    table {
        margin: auto;
        border-collapse: collapse;
        width: 80%;
        background-color: orange; 
    }
    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        color: #5D3FD3; 
    }
    td:first-child {
        width: 100px; 
    }
    .add-goal-button {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        background-color: #5D3FD3;
        border: 1px solid orange;
        border-radius: 3px;
        cursor: pointer;
        color: orange; 
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
        background-color: #5D3FD3;
        border: 1px solid orange;
        border-radius: 3px;
        text-decoration: none;
        color: orange;
        font-size: 20px;
    }
    body {
        background-image: url("https://i.pinimg.com/564x/f5/f4/ec/f5f4ec2dd2b61c2463461c3d50d03cca.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
    .error-message {
        text-align: center;
        margin-top: 20px;
        color: #5D3FD3;
    }
    input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #5D3FD3;
        border-radius: 3px;
        outline: none;
        transition: background-color 0.3s ease-in-out;
        background-color: purple;
        position: relative;
    }
    input[type="checkbox"]:checked {
        background-color: purple;
    }
    input[type="checkbox"]::before {
        content: "X";
        font-weight: bold;
        font-size: 14px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: orange;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    input[type="checkbox"]:checked::before {
        opacity: 1;
    }
    
    .ach-del-button {
        background-color: #5D3FD3; 
        color: orange;
    }
</style>
<body>

    <div class="header">
        <h1 style="color: orange;">Fitness Goals</h1>
        <div class="add-goal-button" onclick="showInputForm()" style="color: orange;">+</div>
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
    echo '<tr><th>Set Achieved or Delete</th><th>Goal ID</th><th>Description</th><th>Target Date</th><th>User ID</th><th>Action</th></tr>';

    $rowIndex = 0;
    while ($row = oci_fetch_assoc($stmt)) {
        echo "<form id='update-form' method='post' action='action.php'>";
        // Skip rendering the row if goal has been achieved
        if ($row['ACHIEVED'] == 1) {
            continue;
        }
        echo '<tr>';
        echo '<td><input type="checkbox" name="goals[]" value="' . $row['GOALID'] . '"></td>';
        echo '<td>' . $row['GOALID'] . '</td>';
        echo '<td>' . $row['DESCRIPTION'] . '</td>';
        echo '<td>' . $row['TARGETDATE'] . '</td>';
        echo '<td>' . $row['USERID'] . '</td>';
        echo "<td><button type='button' class='edit-button' data-row-index='$rowIndex'>Edit</button>";
        echo "<button type='submit' name='achieved' class='ach-del-button'>Achieve</button>";
        echo "<button type='submit' name='delete' value='$rowIndex' class='ach-del-button'>Delete</button></td>";
        echo '</tr>';

        echo "<tr class='update-row' style='display: none;'>
            <td>&nbsp</td>
            <td>&nbsp</td>
            <td><input type='text' name='update_list[\"DESCRIPTION\"]'></td>
            <td><input type='text' name='update_list[\"TARGETDATE\"]'></td>
            <td>&nbsp</td>
            <td>
                <button type='submit'>Update</button>
                <button type='button' class='cancel-button'>Cancel</button>
            </td>
        </tr>";
    $rowIndex++;
    }

    echo "</form>";

    echo '<form id="add-del" method="post" action="">'; // Add form element for add & delete functionality
    // Display input form row (last row) if '+' button is clicked
    echo '<tr id="form-row" class="hidden-row">';
    echo '<td>&nbsp</td>';
    echo '<td>&nbsp</td>';
    echo '<td><input type="text" name="description" placeholder="Enter goal description" style="color: #5D3FD3;"></td>';
    echo '<td><input type="text" name="targetDate" placeholder="Enter target date" style="color: #5D3FD3;"></td>';
    echo '<td><input type="number" name="userID" placeholder="Enter user ID" style="color: #5D3FD3;"></td>';
    echo '<td colspan="2">';
    echo '<input form="" type="submit" name="submit" value="Add" style="background-color: #5D3FD3; color: #fff;"></td>';
    echo '</td>';
    echo '</tr>';

    echo '</table>';

    // Add delete and achieve buttons

    echo '</form>'; // Close the form element

    // Handle form submission
    if (isset($_POST['submit']) && !isset($_POST['update_list'])) {
        $description = $_POST['description'];
        $targetDate = $_POST['targetDate'];
        $userID = (int) $_POST['userID'];
    
        // Check if the user ID exists
        $checkQuery = "SELECT COUNT(*) AS USER_COUNT FROM \"User\" WHERE ID = :userID";
        $checkStmt = oci_parse($db_conn, $checkQuery);
        oci_bind_by_name($checkStmt, ":userID", $userID);
        oci_execute($checkStmt);
        $row = oci_fetch_assoc($checkStmt);
        $userCount = (int) $row['USER_COUNT'];
    
        if ($userCount > 0) {
            // Insert goal in User_FitnessGoal table
            $insertQuery = "INSERT INTO User_FitnessGoal (DESCRIPTION, TARGETDATE, USERID) VALUES (:description, :targetDate, :userID)";
            $insertStmt = oci_parse($db_conn, $insertQuery);
            oci_bind_by_name($insertStmt, ":description", $description);
            oci_bind_by_name($insertStmt, ":targetDate", $targetDate);
            oci_bind_by_name($insertStmt, ":userID", $userID);
            oci_execute($insertStmt);
    
            echo '<script>window.location.href = window.location.href;</script>';
            exit();
        } else {
            echo '<div class="error-message">Invalid user ID. Please enter a valid user ID.</div>';
        }   
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
        echo '<script>window.location.href = window.location.href;</script>';
        exit();        

    } else if (isset($_POST['delete'])) {
        $selectedGoals = isset($_POST['goals']) ? $_POST['goals'] : [];

        foreach ($selectedGoals as $goalId) {
            // Delete the goal from User_FitnessGoal table
            $deleteQuery = "DELETE FROM User_FitnessGoal WHERE goalID = :goalId";
            $deleteStmt = oci_parse($db_conn, $deleteQuery);
            oci_bind_by_name($deleteStmt, ":goalId", $goalId);
            oci_execute($deleteStmt);
        }
        echo '<script>window.location.href = window.location.href;</script>';
        exit();
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

        // Add event listeners to the edit buttons and cancel buttons
        const editButtons = document.querySelectorAll('.edit-button');
        const cancelButtons = document.querySelectorAll('.cancel-button');
        const updateRows = document.querySelectorAll('.update-row');

        // Allow each edit button to reveal the hidden row
        editButtons.forEach((button, index) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const rowIndex = button.getAttribute('data-row-index');
                updateRows[rowIndex].style.display = 'table-row';
            });
        });

        // Allow each cancel button to hide the row
        cancelButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                updateRows[index].style.display = 'none';
            });
        });
    </script>

</body>
</html>
