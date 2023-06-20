-- Drop Table Statements

DROP TABLE AccomplishedBy;
DROP TABLE Attends;
DROP TABLE Completes;
DROP TABLE TrainingPlanConsistsOf;
DROP TABLE Sees;
DROP TABLE ConsistsOf;
DROP TABLE CardioExercise;
DROP TABLE StrengthExercise;
DROP TABLE FlexibilityExercise;
DROP TABLE User_Achievement;
DROP TABLE User_Measurement;
DROP TABLE User_FitnessGoal;
DROP TABLE Exercise;
DROP TABLE Workout;
DROP TABLE Gym;
DROP TABLE PCC;
DROP TABLE TrainingPlan;
DROP TABLE "User";


-- Table Creation Statements

CREATE TABLE "User" (
    ID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name VARCHAR(30)
);

CREATE TABLE User_FitnessGoal (
    goalID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    description VARCHAR(100),
    targetDate VARCHAR(20),
    userID INT,
    FOREIGN KEY (userID) REFERENCES "User" (ID)
        ON DELETE SET NULL
);

CREATE TABLE Workout (
    workoutID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name VARCHAR(50)
);

CREATE TABLE Exercise (
    name VARCHAR(100) PRIMARY KEY
);

CREATE TABLE ConsistsOf (
    workoutID INT,
    exerciseName VARCHAR(50),
    PRIMARY KEY (workoutID, exerciseName),
    FOREIGN KEY (workoutID) REFERENCES Workout (workoutID) ON DELETE SET NULL,
    FOREIGN KEY (exerciseName) REFERENCES Exercise (name) ON DELETE SET NULL
);

CREATE TABLE User_Achievement (
    achievementID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    description VARCHAR(100),
    dateAccomplished VARCHAR(20),
    userID INT,
    goalID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES "User" (ID) ON DELETE CASCADE,
    FOREIGN KEY (goalID) REFERENCES User_FitnessGoal (goalID) ON DELETE CASCADE,
    UNIQUE (goalID)
);

CREATE TABLE Completes (
    userID INT,
    workoutID INT,
    dateCompleted VARCHAR(10),
    PRIMARY KEY (userID, workoutID),
    FOREIGN KEY (userID) REFERENCES "User" (ID) ON DELETE SET NULL,
    FOREIGN KEY (workoutID) REFERENCES Workout (workoutID) ON DELETE CASCADE
);

CREATE TABLE CardioExercise (
    name VARCHAR(50) PRIMARY KEY,
    duration INT,
    speed INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
);

CREATE TABLE StrengthExercise (
    name VARCHAR(50) PRIMARY KEY,
    reps INT,
    weight INT,
    sets INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
);

CREATE TABLE FlexibilityExercise (
    name VARCHAR(50) PRIMARY KEY,
    duration INT,
    sets INT,
    FOREIGN KEY (name) REFERENCES Exercise (name)
        ON DELETE CASCADE
);

CREATE TABLE Gym (
    address VARCHAR(40),
    postalCode VARCHAR(20),
    city VARCHAR(20),
    name VARCHAR(30),
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
    userID INT NOT NULL,
    PRIMARY KEY (userID, height, weight, BMI),
    FOREIGN KEY (userID) REFERENCES "User" (ID)
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
    name VARCHAR(50),
    description VARCHAR(100),
    PRIMARY KEY (planID)
);

