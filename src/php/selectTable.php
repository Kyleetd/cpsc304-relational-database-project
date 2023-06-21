<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/selectTable.css" />
    <title>Select Data</title>

    <style>
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }

        .filter-container {
            display:table;
        }

        .filter-cell {
            display: table-row;
        }
    </style>
</head>
<body>
    <h1>Select Table Data</h1>

    <?php
    require_once('./dbUtils.php');
    $columns = array();

    function handleTableSelectedRequest() {
        if (connectToDB()) {
            // Query the selected table
            $table = $_POST['table_selection'];
            $result = executePlainSQL("SELECT * FROM $table");

            //Add columns to columns array
            global $columns;
            $numCols = oci_num_fields($result);
            for ($i = 1; $i <= $numCols; $i++) {
                $column = oci_field_name($result, $i);
                $dataType = oci_field_type($result, $i);
                $columns[$column] = $dataType;
            }
            disconnectFromDB();
        }
    }

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
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table_selection'])) {
        handleTableSelectedRequest();
    }
    ?>

    <form id="TableSelectorForm" name="TableSelectorForm" method="post" action="">  
        Select a Table :  
        <select name="table_selection">  
        <option value="">--- Select ---</option>  

        <?php  
            if (connectToDB()) {
                $result = executePlainSQL("SELECT table_name FROM user_tables");

                while ($row = oci_fetch_array($result, OCI_ASSOC)) {
                    echo "<option value='".$row["TABLE_NAME"]."'>".$row["TABLE_NAME"]."</option>";
                }
                disconnectFromDB();
            }
        ?>  
        </select>  
        <input type="submit" name="Submit" value="Select" />  
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($columns)) :?>
        
        <p>The <?php echo $_POST['table_selection']?> table is currently selected.</p>

        <form id="ColumnSelectorForm" name="ColumnSelectorForm" method="post" action="">
            Select all columns you would like to show:
            Enter values in text book to filter. Leave empty if no filtering desired.

            <div class="filter-container">
                <?php foreach ($columns as $column => $dataType) : ?>
                    <div class="filter-cell">
                    <input type="checkbox" name="selected_columns_list[]" value="<?php echo $column; ?>"
                        id="filter_list[<?php echo $column; ?>]">
                    <label for="filter_list[<?php echo $column; ?>]"><?php echo $column; ?></label>
                    <?php if ($dataType === 'NUMBER' || $dataType === 'REAL') : ?>
                        <select name="filter_operators[<?php echo $column; ?>]">
                            <option value="=">Equal to (=)</option>
                            <option value="<">Less than (<)</option>
                            <option value=">">Greater than (>)</option>
                            <option value="<=">Less than or equal to (<=)</option>
                            <option value=">=">Greater than or equal to (>=)</option>
                        </select>
                    <?php elseif ($dataType === 'VARCHAR2') : ?>
                        <select name="filter_operators[<?php echo $column; ?>]">
                            <option value="=">Equal to (=)</option>
                            <option value="!=">Not equal to (!=)</option>
                        </select>
                    <?php endif; ?>
                    <input type="text" name="filter_list[<?php echo $column; ?>]">
                    <br>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="hidden" name="table_selection" value="<?php echo $_POST['table_selection']; ?>">
            <input type="submit" name="Submit" value="Get Table">
        </form>
    <?php endif; ?>

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
</body>
</html>