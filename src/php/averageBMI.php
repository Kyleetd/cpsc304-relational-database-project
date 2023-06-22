<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users with average BMI < overall average BMI</title>
</head>

<style>
    body {
        background-image: url('https://i.pinimg.com/564x/a9/80/22/a98022cdb8b339e11542132b6428ac92.jpg');
        background-repeat: no-repeat;
        background-size: cover;
    }
    .header {
        text-align: center;
        font-size: 25px;
        padding: 10px;
        background-color: transparent;
        color: orange; 
        text-shadow: 2px 2px 4px #5D3FD3;
    }
    .button-container {
    display: inline-block;
    vertical-align: middle;
    }
    table {
        margin: auto;
        border-collapse: collapse;
        width: 80%;
        background-color: #BF40BF; 
    }
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid orange;
        color: orange; 
    }
    .back-button {
        display: inline-block;
        width: auto; 
        top: 10px;
        left: 0px;
        height: 30px;
        line-height: 30px;
        background-color: #f2f2f2;
        border: 2px solid orange;
        border-radius: 5px;
        cursor: pointer;
        background-color: #BF40BF;
        color: orange; 
    }
</style>

<body>
<div class="header">
        <h1>Users with average BMI < overall average BMI</h1>
        <a href="./profile.php" class="back-button">Back</a>
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

// Define the SQL query with the JOIN to Users table
$query = "SELECT t.userID, u.name, t.average
          FROM Temp t
          JOIN Users u ON t.userID = u.ID
          WHERE t.average < (SELECT AVG(Temp.average) FROM TEMP)";

$stmt = oci_parse($db_conn, $query);
oci_execute($stmt);

// Display the table
echo '<table>';
echo '<tr><th>UserID</th><th>Name</th><th>Average</th>';

while ($row = oci_fetch_assoc($stmt)) {
    echo '<tr>';
    echo '<td data-column="userID">'.$row['USERID'].'</td>';
    echo '<td data-column="name">'.$row['NAME'].'</td>';
    echo '<td data-column="average">'.$row['AVERAGE'].'</td>';
    echo '</tr>';
}

echo '</table>';

// Calculate and display the overall average of BMIs
$overallAverageQuery = "SELECT AVG(average) AS overall_average FROM TEMP";
$overallAverageStmt = oci_parse($db_conn, $overallAverageQuery);
oci_execute($overallAverageStmt);
$overallAverageRow = oci_fetch_assoc($overallAverageStmt);

echo '<p style="color: orange; text-align: center; font-size: 40px;">Overall Average of BMIs: ' . $overallAverageRow['OVERALL_AVERAGE'] . '</p>';

// Close the database connection
oci_free_statement($stmt);
oci_close($db_conn);
?>
    
</body>
</html>