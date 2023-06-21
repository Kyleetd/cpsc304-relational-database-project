<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym</title>
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
    .add-goal-button {
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
        <h1>Joint Table: Gyms, PCC, and User</h1>
        <div class="button-container">
            <div class="button add-goal-button" onclick="showInputForm()">Add Gym</div>
            <div class="button" id="join" onclick="openNumberOfGymsPerCountry()">Compute Number of Gyms per Country</div>
        </div>
        <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/dashboard.php" class="back-button">Back</a>
</div>

<div id="filter-line">
    JOIN WHERE
    <select id="filter-dropdown">
        <option value="address">Address</option>
        <option value="postalCode">Postal Code</option>
        <option value="city">City</option>
        <option value="name">Name</option>
        <option value="country">Country</option>
        <option value="userID">UserID</option>
    </select>
    =
    <input type="text" id="filter-input" placeholder="Enter value">
    <button onclick="applyFilter()">Apply Filter</button>
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
echo '<table>';
echo '<tr><th>Address</th><th>Postal Code</th><th>City</th><th>Name</th><th>Country</th><th>UserID</th></tr>';

echo '<form method="post" action="">';

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
echo '<input type="submit" name="submit" value="Add">';
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

    // Refresh table
    header("Refresh:0");
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
    function applyFilter() {
        var filterDropdown = document.getElementById("filter-dropdown");
        var filterInput = document.getElementById("filter-input");

        var filterColumn = filterDropdown.value;
        var filterValue = filterInput.value.trim().toLowerCase();

        var table = document.getElementsByTagName("table")[0];
        var rows = table.getElementsByTagName("tr");

        for (var i = 1; i < rows.length; i++) {
            var cells = rows[i].getElementsByTagName("td");

            var rowVisible = false;
            for (var j = 0; j < cells.length; j++) {
                var cell = cells[j];
                var cellValue = cell.innerHTML.trim().toLowerCase();

                if (j === 0 && cellValue === filterValue) {
                    rowVisible = true;
                    break;
                }

                if (j > 0 && filterColumn === cell.getAttribute("data-column") && cellValue === filterValue) {
                    rowVisible = true;
                    break;
                }
            }

            rows[i].style.display = rowVisible ? "" : "none";
        }
    }
    function openNumberOfGymsPerCountry() {
        window.open("https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/numberOfGymsPerCountry.php", "_blank");
    }
</script>

</body>
</html>
