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

            if (str_contains($selectedTable, ";")) {
                return;
            }

            $table = executePlainSQL("SELECT * FROM $selectedTable");

            //Add columns to columns array
            $columnTypes = array();
            $numCols = oci_num_fields($table);
            for ($i = 1; $i <= $numCols; $i++) {
                $column = oci_field_name($table, $i);
                $dataType = oci_field_type($table, $i);
                $columnTypes[$column] = $dataType;
            }
    
            // Build the SELECT statement
            $selectStatement = "SELECT " . implode(", ", $selectedColumns) . " FROM $selectedTable";
    
            // Build the filter conditions
            $filterConditions = array();
            $tuples = array();
            foreach ($filterStatements as $column => $value) {
                if (!empty($value)) {
                    if ($columnTypes[$column] === 'DATE') {
                        $filterConditions[] = "$column " . $_POST['filter_operators'][$column] . " TO_DATE(:$column, 'YYYY-MM-DD')"; 
                    } else {
                        $filterConditions[] = "$column " . $_POST['filter_operators'][$column] . " :$column";
                    }
                    $tuples[":$column"] = $value;
                }
            }
    
            // Finalize the query
            if (!empty($filterConditions)) {
                $filterClause = implode(" AND ", $filterConditions);
                $query = $selectStatement . " WHERE " . $filterClause;
                $results = executeBoundSQL($query, $tuples);
            } else if (!str_contains($selectStatement, ";")) {
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
        <caption><?php echo $_POST['table_selection']; ?></caption>
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