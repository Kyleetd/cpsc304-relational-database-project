<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/goalsAndAchievements.css" />
    <title>Goals and Achievements</title>
</head>
<body>
    <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/goals.php">
        <button id="fitnessGoalsButton">Fitness Goals</button>
    </a>
    <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/achievements.php">
        <button id="fitnessGoalsButton">Fitness Achievements</button>
    </a>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fitnessGoalsButton'])) {
        // Handle Fitness Achievements button click
        // Display Fitness Achievements content
    } elseif (isset($_POST['fitnessAchievementsButton'])) {
        // Handle Fitness Achievements button click
        // Display Fitness Achievements content
        echo '<h1>Fitness Achievements</h1>';
    }
}
?>

</body>
</html>