<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users with average BMI < overall average BMI</title>
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
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
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
        <h1>Users with average BMI < overall average BMI</h1>
        <a href="https://www.students.cs.ubc.ca/~gargkash/project_j4i5v_j7r8j_r6z9i/src/php/profile.php" class="back-button">Back</a>
</div>

<?php

// Establish a connection to the Oracle database
$db_conn = OCILogon("ora_gargkash", "a89601264", "dbhost.students.cs.ubc.ca:1522/stu");

// Check if the connection was successful
if (!$db_conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Create a TEMP view
$tempQuery = "CREATE VIEW TEMP(userID, average) AS
              SELECT u.userID, AVG(u.bmi) AS average
              FROM User_Measurement u
              GROUP BY userID";

// Execute the view creation query
$createTempViewStmt = oci_parse($db_conn, $tempQuery);
oci_execute($createTempViewStmt);

// define & execute the SQL query
$query = "SELECT userID, average
          FROM Temp
          WHERE average < (SELECT AVG(Temp.average) FROM TEMP)";
$stmt = oci_parse($db_conn, $query);
oci_execute($stmt);

// Display the table
echo '<table>';
echo '<tr><th>UserID</th><th>Name</th><th>Height</th><th>Weight</th><th>BMI</th><th></th></tr>';

while ($row = oci_fetch_assoc($stmt)) {
    echo '<tr>';
    echo '<td data-column="userID">'.$row['USERID'].'</td>';
    echo '<td data-column="name">'.$row['NAME'].'</td>';
    echo '<td data-column="height">'.$row['HEIGHT'].'</td>';
    echo '<td data-column="weight">'.$row['WEIGHT'].'</td>';
    echo '<td data-column="BMI">'.$row['BMI'].'</td>';
    echo '</tr>';
}

echo '</table>';

// Close the database connection
oci_free_statement($stmt);
oci_close($db_conn);
?>
    
</body>
</html>