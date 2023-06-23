<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/selectData.css" />
    <title>Table Selector</title>
</head>
<body>
    <div class="header">
        <h1 style="color: orange; text-shadow: 2px 2px 4px #5D3FD3;">View Table Data</h1>
        <a href="./dashboard.php" class="back-button">Back</a>
    </div>

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table_selection'])) {
        handleTableSelectedRequest();
    }
    ?>

    <div class="form-container">
        <form id="TableSelectorForm" name="TableSelectorForm" method="post" action="">
            <div class=table-select-container>
            <span class="purple-box">Select a Table :</span>  
            <select class="table-selection" name="table_selection">  
                <option value="">--- Select ---</option>  

                <?php  
                if (connectToDB()) {
                    $result = executePlainSQL("SELECT table_name FROM user_tables");

                    while ($row = oci_fetch_array($result, OCI_ASSOC)) {
                        ?>
                        <option value='<?php echo $row["TABLE_NAME"]; ?>'
                            <?php if (isset($_POST['table_selection']) && $row["TABLE_NAME"] == $_POST['table_selection']) {
                                echo "selected";
                            } ?> 
                        >
                            <?php echo $row["TABLE_NAME"]; ?>
                        </option>
                        <?php
                    }
                    disconnectFromDB();
                }
                ?>  
            </select>  
            <input class="select-button" type="submit" name="Submit" value="Select" />
            </div>
        </form>
    </div>

    <div class="attributes-container">
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($columns)) :?>
            <form id="ColumnSelectorForm" name="ColumnSelectorForm" method="post" action="./selectDataResult.php">
                <br>
                <div class="purple-box">
                    <span>Select all columns you would like to show:<br>
                        Enter values in boxes to filter. Leave empty if no filtering desired.</span>
                </div>

                <div class="filter-container">
                    <?php foreach ($columns as $column => $dataType) : ?>
                        <div class="filter-cell">
                            <input type="checkbox" name="selected_columns_list[]" value="<?php echo $column; ?>"
                                id="filter_list[<?php echo $column; ?>]">
                            <label class="purple-box" for="filter_list[<?php echo $column; ?>]"><?php echo $column; ?></label>
                            <?php if ($dataType === 'NUMBER' || $dataType === 'REAL') : ?>
                                <select class name="filter_operators[<?php echo $column; ?>]">
                                    <option value="=">Equal to (=)</option>
                                    <option value="<">Less than (<)</option>
                                    <option value=">">Greater than (>)</option>
                                    <option value="<=">Less than or equal to (<=)</option>
                                    <option value=">=">Greater than or equal to (>=)</option>
                                </select>
                                <input type="number" name="filter_list[<?php echo $column; ?>]" placeholder="Number">
                            <?php elseif ($dataType === 'VARCHAR2') : ?>
                                <select name="filter_operators[<?php echo $column; ?>]" >
                                    <option value="=">Equal to (=)</option>
                                    <option value="!=">Not equal to (!=)</option>
                                </select>
                                <input type="text" name="filter_list[<?php echo $column; ?>]" placeholder="Text">
                            <?php elseif ($dataType === 'DATE') : ?>
                                <select name="filter_operators[<?php echo $column; ?>]">
                                    <option value="=">Equal to (=)</option>
                                    <option value="<">Less than (<)</option>
                                    <option value=">">Greater than (>)</option>
                                    <option value="<=">Less than or equal to (<=)</option>
                                    <option value=">=">Greater than or equal to (>=)</option>
                                </select>
                                <input type="date" min="1900-01-01" max="9999-12-31" name="filter_list[<?php echo $column; ?>]">
                            <?php endif; ?>
                            <br>
                        </div>
                    <?php endforeach; ?>
                </div>

                <input type="hidden" name="table_selection" value="<?php echo $_POST['table_selection']; ?>">
                <input class="select-button" type="submit" name="Submit" value="Get Table">
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
