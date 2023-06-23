<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users Who Attend All Gyms</title>
</head>

<style>
    .header {
        text-align: center;
        font-size: 25px;
        padding: 10px;
        background-color: transparent;
    }
    .table-container {
        display: flex;
        justify-content: center;
    }
    table {
        border-collapse: collapse;
        width: 80%;
        text-align: center;
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
        <h1 style="color: orange; text-shadow: 2px 2px 4px #5D3FD3;">All Users Who Attend All Gyms</h1>
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
$query = "SELECT DISTINCT(U.ID) AS U_ID, U.name AS U_NAME
          FROM \"User\" U
          JOIN Attends A ON U.ID = A.userID
          JOIN Gym G ON A.address = G.address AND A.postalCode = G.postalCode
          WHERE NOT EXISTS (
              SELECT G1.address, G1.postalCode
              FROM Gym G1
              WHERE NOT EXISTS (
                  SELECT A1.address, A1.postalCode
                  FROM Attends A1
                  WHERE A1.userID = U.ID
                  AND A1.address = G1.address
                  AND A1.postalCode = G1.postalCode
              )
          )";
$stmt = oci_parse($db_conn, $query);
oci_execute($stmt);
?>

<div class="table-container">
    <table>
        <tr>
            <th>UserID</th>
            <th>Name</th>
        </tr>

        <?php
        while ($row = oci_fetch_assoc($stmt)) {
            echo '<tr>';
            echo '<td data-column="ID">' . $row['U_ID'] . '</td>';
            echo '<td data-column="name">' . $row['U_NAME'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>
    
</body>
</html>