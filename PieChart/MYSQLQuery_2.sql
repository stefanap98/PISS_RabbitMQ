CREATE DATABASE IF NOT EXISTS piechart;
Use piechart;

CREATE TABLE IF NOT EXISTS ProjectGrades  (
  Id int NOT NULL PRIMARY KEY,
  Grade int UNSIGNED DEFAULT 0
);
