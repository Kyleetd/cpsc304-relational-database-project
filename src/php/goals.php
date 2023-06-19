<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <link rel="stylesheet" type="text/css" href="../css/goalsAndAchievements.css" /> -->
        <title>Goals</title>
    </head>

    <body>
        <h2>Add a Fitness Goal</h2>
        <form method="POST" action="Goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Goal ID: <input type="number" name="goalID"> <br /><br />
            Description: <input type="text" name="description"> <br /><br />
            Target Date: <input type="text" name="targetDate"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Mark a Goal as Achieved</h2>

        <form method="POST" action="Goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="goalAchievedRequest" name="goalAchievedRequest">
            Goal ID: <input type="text" name="goalID"> <br /><br />
            Date Achieved: <input type="text" name="dateAchieved"> <br /><br />

            <input type="submit" value="Mark Achieved" name="goalAchievedSubmit"></p>
        </form>

        <hr />

        <h2>Display Goals</h2>
        <form method="GET" action="Goals.php">
            <input type="hidden" id="displayGoalsRequest" name="displayGoalsRequest">
            <input type="submit" value="Display Goals" name="displayGoals">
        </form>

<?php



?>

	</body>
</html>