CREATE TABLE TrainingPlanConsistsOf (
    planID INT,
    exerciseName VARCHAR(100),
    PRIMARY KEY (planID, exerciseName),
    FOREIGN KEY (planID) REFERENCES TrainingPlan (planID)
        ON DELETE SET NULL,
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



-- Insert Statements

INSERT INTO "User" (name) VALUES ('Kylee');
INSERT INTO "User" (name) VALUES ('Jon');
INSERT INTO "User" (name) VALUES ('Kashish');
INSERT INTO "User" (name) VALUES ('Mickey');
INSERT INTO "User" (name) VALUES ('Naruto');

INSERT INTO User_FitnessGoal (description, targetDate, userID) VALUES ('Lose weight', '2023-12-31', 1);
INSERT INTO User_FitnessGoal (description, targetDate, userID) VALUES ('Build muscle', '2023-10-15', 2);
INSERT INTO User_FitnessGoal (description, targetDate, userID) VALUES ('Improve flexibility', '2024-01-30', 3);
INSERT INTO User_FitnessGoal (description, targetDate, userID) VALUES ('Increase endurance', '2023-11-30', 4);
INSERT INTO User_FitnessGoal (description, targetDate, userID) VALUES ('Maintain overall fitness', '2023-12-31', 5);
INSERT INTO User_FitnessGoal (description, targetDate, userID) VALUES ('Run for 30 minutes', '2023-12-31', 5);

INSERT INTO Workout (name) VALUES ('Cardio-Centric High Impact');
INSERT INTO Workout (name) VALUES ('Endurance Boost');
INSERT INTO Workout (name) VALUES ('Upper Body');
INSERT INTO Workout (name) VALUES ('Upper Body Strength and Stretching');
INSERT INTO Workout (name) VALUES ('HIIT Cycling');

INSERT INTO Exercise (name) VALUES ('Jump Squats and Lunges'); 
INSERT INTO Exercise (name) VALUES ('Sprints');
INSERT INTO Exercise (name) VALUES ('Weighted Squats');
INSERT INTO Exercise (name) VALUES ('Hop-Scotch Circuit');
INSERT INTO Exercise (name)VALUES ('Box Jumps');
INSERT INTO Exercise (name) VALUES ('Squat Jump Sequence');
INSERT INTO Exercise (name) VALUES ('Bicep Curl');
INSERT INTO Exercise (name) VALUES ('Bench Press');
INSERT INTO Exercise (name) VALUES ('Ball Throws');
INSERT INTO Exercise (name) VALUES ('Power Yoga Flow');
INSERT INTO Exercise (name) VALUES ('Leg Kicks');
INSERT INTO Exercise (name) VALUES ('Toe Touches');
INSERT INTO Exercise (name) VALUES ('Hanging Leg Raises');
INSERT INTO Exercise (name) VALUES ('Chaturanga Push-Ups');
INSERT INTO Exercise (name) VALUES ('Cycling');
INSERT INTO Exercise (name) VALUES ('Ballet Bar Routine');
INSERT INTO Exercise (name) VALUES ('Hip-Hop Dance Routine');

INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (1, 'Jump Squats and Lunges');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (1, 'Sprints');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (1, 'Box Jumps');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (1, 'Squat Jump Sequence');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (2, 'Hop-Scotch Circuit');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (2, 'Hip-Hop Dance Routine');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (3, 'Bicep Curl');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (3, 'Bench Press');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (3, 'Ball Throws');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (4, 'Chaturanga Push-Ups');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (4, 'Weighted Squats');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (4, 'Power Yoga Flow');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (4, 'Leg Kicks');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (4, 'Toe Touches');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (4, 'Hanging Leg Raises');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (4, 'Ballet Bar Routine');
INSERT INTO ConsistsOf (workoutID, exerciseName) VALUES (5, 'Cycling');

INSERT INTO User_Achievement (description, dateAccomplished, userID, goalID) VALUES ('20 chin-ups', '12/06/2023', 1, 1);
INSERT INTO User_Achievement (description, dateAccomplished, userID, goalID) VALUES ('80 push-ups', '12/06/2023', 1, 2);
INSERT INTO User_Achievement (description, dateAccomplished, userID, goalID) VALUES ('Splits on both sides', '01/05/2023', 2, 3);
INSERT INTO User_Achievement (description, dateAccomplished, userID, goalID) VALUES ('Splits on both sides', '15/02/2023', 4, 4);
INSERT INTO User_Achievement (description, dateAccomplished, userID, goalID) VALUES ('Run for 30 minutes at 9 kph', '01/01/2023', 5, 5);

INSERT INTO Completes (userID, workoutID, dateCompleted) VALUES (1, 5, '22/03/2023');
INSERT INTO Completes (userID, workoutID, dateCompleted) VALUES (2, 4, '09/02/2023');
INSERT INTO Completes (userID, workoutID, dateCompleted) VALUES (3, 3, '11/05/2023');
INSERT INTO Completes (userID, workoutID, dateCompleted) VALUES (4, 2, '29/01/2023');
INSERT INTO Completes (userID, workoutID, dateCompleted) VALUES (5, 1, '14/02/2023');

INSERT INTO CardioExercise (name, duration, speed) VALUES ('Jump Squats and Lunges', 5, NULL);
INSERT INTO CardioExercise (name, duration, speed) VALUES ('Box Jumps', 10, NULL);
INSERT INTO CardioExercise (name, duration, speed) VALUES ('Squat Jump Sequence', 5, NULL);
INSERT INTO CardioExercise (name, duration, speed) VALUES ('Sprints', 3, 25);
INSERT INTO CardioExercise (name, duration, speed) VALUES ('Hop-Scotch Circuit', 5, NULL);
INSERT INTO CardioExercise (name, duration, speed) VALUES ('Hip-Hop Dance Routine', 45, NULL);
INSERT INTO CardioExercise (name, duration, speed) VALUES ('Cycling', 30, 20);

INSERT INTO StrengthExercise (name, reps, weight, sets) VALUES ('Weighted Squats', 20, 15, 3);
INSERT INTO StrengthExercise (name, reps, weight, sets) VALUES ('Bicep Curl', 15, 30, 3);
INSERT INTO StrengthExercise (name, reps, weight, sets) VALUES ('Bench Press', 30, 10, 3);
INSERT INTO StrengthExercise (name, reps, weight, sets) VALUES ('Ball Throws', 25, 20, 3);
INSERT INTO StrengthExercise (name, reps, weight, sets) VALUES ('Chaturanga Push-Ups', 30, NULL, 3);

INSERT INTO FlexibilityExercise (name, duration, sets) VALUES ('Power Yoga Flow', 30, NULL);
INSERT INTO FlexibilityExercise (name, duration, sets) VALUES ('Leg Kicks', 3, 15);
INSERT INTO FlexibilityExercise (name, duration, sets) VALUES ('Toe Touches', 3, 5);
INSERT INTO FlexibilityExercise (name, duration, sets) VALUES ('Hanging Leg Raises', 20, 2);
INSERT INTO FlexibilityExercise (name, duration, sets) VALUES ('Ballet Bar Routine', 45, NULL);

INSERT INTO Gym (address, postalCode, city, name) VALUES ('6000 Student Union Blvd', 'V6T 1Z1', 'Vancouver', 'BirdCoop Fitness Centre');
INSERT INTO Gym (address, postalCode, city, name) VALUES ('3407 Guadalupe St', '78705',  'Austin', 'Anytime Fitness');
INSERT INTO Gym (address, postalCode, city, name) VALUES ('206 Lakeside Dr', 'V1L 6B9', 'Nelson', 'Maverick Strength');
INSERT INTO Gym (address, postalCode, city, name) VALUES ('1121 Ironwood St', 'V9W 5L6', 'Campbell River', 'West Coast Muscle and Fitness');
INSERT INTO Gym (address, postalCode, city, name) VALUES ('1350 Manufacturing St Suite 204', '75207', 'Dallas', 'Hunger in the Wild Gym');

INSERT INTO PCC (postalCode, country) VALUES ('V6T 1Z1', 'Canada');
INSERT INTO PCC (postalCode, country) VALUES ('78705',  'United States');
INSERT INTO PCC (postalCode, country) VALUES ('V1L 6B9', 'Canada');
INSERT INTO PCC (postalCode, country) VALUES ('V9W 5L6', 'Canada');
INSERT INTO PCC (postalCode, country) VALUES ('75207',  'United States');

INSERT INTO User_Measurement (height, weight, BMI, UserID) VALUES ('175', '60', '19.6', 1);
INSERT INTO User_Measurement (height, weight, BMI, UserID) VALUES ('188', '75', '21.2', 2);
INSERT INTO User_Measurement (height, weight, BMI, UserID) VALUES ('153', '64', '27.3', 3);
INSERT INTO User_Measurement (height, weight, BMI, UserID) VALUES ('202', '92', '22.5', 4);
INSERT INTO User_Measurement (height, weight, BMI, UserID) VALUES ('166', '49', '17.8', 5);

INSERT INTO Attends (address, postalCode, userID) VALUES ('6000 Student Union Blvd', 'V6T 1Z1', 1);
INSERT INTO Attends (address, postalCode, userID) VALUES ('6000 Student Union Blvd', 'V6T 1Z1', 2);
INSERT INTO Attends (address, postalCode, userID) VALUES ('206 Lakeside Dr', 'V1L 6B9', 3);
INSERT INTO Attends (address, postalCode, userID) VALUES ('1350 Manufacturing St Suite 204', '75207', 4);
INSERT INTO Attends (address, postalCode, userID) VALUES ('3407 Guadalupe St', '78705', 4);
INSERT INTO Attends (address, postalCode, userID) VALUES ('1121 Ironwood St', 'V9W 5L6', 5);

INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (1, 3);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (1, 4);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (2, 3);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (2, 4);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (3, 4);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (4, 4);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (5, 1);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (5, 2);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (5, 5);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (6, 1);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (6, 2);
INSERT INTO AccomplishedBy (goalID, workoutID) VALUES (6, 5);

INSERT INTO TrainingPlan (planID, name, description) VALUES (1, 'Plan 1', 'Jump series');
INSERT INTO TrainingPlan (planID, name, description) VALUES (2, 'Plan 2', 'Cardio routines');
INSERT INTO TrainingPlan (planID, name, description) VALUES (3, 'Plan 3', 'Upper body training');
INSERT INTO TrainingPlan (planID, name, description) VALUES (4, 'Plan 4', 'Strength and flexibility');
INSERT INTO TrainingPlan (planID, name, description) VALUES (5, 'Plan 5', 'Cycling');

INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (1, 'Jump Squats and Lunges');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (1, 'Sprints');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (1, 'Box Jumps');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (1, 'Squat Jump Sequence');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (2, 'Hop-Scotch Circuit');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (2, 'Hip-Hop Dance Routine');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (3, 'Bicep Curl');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (3, 'Bench Press');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (3, 'Ball Throws');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (4, 'Chaturanga Push-Ups');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (4, 'Weighted Squats');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (4, 'Power Yoga Flow');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (4, 'Leg Kicks');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (4, 'Toe Touches');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (4, 'Hanging Leg Raises');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (4, 'Ballet Bar Routine');
INSERT INTO TrainingPlanConsistsOf (planID, exerciseName) VALUES (5, 'Cycling');

INSERT INTO Sees (userID, planID) VALUES (1, 1);
INSERT INTO Sees (userID, planID) VALUES (2, 1);
INSERT INTO Sees (userID, planID) VALUES (3, 2);
INSERT INTO Sees (userID, planID) VALUES (4, 3);
INSERT INTO Sees (userID, planID) VALUES (5, 3);



