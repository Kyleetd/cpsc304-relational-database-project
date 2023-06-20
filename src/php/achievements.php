<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Achievements</title>
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
    </style>

    <div class="header">
        <h1>Fitness Achievements</h1>
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
    $query = "SELECT * FROM User_Achievement";
    $stmt = oci_parse($db_conn, $query);
    oci_execute($stmt);

     // Display the table
     echo '<table>';
     echo '<tr><th>Achievement ID</th><th>Description</th><th>Date Accomplished</th><th>User ID</th><th>Goal ID</th></tr>';

     while ($row = oci_fetch_assoc($stmt)) {
        echo '<tr>';
        echo '<td>'.$row['ACHIEVEMENTID'].'</td>';
        echo '<td>'.$row['DESCRIPTION'].'</td>';
        echo '<td>'.$row['DATEACCOMPLISHED'].'</td>';
        echo '<td>'.$row['USERID'].'</td>';
        echo '<td>'.$row['GOALID'].'</td>';
        echo '</tr>';
    }
    echo '</table>';

    // Close the database connection

    oci_close($db_conn);

    ?>

    </body>
</html>