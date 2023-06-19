<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Goals</title>
    </head>

    <body>
        <h2>Add a Fitness Goal</h2>
        <form method="POST" action="Goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Goal ID: <input type="number" name="goalID"> <br /><br />
            Description: <input type="text" name="description"> <br /><br />
            Target Date: <input type="text" name="targetDate"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Mark a Goal as Achieved</h2>

        <form method="POST" action="Goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="goalAchievedRequest" name="goalAchievedRequest">
            Goal ID: <input type="text" name="goalID"> <br /><br />
            Date Achieved: <input type="text" name="dateAchieved"> <br /><br />

            <input type="submit" value="Mark Achieved" name="goalAchievedSubmit"></p>
        </form>

        <hr />

        <h2>Display Goals</h2>
        <form method="GET" action="Goals.php">
            <input type="hidden" id="displayGoalsRequest" name="displayGoalsRequest">
            <input type="submit" value="Display Goals" name="displayGoals">
        </form>

<?php

// Establish a connection to the Oracle database
$db_conn = OCILogon("ora_kyleetd", "a78242021", "dbhost.students.cs.ubc.ca:1522/stu");

// Check if the connection was successful
if ($db_conn) {
    
    // Drop the "GOALSTABLE" table
    $query = "DROP TABLE GOALSTABLE";
    $stmt = oci_parse($db_conn, $query);
    oci_execute($stmt);
    
    // Execute the SQL query to retrieve table names
    $query = "SELECT table_name FROM user_tables";
    $stmt = oci_parse($db_conn, $query);
    oci_execute($stmt);

    // Fetch and display the table names
    echo "<h1>Tables in the Database:</h1>";
    while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
        echo "<p>{$row['TABLE_NAME']}</p>";
    }

    // Free the statement and close the connection
    oci_free_statement($stmt);
    oci_close($db_conn);
} else {
    // Display an error message if the connection failed
    echo "<h1>Failed to connect to the database.</h1>";
}

function executePlainSQL($cmdstr) {
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function executeBoundSQL($cmdstr, $list) {
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}

function printResult($result) { //prints results from a select statement
    echo "<br>Retrieved data from table User_FitnessGoal:<br>";
    echo "<table>";
    echo "<tr><th>goalID</th><th>Description</th><th>targetDate</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["goalID"] . "</td><td>" . $row["Description"] . "</td><td>" . $row["targetDate"] . "</td></tr>";
    }

    echo "</table>";
}

function connectToDB() {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_kyleetd", "a78242021", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

?>

	</body>
</html>