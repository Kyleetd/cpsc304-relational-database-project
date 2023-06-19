CREATE TABLE "User" (
    ID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name VARCHAR(20)
);

CREATE TABLE User_Achievement (
    achievementID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    description VARCHAR(100),
    dateAccomplished VARCHAR(20),
    userID INT,
    goalID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES "User" (ID)
        ON DELETE CASCADE
    FOREIGN KEY (goalID) REFERENCES User_FitnessGoal (goalID)
        ON DELETE CASCADE
    UNIQUE (goalID)
);

CREATE TABLE User_FitnessGoal (
    goalID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    description VARCHAR(100),
    targetDate VARCHAR(20),
    userID INT,
    FOREIGN KEY (userID) REFERENCES "User" (ID)
        ON DELETE SET NULL
);

CREATE TABLE ConsistsOf (
    workoutID INT,
    exerciseName VARCHAR(20),
    PRIMARY KEY (workoutID, exerciseName),
    FOREIGN KEY (workoutID) REFERENCES Workout (workoutID)
        ON DELETE SET NULL,
    FOREIGN KEY (exerciseName) REFERENCES Exercise (name)
        ON DELETE SET NULL
);

CREATE TABLE Workout (
    workoutID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name VARCHAR(20)
);

CREATE TABLE Exercise (
    name VARCHAR(20) PRIMARY KEY
);

CREATE TABLE CardioExercise (
    name VARCHAR(20) PRIMARY KEY,
    duration INT,
    speed INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
);

CREATE TABLE StrengthExercise (
    name VARCHAR(20) PRIMARY KEY,
    reps INT,
    weight INT,
    sets INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
);

CREATE TABLE FlexibilityExercise (
    name VARCHAR(20) PRIMARY KEY,
    duration INT,
    sets INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
);

CREATE TABLE Gym (
    address VARCHAR(40),
    postalCode VARCHAR(20),
    city VARCHAR(20),
    name VARCHAR(20),
    PRIMARY KEY (address, postalCode)
);

CREATE TABLE PCC (
    postalCode VARCHAR(20),
    country VARCHAR(20),
    PRIMARY KEY (postalCode)
);

CREATE TABLE User_Measurement (
    height INT,
    weight INT,
    BMI REAL,
    UserID INT NOT NULL,
    PRIMARY KEY (userID, height, weight, BMI),
    FOREIGN KEY (userID) REFERENCES "User" (ID)
        ON DELETE CASCADE
);

CREATE TABLE Completes (
    userID INT,
    workoutID INT,
    date VARCHAR(8),
    PRIMARY KEY (userID, workoutID),
    FOREIGN KEY (userID) REFERENCES "User" (ID)
        ON DELETE SET NULL,
    FOREIGN KEY (workoutID) REFERENCES Workout (workoutID)
        ON DELETE CASCADE
);

CREATE TABLE Attends (
    address VARCHAR(40),
    postalCode VARCHAR(20),
    userID INT,
    PRIMARY KEY (address, postalCode, userID),
    FOREIGN KEY (address, postalCode) REFERENCES Gym (address, postalCode)
        ON DELETE SET NULL,
    FOREIGN KEY (userID) REFERENCES "User" (ID)
        ON DELETE SET NULL
);

CREATE TABLE AccomplishedBy (
    goalID INT,
    workoutID INT,
    PRIMARY KEY (goalID, workoutID),
    FOREIGN KEY (goalID) REFERENCES User_FitnessGoal (goalID)
        ON DELETE SET NULL,
    FOREIGN KEY (workoutID) REFERENCES Workout (workoutID)
        ON DELETE SET NULL
);

CREATE TABLE TrainingPlan (
    planID INT,
    name VARCHAR(100),
    description VARCHAR(20),
    PRIMARY KEY (planID)
);

CREATE TABLE TrainingPlanConsistsOf (
    planID INT,
    exerciseName VARCHAR(20),
    PRIMARY KEY (planID, exerciseName),
    FOREIGN KEY (planID) REFERENCES TrainingPlan (planID)
        ON DELETE SET NULL
    FOREIGN KEY (exerciseName) REFERENCES Exercise (name)
        ON DELETE SET NULL
);

CREATE TABLE Sees (
    userID INT,
    planID INT,
    PRIMARY KEY (userID, planID),
    FOREIGN KEY (userID) REFERENCES "User" (ID)
        ON DELETE SET NULL,
    FOREIGN KEY (planID) REFERENCES TrainingPlan (planID)
        ON DELETE SET NULL
);
