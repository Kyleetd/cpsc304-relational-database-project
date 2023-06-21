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
        background-color: #f2f2f2;
    }
    .table-container {
        display: flex;
        justify-content: center;
    }
    table {
        border-collapse: collapse;
        width: 80%;
        text-align: center;
    }
    th, td {
        padding: 8px;
        text-align: center;
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
        <h1>Users who Attend Every Gym</h1>
        <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/gym.php" class="back-button">Back</a>
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