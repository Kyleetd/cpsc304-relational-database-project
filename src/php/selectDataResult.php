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
        <h1>View Table Data</h1>
        <a href="./selectData.php" class="back-button">Back</a>
    </div>

    <?php
    require_once('./dbUtils.php');

    function getPrimaryKeys() {
        if (connectToDB()) {
            $selectedTable = $_POST['table_selection'];

            $stmt = "SELECT cols.table_name, cols.column_name, cols.position, cons.status, cons.owner
            FROM all_constraints cons, all_cons_columns cols
            WHERE cols.table_name = '$selectedTable'
            AND cons.constraint_type = 'P'
            AND cons.constraint_name = cols.constraint_name
            AND cons.owner = cols.owner
            ORDER BY cols.table_name, cols.position";

            $results = executePlainSQL($stmt);
            while ($row = oci_fetch_array($results, OCI_ASSOC)) {
                echo $row["TABLE_NAME"];
            }
            disconnectFromDB();
            return $results;
        }
    }

    function handleUpdate() {
        if (connectToDB()) {
            $updates = $_POST['update_list'];
            $selectedTable = $_POST['table_selection'];
            $updateStatement = "UPDATE $selectedTable SET ";

            $setStatements = array();
            // $tuples = array();
            // foreach ($updates as $column => value) {
            //     if (!empty($value)) {
            //         $setStatements[] = "$column = ':$column'";
            //         $tuples[":$column"] = $value;
            //     } 
            // }

            if (!empty($setStatements)) {
                $setClause = implode(", ", $setStatements);
                $query = $updateStatement . $setClause . " WHERE ohiughuguyf";
                $results = executeBoundSQL($query, $tuples);
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
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_list'])) {
        handleUpdate();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' 
        && isset($_POST['selected_columns_list']) && isset($_POST['filter_list'])) {
        handleColAndFilterRequest();
    }
    ?>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST['selected_columns_list']) && isset($_POST['filter_list'])) : ?>
    <?php getPrimaryKeys(); ?>
    <table>
        <caption><?php echo $_POST['table_selection']; ?></caption>
        <thead>
            <tr>
                <?php foreach ($_POST['selected_columns_list'] as $column) : ?>
                    <th><?php echo $column; ?></th>
                <?php endforeach; ?>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $rowIndex = 0; ?>
            <?php while ($row = oci_fetch_array($results, OCI_ASSOC)) : ?>
                <tr>
                    <?php foreach ($_POST['selected_columns_list'] as $column) : ?>
                        <td><?php echo $row[$column]; ?></td>
                    <?php endforeach; ?>
                    <td>
                        <button type="button" class="edit-button" data-row-index="<?php echo $rowIndex; ?>">Edit</button>
                    </td>
                </tr>
                <tr class="update-row" style="display: none;">
                    <form method="post" action="">
                        <?php foreach ($_POST['selected_columns_list'] as $column) : ?>
                            <td><input type="text" name="<?php echo "update_list[$column]"; ?>"></td>
                        <?php endforeach; ?>
                        <td>
                            <button type="submit">Update</button>
                            <button type="button" class="cancel-button">Cancel</button>
                        </td>

                        <input type="hidden" name="table_selection" value="<?php echo $_POST['table_selection']; ?>">

                        <?php foreach ($_POST['selected_columns_list'] as $column) : ?>
                            <input type="hidden" name="<?php echo "selected_columns_list[]"; ?>" value="<?php echo $column; ?>">
                        <?php endforeach; ?>

                        <?php foreach ($_POST['filter_list'] as $filter) : ?>
                            <input type="hidden" name="<?php echo "filter_list[]"; ?>" value="<?php echo $filter; ?>">
                        <?php endforeach; ?>

                        <?php foreach ($_POST['filter_operators'] as $op) : ?>
                            <input type="hidden" name="<?php echo "filter_operators[]"; ?>" value="<?php echo $op; ?>">
                        <?php endforeach; ?>
                    </form>
                </tr>
                <?php $rowIndex++; ?>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
    // Add event listeners to the edit buttons and cancel buttons
    const editButtons = document.querySelectorAll('.edit-button');
    const cancelButtons = document.querySelectorAll('.cancel-button');
    const updateRows = document.querySelectorAll('.update-row');

    // Allow each edit button to reveal the hidden row
    editButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const rowIndex = button.getAttribute('data-row-index');
            updateRows[rowIndex].style.display = 'table-row';
        });
    });

    // Allow each cancel button to hide the row
    cancelButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            updateRows[index].style.display = 'none';
        });
    });
</script>

</body>
</html>