<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Having Average BMI Greater</title>
</head>
<body>
<style>
body {
    background-image: url('https://i.pinimg.com/564x/01/ff/b0/01ffb043ba4a7d9c46691444e61dbd30.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.center-content {
    text-align: center;
}

.big-text {
    font-size: 24px;
}

.purple-box {
    background-color: #E0B0FF;
    padding: 10px;
    display: inline-block;
}

.container {
    background-color: #E0B0FF;
    padding: 20px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}

.container p {
    margin: 0;
    padding: 10px;
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
</style>

<body>
    <a href="./profile.php" class="back-button">Back</a>
</div>

<?php
// Establish a connection to the Oracle database
$db_conn = OCILogon("ora_kyleetd", "a78242021", "dbhost.students.cs.ubc.ca:1522/stu");

// Check if the connection was successful
if (!$db_conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Get the BMI input value
$BMIValue = $_GET['BMI'];

// Perform a filter query 
$countQuery = "SELECT COUNT(*) AS user_count
            FROM (
                    SELECT Users.ID
                    FROM Users
                    JOIN User_Measurement ON User_Measurement.userID = Users.ID
                    GROUP BY Users.ID
                    HAVING MAX(User_Measurement.BMI) > :BMIValue
            ) subquery";

// Prepare the query statement
$countStmt = oci_parse($db_conn, $countQuery);
oci_bind_by_name($countStmt, ":BMIValue", $BMIValue);

// Execute the query
oci_execute($countStmt);

$count = oci_fetch_assoc($countStmt);

if ($count === false) {
    echo '<div class="container">
              <p class="big-text">Count of Users having BMI over</p>
              <p class="big-text purple-box">' . $BMIValue . '</p>
              <p class="big-text">is:</p>
              <p class="big-text purple-box">0</p>
          </div>';
} else {
    echo '<div class="container">
              <p class="big-text">Count of Users having BMI over </p>
              <p class="big-text purple-box">' . $BMIValue . '</p>
              <p class="big-text">is:</p>
              <p class="big-text purple-box">' . $count['USER_COUNT'] . '</p>
          </div>';
}
?>
    
</body>
</html>
