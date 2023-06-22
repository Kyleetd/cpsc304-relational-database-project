<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/dashboard.css" />
  <style>
    body {
      background-image: url('https://i.pinimg.com/564x/1f/3d/43/1f3d43bcd2f77090fe4e8ee181654a3e.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .menu-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .button-wrapper {
      background-color: #8D6CE9; /* Purple color */
      padding: 20px;
      border: 4px solid #8D6CE9; /* Purple color */
      border-radius: 10px;
    }

    .button {
      background-color: #FFB74D; /* Warm orange color */
      border: none;
      color: #FFFFFF; /* White text color */
      padding: 12px 24px;
      font-size: 18px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      margin: 10px;
      cursor: pointer;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }

    .fitnessGoalsButton:hover {
      background-color: #FF9800; /* Lighter shade of warm orange */
    }
  </style>
  <title>Dashboard</title>
</head>
<body>
  <div class="menu-container">
    <div class="button-wrapper">
      <a href="./selectData.php">
        <button class="button">View Any Table</button>
      </a>
      <a href="./goalsAndAchievements.php">
        <button class="button">Goals And Achievements</button>
      </a>
      <a href="./profile.php">
        <button class="button">User Profiles</button>
      </a>
      <a href="./gym.php">
        <button class="button">Gyms</button>
      </a>
    </div>
  </div>
</body>
</html>
