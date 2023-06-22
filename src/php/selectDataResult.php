<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/selectDataResult.css" />
    <title>Table Result</title>
</head>
<body>
    <div class="header">
        <h1 style="color: orange; text-shadow: 2px 2px 4px #5D3FD3;">View Table Data</h1>
        <a href="./selectData.php" class="back-button">Back</a>
    </div>
    <style>
    .header {
        text-align: center;
        font-size: 25px;
        padding: 10px;
        background-color: transparent;
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
        background-image: url("https://i.pinimg.com/564x/26/59/09/265909ebce6c16b329e09c48b9147667.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        margin: 0;
        height: 100vh; 
    }
    table {
        margin: auto;
        border-collapse: collapse;
        width: 80%;
        background-color: #5D3FD3; 
    }
    th, td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid orange;
        color: orange; 
    }
    </style>

    <?php
    require_once('./dbUtils.php');

    $results = array();
    function handleColAndFilterRequest() {
        if (connectToDB()) {
            global $results;
            $selectedColumns = $_POST['selected_columns_list'];
            $filterStatements = $_POST['filter_list'];
            $selectedTable = $_POST['table_selection'];
    
            // Build the SELECT statement
            $selectStatement = "SELECT " . implode(", ", $selectedColumns) . " FROM $selectedTable";
    
            // Build the filter conditions
            $filterConditions = array();
            $tuples = array();
            foreach ($filterStatements as $column => $value) {
                if (!empty($value)) {
                    $filterConditions[] = "$column " . $_POST['filter_operators'][$column] . " :$column";
                    $tuples[":$column"] = $value;
                }
            }
    
            // Finalize the query
            if (!empty($filterConditions)) {
                $filterClause = implode(" AND ", $filterConditions);
                $query = $selectStatement . " WHERE " . $filterClause;
                $results = executeBoundSQL($query, $tuples);
            } else {
                $query = $selectStatement;
                $results = executePlainSQL($query);
            }
            disconnectFromDB();
        }
    }
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST' 
        && isset($_POST['selected_columns_list']) && isset($_POST['filter_list'])) {
        handleColAndFilterRequest();
    }
    ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' 
        && isset($_POST['selected_columns_list']) && isset($_POST['filter_list'])) : ?>
        <table>
        <caption style="color: #5D3FD3;"><?php echo $_POST['table_selection']; ?></caption>
            <thead>
                <tr>
                    <?php foreach ($_POST['selected_columns_list'] as $column) : ?>
                        <th><?php echo $column; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($results, OCI_ASSOC)) : ?>
                    <tr>
                        <?php foreach ($_POST['selected_columns_list'] as $column) : ?>
                            <td><?php echo $row[$column]; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
    </div>
</body>
</html>