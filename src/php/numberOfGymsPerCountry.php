<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number of Gyms Per Country</title>
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
        background-color: #5D3FD3; 
        }
    th, td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid orange;
        color: orange; 
    }
    .back-button {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 1px 3px;
        background-color: orange;
        border: 1px solid #5D3FD3;
        border-radius: 3px;
        text-decoration: none;
        color: #5D3FD3;
        font-size: 20px;
    }
    body {
        background-image: url("https://i.pinimg.com/564x/b9/16/99/b91699c52243770ce558b9035658e852.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        margin: 0;
        height: 100vh; 
    }
</style>

<body>
<div class="header">
        <h1 style="color: orange; text-shadow: 2px 2px 4px #5D3FD3;">Number of Gyms Per Country</h1>
        <a href="./gym.php" class="back-button">Back</a>
</div>

<?php

// Establish a connection to the Oracle database
$db_conn = OCILogon("ora_kyleetd", "a78242021", "dbhost.students.cs.ubc.ca:1522/stu");

// Check if the connection was successful
if (!$db_conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// define & execute the SQL query
$query = "SELECT PCC.country, COUNT(*)
          FROM PCC
          GROUP BY PCC.country";
$stmt = oci_parse($db_conn, $query);
oci_execute($stmt);

// Display the table
echo '<table>';
echo '<tr><th>Country</th><th>Number of Gyms</th></tr>';

while ($row = oci_fetch_assoc($stmt)) {
    echo '<tr>';
    echo '<td>'.$row['COUNTRY'].'</td>';
    echo '<td>'.$row['COUNT(*)'].'</td>';
    echo '</tr>';
}

echo '</table>';

// Close the database connection
oci_free_statement($stmt);
oci_close($db_conn);
?>
    
</body>
</html>