<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/selectData.css" />
    <title>Table Result</title>
</head>
<body>
    <div class="header">
        <h1>View Table Data</h1>
        <a href="./selectData.php" class="back-button">Back</a>
    </div>

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
        
            //Build the filter conditions
            $filterConditions = array();
            foreach ($filterStatements as $column => $value) {
                if (!empty($value)) {
                    $filterConditions[] = "$column".$_POST['filter_operators'][$column]."'$value'";
                }
            }
        
            // Finalize the query
            if (!empty($filterConditions)) {
                $filterClause = implode(" AND ", $filterConditions);
                $query = $selectStatement." WHERE ".$filterClause;
            } else {
                $query = $selectStatement;
            }
        
            $results = executePlainSQL($query);
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