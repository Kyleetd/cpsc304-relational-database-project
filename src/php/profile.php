<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
    .button {
        display: inline-block;
        background-color: #BF40BF;
        width: auto; 
        height: 30px;
        line-height: 30px;
        text-align: center;
        background-color: #f2f2f2;
        border: 2px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: orange;
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
    .add-user-button {
        display: inline-block;
        width: auto; 
        height: 30px;
        line-height: 30px;
        text-align: center;
        background-color: #f2f2f2;
        border: 2px solid orange;
        border-radius: 5px;
        cursor: pointer;
        background-color: #BF40BF;
        color: orange; 
    }
    .hidden-row {
        display: none;
    }
    #filter-line {
        text-align: center;
        margin: 20px 0;
    }
    #filter-dropdown {
        margin: 0 5px;
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
        <h1>Profile: Users and Measurements</h1>
        <div class="button-container">
            <div class="button add-user-button" onclick="showInputForm()">Add User</div>
            <div class="button" id="join" onclick="openAverageBMI()">Compute users with average BMI < overall average BMI</div>
        </div>
        <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/dashboard.php" class="back-button">Back</a>
</div>

<div id="filter-line">
    <form method="post">
        COUNT USERS HAVING BMI
        >
        <input type="number" id="find-count" name="find-count" placeholder="Enter BMI value" min="0">
        <button type="submit" name="find-count-submit">Find Count</button>
    </form>
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
$query = 'SELECT User_Measurement.userID, Users.name, User_Measurement.height, User_Measurement.weight, User_Measurement.BMI
          FROM Users, User_Measurement
          WHERE User_Measurement.userID = Users.ID';
$stmt = oci_parse($db_conn, $query);
oci_execute($stmt);

// Display the table
echo '<form method="post" action="">';
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

// Display input form row (last row) if '+' button is clicked
echo '<tr id="form-row" class="hidden-row">';
echo '<td><input type="number" name="userID" placeholder="Enter user ID"></td>';
echo '<td><input type="text" name="name" placeholder="Enter name"></td>';
echo '<td><input type="number" name="height" placeholder="Enter height"></td>';
echo '<td><input type="number" name="weight" placeholder="Enter weight"></td>';
echo '<td><input type="text" name="BMI" placeholder="Enter BMI"></td>';
echo '<td colspan="2">';
echo '<input type="submit" name="submit" value="Add">';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</form>'; 

// Handle form submission
if (isset($_POST['submit'])) {
    $userID = $_POST['userID'];
    $name = $_POST['name'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $BMI = $_POST['BMI'];

    // Insert User in User_Measurement table
    $insertQuery = "INSERT INTO User_Measurement (HEIGHT, WEIGHT, BMI, USERID) VALUES (:height, :weight, :BMI, :userID)";
    $insertStmt = oci_parse($db_conn, $insertQuery);
    oci_bind_by_name($insertStmt, ":height", $height);
    oci_bind_by_name($insertStmt, ":weight", $weight);
    oci_bind_by_name($insertStmt, ":BMI", $BMI);
    oci_bind_by_name($insertStmt, ":userID", $userID);
    oci_execute($insertStmt);

    // // Insert User in User table
    $insertQuery = "INSERT INTO User (ID, NAME) VALUES (:ID, :name)";
    $insertStmt = oci_parse($db_conn, $insertQuery);
    oci_bind_by_name($insertStmt, ":ID", $userID);
    oci_bind_by_name($insertStmt, ":name", $name);
    oci_execute($insertStmt);

    // Refresh table
     echo '<script>window.location.href = window.location.href;</script>';
     exit();
} else if (isset($_POST['find-count-submit'])) {

    // Get the BMI input value
    $BMIValue = trim($_POST['find-count']);

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

    // Check if a result was fetched
    if ($count === false) {
        echo "No users found with a BMI over " . $BMIValue;
    } else {
        echo "Count of Users having BMI over " . $BMIValue . " is: " . $count['USER_COUNT'];
    }

    // Clean up the statement
    oci_free_statement($countStmt);
    exit();
}
		
// Close the database connection	
oci_free_statement($stmt);	
oci_close($db_conn);

?>

<script>
    function showInputForm() {
        var formRow = document.getElementById('form-row');
        formRow.style.display = 'table-row';
    }
    function openAverageBMI() {
        window.open("https://www.students.cs.ubc.ca/~gargkash/project_j4i5v_j7r8j_r6z9i/src/php/averageBMI.php", "_blank");
    }
</script>

</body>
</html>
