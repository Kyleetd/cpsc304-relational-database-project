<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/goalsAndAchievements.css" />
    <title>Goals and Achievements</title>
    <a href="./dashboard.php" class="back-button">Back</a>
</head>

<body>
    <div class='button-wrapper'>
        <a href="./goals.php">
            <button class='button'>Fitness Goals</button>
        </a>
        <a href="./achievements.php">
            <button class='button'>Fitness Achievements</button>
        </a>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['fitnessGoalsButton'])) {
            // Handle Fitness Achievements button click
            // Display Fitness Achievements content
        } elseif (isset($_POST['fitnessAchievementsButton'])) {
            // Handle Fitness Achievements button click
            // Display Fitness Achievements content
            echo '<h1 style="color: orange;">Fitness Achievements</h1>';
        }
    }
    ?>

</body>
</html>