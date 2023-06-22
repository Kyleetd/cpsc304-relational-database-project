<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/goalsAndAchievements.css" />
    <title>Goals and Achievements</title>
</head>

<style>
    body {
        background-image: url("https://i.pinimg.com/564x/8d/a4/8a/8da48a450c50691b02c50467c20e0434.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        color: orange;
    }
    #back-button,
    #fitnessGoalsButton,
    #fitnessAchievementsButton {
        padding: 1px 3px;
        background-color: orange;
        border: 1px solid purple;
        border-radius: 3px;
        text-decoration: none;
        color: #7F00FF;
        font-size: 40px;
        margin-bottom: 10px;
    }
</style>

<body>
    <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/goals.php">
        <button id="fitnessGoalsButton">Fitness Goals</button>
    </a>
    <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/achievements.php">
        <button id="fitnessAchievementsButton">Fitness Achievements</button>
    </a>
    <a href="https://www.students.cs.ubc.ca/~kyleetd/project_j4i5v_j7r8j_r6z9i/src/php/dashboard.php">
        <button id="back-button">Dashboard</button>
    </a>

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
