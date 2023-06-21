<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>

<style>
    .header {
        text-align: center;
        font-size: 25px;
        padding: 10px;
        background-color: #f2f2f2;
    }
    .button-container {
        display: inline-block;
        vertical-align: middle;
    }
    .button {
        display: inline-block;
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
    .add-user-button {
        display: inline-block;
        width: auto; 
        height: 30px;
        line-height: 30px;
        text-align: center;
        background-color: #f2f2f2;
        border: 2px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
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
            <div class="button" id="join" onclick="openAverageBMI()">Find users with average BMI < overall average BMI</div>
        </div>
        <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/dashboard.php" class="back-button">Back</a>
</div>

<div id="filter-line">
    <form method="post">
        COUNT USERS HAVING BMI
        >
        <input type="text" id="filter-input" name="filter-input" placeholder="Enter BMI value">
        <button type="submit" name="apply_filter">Find Count</button>
    </form>
</div>

<?php
// Establish a connection to the Oracle database
$db_conn = OCILogon("ora_gargkash", "a89601264", "dbhost.students.cs.ubc.ca:1522/stu");

// Check if the connection was successful
if (!$db_conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// define & execute the SQL query
$query = "SELECT User_Measurement.userID, User.name, User_Measurement.height, User_Measurement.weight, User_Measurement.BMI
          FROM User_Measurement, User
          WHERE User_Measurement.userID = User.ID";
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
echo '<td><input type="number" name="BMI" placeholder="Enter BMI"></td>';
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

    // Insert User in User table
    $insertQuery = "INSERT INTO User (ID, NAME) VALUES (:ID, :name)";
    $insertStmt = oci_parse($db_conn, $insertQuery);
    oci_bind_by_name($insertStmt, ":ID", $userID);
    oci_bind_by_name($insertStmt, ":name", $name);
    oci_execute($insertStmt);

    // Refresh table
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '    var formRow = document.getElementById("form-row");';
    echo '    formRow.style.display = "none";';
    echo '    var tableBody = document.querySelector("table tbody");';
    echo '    tableBody.innerHTML = `' . $tableRows . '`;';
    echo '});';
    echo '</script>';
} else if (isset($_POST['apply_filter'])) {

    // Get the filter input value
    $filterDropdown = $_POST['filter-dropdown'];
    $filterValue = trim(strtolower($_POST['filter-input']));

    // Create a view for the joinedAll table
    $viewQuery = "CREATE VIEW joinedAll AS
                SELECT Gym.address, Gym.postalCode, PCC.country, Gym.city, Gym.name, Attends.userID
                FROM Gym
                LEFT JOIN Attends ON Gym.address = Attends.address AND Gym.postalCode = Attends.postalCode
                LEFT JOIN PCC ON Gym.postalCode = PCC.postalCode";

    // Execute the view creation query
    $createViewStmt = oci_parse($db_conn, $viewQuery);
    oci_execute($createViewStmt);

    // Perform a separate query on the view
    $filterQuery = "SELECT address, postalCode, country, city, name, userID
                    FROM joinedAll
                    WHERE LOWER(" . $filterDropdown . ") = :filterValue";

    $filterStmt = oci_parse($db_conn, $filterQuery);
    oci_bind_by_name($filterStmt, ":filterValue", $filterValue);
    oci_bind_by_name($filterStmt, ":filterDropdown", $filterDropdown);
    oci_execute($filterStmt);    

    // Fetch all rows from the executed statement into an array
    $rows = oci_fetch_all($stmt, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    // Display the filtered table
    echo '<form method="post" action="">';
    echo '<table>';
    echo '<tr><th>Address</th><th>Postal Code</th><th>City</th><th>Name</th><th>Country</th><th>UserID</th></tr>';

    while ($row = oci_fetch_assoc($filterStmt)) {
        echo '<tr>';
        echo '<td data-column="address">' . $row['ADDRESS'] . '</td>';
        echo '<td data-column="postalCode">' . $row['POSTALCODE'] . '</td>';
        echo '<td data-column="city">' . $row['CITY'] . '</td>';
        echo '<td data-column="name">' . $row['NAME'] . '</td>';
        echo '<td data-column="country">' . $row['COUNTRY'] . '</td>';
        echo '<td data-column="userID">' . $row['USERID'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '</form>';

    // Refresh table
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '    var formRow = document.getElementById("form-row");';
    echo '    formRow.style.display = "none";';
    echo '    var tableBody = document.querySelector("table tbody");';
    echo '    tableBody.innerHTML = `' . $tableRows . '`;';
    echo '});';
    echo '</script>';
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
    function openNumberOfGymsPerCountry() {
        window.open("https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/numberOfGymsPerCountry.php", "_blank");
    }
</script>

</body>
</html>
