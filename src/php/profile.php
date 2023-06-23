<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/profile.css" />
    <title>Profile</title>
</head>

<body>
<div class="header">
        <h1>Profile: Users and Measurements</h1>
        <div class="button-container">
            <div class="button add-user-button" onclick="showInputForm()">Add User</div>
            <div class="button bmi-button" id="join" onclick="openAverageBMI()">Compute users with average BMI < overall average BMI</div>
        </div>
        <a href="./dashboard.php" class="back-button">Back</a>
</div>

<div id="filter-line">
  <div class="center-content">
    <span class="count-text">COUNT USERS HAVING BMI > </span>
    <input type="number" id="find-count" name="find-count" placeholder="Enter BMI value" min="0">
    <button type="button" class="find-count-button" onclick="handleFindCount()">Find Count</button>
  </div>
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

    // // Insert User in Users table
    $insertQuery = "INSERT INTO Users (ID, NAME) VALUES (:ID, :name)";
    $insertStmt = oci_parse($db_conn, $insertQuery);
    oci_bind_by_name($insertStmt, ":ID", $userID);
    oci_bind_by_name($insertStmt, ":name", $name);
    oci_execute($insertStmt);

    // Refresh table
     echo '<script>window.location.href = window.location.href;</script>';
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
    function handleFindCount() {
        var BMIValue = document.getElementById('find-count').value;
        var url = "https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/numUsersBMI.php?BMI=" + BMIValue;
        window.open(url, "_blank");
    }
</script>

</body>
</html>