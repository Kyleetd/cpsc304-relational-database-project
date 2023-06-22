<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym</title>
</head>

<style>
body {
    background-image: url('https://i.pinimg.com/564x/a9/80/22/a98022cdb8b339e11542132b6428ac92.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
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
.add-goal-button {
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
.gyms-per-country-button {
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
.all-gyms-button {
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
.apply-filter-button {
    display: inline-block;
    width: auto; 
    height: 25px;
    line-height: 15px;
    text-align: center;
    background-color: #f2f2f2;
    border: 2px solid orange;
    border-radius: 5px;
    cursor: pointer;
    background-color: #BF40BF;
    color: orange; 
}
.reset-button {
    display: inline-block;
    width: auto; 
    height: 25px;
    line-height: 15px;
    text-align: center;
    background-color: #f2f2f2;
    border: 2px solid #ddd;
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
.button-container-back-reset {
    position: fixed;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: row;
}
.back-button {
    font-family: Arial, sans-serif;
    position: absolute;
    top: 10px;
    left: 0px;
    padding: 1px 3px;
    background-color: #BF40BF;
    border: 1px solid orange;
    border-radius: 3px;
    text-decoration: none;
    color: #333;
    font-size: 15px;
    width: 43px; 
    color: orange; 
}
.button-container {
    /* Add container styles if necessary */
}
.button-container input[type="submit"] {
    font-family: Arial, sans-serif;
    background-color: #BF40BF;
    border: 1px solid orange;
    border-radius: 3px;
    text-decoration: none;
    color: #333;
    font-size: 15px;
    width: 43px; 
    color: orange; 
  }
</style>

<body>
<form method="post" action="">
  <div class="header">
    <h1>Joint Table: Gyms, PCC, and User</h1>
    <div class="button-container">
      <div class="button add-goal-button" onclick="showInputForm()">Add Gym</div>
      <div class="button gyms-per-country-button" id="join" onclick="openNumberOfGymsPerCountry()">Compute Number of Gyms per Country</div>
      <div class="button all-gyms-button" id="join" onclick="openUsersAttendingAllGyms()">Find Users Attending all Gyms</div>
    </div>
    <div class="button-container-back-reset">
      <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/dashboard.php" class="back-button">Back</a>
    </div>
  </div>
</form>

<div id="filter-line">
    <form method="post">
        <span style="color: purple;">JOIN WHERE</span>
        <select id="filter-dropdown" name="filter-dropdown" style="background-color: orange;">
            <option value="address">Address</option>
            <option value="postalCode">Postal Code</option>
            <option value="city">City</option>
            <option value="name">Name</option>
            <option value="country">Country</option>
            <option value="userID">UserID</option>
        </select>
        <span style="color: purple;">=</span>
        <input type="text" id="filter-input" name="filter-input" placeholder="Enter value" style="background-color: orange;">
        <button type="submit" name="apply_filter" class="apply-filter-button">Apply Filter</button>
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
$query = "SELECT Gym.address, Gym.postalCode, PCC.country, Gym.city, Gym.name, Attends.userID
          FROM Gym
          LEFT JOIN Attends ON Gym.address = Attends.address AND Gym.postalCode = Attends.postalCode
          LEFT JOIN PCC ON Gym.postalCode = PCC.postalCode";
$stmt = oci_parse($db_conn, $query);
oci_execute($stmt);

// Display the table
echo '<form method="post" action="">';
echo '<table>';
echo '<tr><th>Address</th><th>Postal Code</th><th>City</th><th>Name</th><th>Country</th><th>UserID</th></tr>';

while ($row = oci_fetch_assoc($stmt)) {
    echo '<tr>';
    echo '<td data-column="address">'.$row['ADDRESS'].'</td>';
    echo '<td data-column="postalCode">'.$row['POSTALCODE'].'</td>';
    echo '<td data-column="city">'.$row['CITY'].'</td>';
    echo '<td data-column="name">'.$row['NAME'].'</td>';
    echo '<td data-column="country">'.$row['COUNTRY'].'</td>';
    echo '<td data-column="userID">'.$row['USERID'].'</td>';
    echo '</tr>';
}

// Display input form row (last row) if '+' button is clicked
echo '<tr id="form-row" class="hidden-row">';
echo '<td><input type="text" name="address" placeholder="Enter address"></td>';
echo '<td><input type="text" name="postalCode" placeholder="Enter Postal Code"></td>';
echo '<td><input type="text" name="city" placeholder="Enter city"></td>';
echo '<td><input type="text" name="name" placeholder="Enter gym name"></td>';
echo '<td><input type="text" name="country" placeholder="Enter country"></td>';
echo '<td><input type="number" name="userID" placeholder="Enter user ID"></td>';
echo '<td colspan="2">';
echo '<div class="button-container"><input type="submit" name="submit" value="Add"></div>';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</form>'; 

// Handle form submission
if (isset($_POST['submit'])) {
    $address = $_POST['address'];
    $postalCode = $_POST['postalCode'];
    $city = $_POST['city'];
    $name = $_POST['name'];
    $country = $_POST['country'];
    $userID = (int) $_POST['userID'];

    // Insert Gym in Gym table
    $insertQuery = "INSERT INTO Gym (ADDRESS, POSTALCODE, CITY, NAME) VALUES (:address, :postalCode, :city, :name)";
    $insertStmt = oci_parse($db_conn, $insertQuery);
    oci_bind_by_name($insertStmt, ":address", $address);
    oci_bind_by_name($insertStmt, ":postalCode", $postalCode);
    oci_bind_by_name($insertStmt, ":city", $city);
    oci_bind_by_name($insertStmt, ":name", $name);
    oci_execute($insertStmt);

    // Insert Gym in PCC table
    $insertQuery = "INSERT INTO PCC (POSTALCODE, COUNTRY) VALUES (:postalCode, :country)";
    $insertStmt = oci_parse($db_conn, $insertQuery);
    oci_bind_by_name($insertStmt, ":postalCode", $postalCode);
    oci_bind_by_name($insertStmt, ":country", $country);
    oci_execute($insertStmt);

    // Insert Gym in Attends table
    $insertQuery = "INSERT INTO Attends (ADDRESS, POSTALCODE, USERID) VALUES (:address, :postalCode, :userID)";
    $insertStmt = oci_parse($db_conn, $insertQuery);
    oci_bind_by_name($insertStmt, ":address", $address);
    oci_bind_by_name($insertStmt, ":postalCode", $postalCode);
    oci_bind_by_name($insertStmt, ":userID", $userID);
    oci_execute($insertStmt);

    // Refresh the page
    echo '<script>window.location.href = window.location.href;</script>';
    exit();
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

     // Add event handlers for buttons
     echo '<button type="submit" name="reset_filter" value="reset" class="reset-button">Reset Filter</button>';

     echo '</form>';

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
    function openNumberOfGymsPerCountry() {
        window.open("https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/numberOfGymsPerCountry.php", "_blank");
    }
    function openUsersAttendingAllGyms() {
        window.open("https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/allUsersAllGyms.php", "_blank");
    }
    function resetTable() {
        // Restore the original table HTML
        location.reload();
    }
</script>

</body>
</html>
