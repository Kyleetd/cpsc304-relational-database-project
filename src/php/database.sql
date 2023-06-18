CREATE TABLE [User] (
    ID SERIAL PRIMARY KEY,
    name VARCHAR(20)
);

CREATE TABLE User_Achievement (
    achievementID SERIAL PRIMARY KEY,
    description CHAR(100),
    dateAccomplished CHAR(20),
    userID INT,
    goalID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES [User] (ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (goalID) REFERENCES User_FitnessGoal (goalID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    UNIQUE (goalID)
);

CREATE TABLE User_FitnessGoal (
    goalID SERIAL PRIMARY KEY,
    description CHAR(100),
    targetDate CHAR(20),
    userID INT,
    FOREIGN KEY (userID) REFERENCES [User] (ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE ConsistsOf (
    workoutID INT,
    exerciseName CHAR(20),
    PRIMARY KEY (workoutID, exerciseName),
    FOREIGN KEY (workoutID) REFERENCES Workout (workoutID)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (exerciseName) REFERENCES Exercise (name)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE Workout (
    workoutID SERIAL PRIMARY KEY,
    name CHAR(20)
);

CREATE TABLE Exercise (
    name CHAR(20) PRIMARY KEY
);

CREATE TABLE CardioExercise (
    name CHAR(20) PRIMARY KEY,
    duration INT,
    speed INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE StrengthExercise (
    name CHAR(20) PRIMARY KEY,
    reps INT,
    weight INT,
    sets INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE FlexibilityExercise (
    name CHAR(20) PRIMARY KEY,
    duration INT,
    sets INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Gym (
    address CHAR(40),
    postalCode CHAR(20),
    city CHAR(20),
    country CHAR(20),
    name CHAR(20),
    PRIMARY KEY (address, postalCode)
);

CREATE TABLE User_Measurement (
    height INT,
    weight INT,
    BMI REAL,
    UserID INT NOT NULL,
    PRIMARY KEY (userID, height, weight, BMI),
    FOREIGN KEY (userID) REFERENCES [User] (ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Completes (
    userID INT,
    workoutID INT,
    date CHAR(8),
    PRIMARY KEY (userID, workoutID),
    FOREIGN KEY (userID) REFERENCES [User] (ID)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (workoutID) REFERENCES Workout (workoutID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Attends (
    address CHAR(40),
    postalCode CHAR(20),
    userID INT,
    PRIMARY KEY (address, postalCode, userID),
    FOREIGN KEY (address, postalCode) REFERENCES Gym (address, postalCode)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (userID) REFERENCES [User] (ID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE AccomplishedBy (
    goalID INT,
    workoutID INT,
    PRIMARY KEY (goalID, workoutID),
    FOREIGN KEY (goalID) REFERENCES User_FitnessGoal (goalID)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (workoutID) REFERENCES Workout (workoutID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE TrainingPlan (
    planID INT,
    name CHAR(100),
    description CHAR(20),
    PRIMARY KEY (planID)
);

CREATE TABLE TrainingPlanConsistsOf (
    planID INT,
    exerciseName CHAR(20),
    PRIMARY KEY (planID, exerciseName),
    FOREIGN KEY (planID) REFERENCES TrainingPlan (planID)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (exerciseName) REFERENCES Exercise (name)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE Sees (
    userID INT,
    planID INT,
    PRIMARY KEY (userID, planID),
    FOREIGN KEY (userID) REFERENCES [User] (ID)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (planID) REFERENCES TrainingPlan (planID)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);
