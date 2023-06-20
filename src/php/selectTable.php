<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/selectTable.css" />
    <title>Select Data</title>
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
            for ($i = 1; $i < $numCols; $i++) {
                $column = oci_field_name($result, $i);
                $dataType = oci_field_type($result, $i);
                $columns[$column] = $dataType;
            }
            disconnectFromDB();
        }
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table_selection'])) {
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

                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=".$row["TABLE_NAME"].">".$row["TABLE_NAME"]."</option>";
                }
                oci_close($db_conn);
            }
        ?>  
        </select>  
        <input type="submit" name="Submit" value="Select" />  
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($columns)) {

        ?><p>The <?php echo $_POST['table_selection']?> table is currently selected.</p>

        <form id="ColumnSelectorForm" name="ColumnSelectorForm" method="post" action="">
            Select all columns you would like to show:
            <?php foreach ($columns as $column => $dataType) : ?>
                <input type="checkbox" name="selected_columns[]" value="<?php echo $column; ?>">
                <label><?php echo $column; ?></label><br>
                <?php if ($dataType === 'NUMBER') : ?>
                    <input type="text" name="filter[<?php echo $column; ?>]" placeholder="integer"><br>
                <?php elseif ($dataType === 'VARCHAR') : ?>
                    <input type="text" name="filter[<?php echo $column; ?>]" placeholder="text"><br>
                <?php elseif ($dataType === 'REAL') : ?>
                    <input type="text" name="filter[<?php echo $column; ?>]" placeholder="number"><br>
                <?php else : ?>
                    <input type="text" name="filter[<?php echo $column; ?>]" placeholder="text"><br>
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="submit" name="Submit" value="Submit">
        </form>
    <?php } ?>
</body>
</html>