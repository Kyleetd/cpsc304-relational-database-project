<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <link rel="stylesheet" type="text/css" href="../css/goalsAndAchievements.css" /> -->
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

?>

	</body>
</html>