<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievements</title>
    <style>
    body {
        background-image: url('https://i.pinimg.com/564x/01/03/21/010321446051fb8a97b258436c7f0e8f.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
    .header {
        text-align: center;
        font-size: 25px;
        padding: 10px;
        text-shadow: 2px 2px 4px #5D3FD3;
    }
    .header h1 {
        color: #FFB74D; 
    }
    table {
        margin: auto;
        border-collapse: collapse;
        width: 80%;
        background-color: #8D6CE9; 
    }
    th, td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid orange;
        color: #FFB74D; 
    }
    .back-button {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 1px 3px;
        background-color: #FFB74D; 
        border: 1px solid #8D6CE9; 
        border-radius: 3px;
        text-decoration: none;
        color: #8D6CE9; 
        font-size: 20px;
        text-shadow: none;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>Fitness Achievements</h1>
        <a href="./goalsAndAchievements.php" class="back-button">Back</a>
    </div>

    <?php
    // Establish a connection to the Oracle database
    $db_conn = OCILogon("ora_kyleetd", "a78242021", "dbhost.students.cs.ubc.ca:1522/stu");

    // Check if the connection was successful
    if (!$db_conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    // Define & execute the SQL query
    $query = "SELECT * FROM User_Achievement";
    $stmt = oci_parse($db_conn, $query);
    oci_execute($stmt);

    // Display the table
    echo '<table>';
    echo '<tr><th>Achievement ID</th><th>Description</th><th>Date Accomplished</th><th>User ID</th><th>Goal ID</th></tr>';

    while ($row = oci_fetch_assoc($stmt)) {
        echo '<tr>';
        echo '<td>' . $row['ACHIEVEMENTID'] . '</td>';
        echo '<td>' . $row['DESCRIPTION'] . '</td>';
        echo '<td>' . $row['DATEACCOMPLISHED'] . '</td>';
        echo '<td>' . $row['USERID'] . '</td>';
        echo '<td>' . $row['GOALID'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';

    // Close the database connection
    oci_close($db_conn);
    ?>
</body>

</html>
