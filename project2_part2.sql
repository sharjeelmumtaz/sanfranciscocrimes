
DROP DATABASE IF EXISTS SFIncidents;
CREATE DATABASE SFIncidents;
USE SFIncidents;

DROP TABLE IF EXISTS megatable;
CREATE TABLE megatable (
PdId BIGINT,
IncidntNum INT,
Incident_Code INT,
Category VARCHAR(255),
Descript VARCHAR(255),
DayOfWeek VARCHAR(255),
Incident_Date DATE,
Incident_Time TIME,
PdDistrict VARCHAR(255),
Resolution VARCHAR(255),
Address VARCHAR(255),
X FLOAT,
Y FLOAT,
location VARCHAR(255),
SF_Find_Neighborhoods_2_2 VARCHAR(255),
Current_Police_Districts_2_2 VARCHAR(255),
Current_Supervisor_Districts_2_2 VARCHAR(255),
Analysis_Neighborhoods_2_2 VARCHAR(255),
DELETE_Fire_Prevention_Districts_2_2 VARCHAR(255),
DELETE_Police_Districts_2_2 VARCHAR(255),
DELETE_Supervisor_Districts_2_2 VARCHAR(255),
DELETE_Zip_Codes_2_2 VARCHAR(255),
DELETE_Neighborhoods_2_2 VARCHAR(255),
DELETE_2017_Fix_It_Zones_2_2 VARCHAR(255),
Civic_Center_Harm_Reduction_Project_Boundary_2_2 VARCHAR(255),
Fix_It_Zones_as_of_2017_11_06_2_2 VARCHAR(255),
DELETE_HSOC_Zones_2_2 VARCHAR(255),
Fix_It_Zones_as_of_2018_02_07_2_2 VARCHAR(255),
CBD_BID_and_GBD_Boundaries_as_of_2017_2_2 VARCHAR(255),
Areas_of_Vulnerability_2016_2_2 VARCHAR(255),
Central_Market_Tenderloin_Boundary_2_2 VARCHAR(255),
Central_Market_Tenderloin_Boundary_Polygon_Updated_2_2 VARCHAR(255),
HSOC_Zones_as_of_2018_06_05_2_2 VARCHAR(255),
OWED_Public_Spaces_2_2 VARCHAR(255),
Neighborhoods_2 VARCHAR(255)
);

# LOAD DATA INFILE 'c:\\wamp64\\tmp\\police-department-incidents.csv'
LOAD DATA
    LOCAL INFILE '/Users/jonny/Documents/School/DBMS/project 2/police-department-incidents.csv'
    INTO TABLE megatable 
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;


# Decompose the megabtable into smaller tables

DROP TABLE IF EXISTS Description;
CREATE TABLE IF NOT EXISTS Description (
PdId BIGINT PRIMARY KEY,
IncidntNum INT,
Incident_Code INT,
Category VARCHAR(255),
Descript VARCHAR(255),
Resolution VARCHAR(255)
);

DROP TABLE IF EXISTS Crime_Time;
CREATE TABLE IF NOT EXISTS Crime_Time (
PdId BIGINT PRIMARY KEY,
DayOfWeek VARCHAR(255),
Descript VARCHAR(255),
Incident_Date DATE,
Incident_Time TIME
);

DROP TABLE IF EXISTS Location;
CREATE TABLE IF NOT EXISTS Location (
PdId BIGINT PRIMARY KEY,
PdDistrict VARCHAR(255),
Address VARCHAR(255),
X FLOAT,
Y FLOAT,
location VARCHAR(255)
);

DROP TABLE IF EXISTS Other;
CREATE TABLE IF NOT EXISTS Other (
PdId BIGINT PRIMARY KEY,
SF_Find_Neighborhoods_2_2 VARCHAR(255),
Current_Police_Districts_2_2 VARCHAR(255),
Current_Supervisor_Districts_2_2 VARCHAR(255),
Analysis_Neighborhoods_2_2 VARCHAR(255),
DELETE_Fire_Prevention_Districts_2_2 VARCHAR(255),
DELETE_Police_Districts_2_2 VARCHAR(255),
DELETE_Supervisor_Districts_2_2 VARCHAR(255),
DELETE_Zip_Codes_2_2 VARCHAR(255),
DELETE_Neighborhoods_2_2 VARCHAR(255)
);

DROP TABLE IF EXISTS Broken;
CREATE TABLE IF NOT EXISTS Broken (
PdId BIGINT PRIMARY KEY,
DELETE_2017_Fix_It_Zones_2_2 VARCHAR(255),
Civic_Center_Harm_Reduction_Project_Boundary_2_2 VARCHAR(255),
Fix_It_Zones_as_of_2017_11_06_2_2 VARCHAR(255),
DELETE_HSOC_Zones_2_2 VARCHAR(255),
Fix_It_Zones_as_of_2018_02_07_2_2 VARCHAR(255),
CBD_BID_and_GBD_Boundaries_as_of_2017_2_2 VARCHAR(255),
Areas_of_Vulnerability_2016_2_2 VARCHAR(255),
Central_Market_Tenderloin_Boundary_2_2 VARCHAR(255),
Central_Market_Tenderloin_Boundary_Polygon_Updated_2_2 VARCHAR(255),
HSOC_Zones_as_of_2018_06_05_2_2 VARCHAR(255),
OWED_Public_Spaces_2_2 VARCHAR(255)
);

# We must change sql modes because the table we're working on has broken data,
# and so we must group it to work around this.
# Specifically, each entry is duplicated: so for every entry in the megatable,
# there is another identical entry, including PdId, which is the primary key.
SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

INSERT INTO Description
SELECT PdId,
IncidntNum,
Incident_Code,
Category,
Descript,
Resolution
FROM megatable
GROUP BY PdId;

INSERT INTO Crime_Time
SELECT PdId,
DayOfWeek,
Descript,
Incident_Date,
Incident_Time
FROM megatable
GROUP BY PdId;

INSERT INTO Location
SELECT PdId,
PdDistrict,
Address,
X,
Y,
location
FROM megatable
GROUP BY PdId;

INSERT INTO Other
SELECT PdId,
SF_Find_Neighborhoods_2_2,
Current_Police_Districts_2_2,
Current_Supervisor_Districts_2_2,
Analysis_Neighborhoods_2_2,
DELETE_Fire_Prevention_Districts_2_2,
DELETE_Police_Districts_2_2,
DELETE_Supervisor_Districts_2_2,
DELETE_Zip_Codes_2_2,
DELETE_Neighborhoods_2_2
FROM megatable
GROUP BY PdId;

INSERT INTO Broken
SELECT PdId,
DELETE_2017_Fix_It_Zones_2_2,
Civic_Center_Harm_Reduction_Project_Boundary_2_2,
Fix_It_Zones_as_of_2017_11_06_2_2,
DELETE_HSOC_Zones_2_2,
Fix_It_Zones_as_of_2018_02_07_2_2,
CBD_BID_and_GBD_Boundaries_as_of_2017_2_2,
Areas_of_Vulnerability_2016_2_2,
Central_Market_Tenderloin_Boundary_2_2,
Central_Market_Tenderloin_Boundary_Polygon_Updated_2_2,
HSOC_Zones_as_of_2018_06_05_2_2,
OWED_Public_Spaces_2_2
FROM megatable
GROUP BY PdId;

SELECT * FROM Crime_Time;

# Stored Procedure that gets the crimes on a cetain day of the week

DROP PROCEDURE IF EXISTS getCrimesByDay;
DELIMITER //
CREATE PROCEDURE getCrimesByDay(IN  DayOfWeekEntered VARCHAR(255))
BEGIN 
SELECT * 
FROM Crime_Time
WHERE DayOfWeek = DayOfWeekEntered
LIMIT 100;
END //
DELIMITER ;

# Testing the procedure
CALL getCrimesByDay('Friday');

# Stored Procedure that gets the crimes on a cetain date

DROP PROCEDURE IF EXISTS getCrimesByDate;
DELIMITER //
CREATE PROCEDURE getCrimesByDate(IN EnteredDate DATE)
BEGIN 
SELECT Crime_Time.PdId AS PdId, DayOfWeek, Crime_Time.Descript AS Descript, Incident_Date, Incident_Time,
	IncidntNum, Incident_Code, Category, Resolution
FROM Crime_Time
	INNER JOIN Description USING (PdId)
WHERE Incident_Date = EnteredDate
ORDER BY Description.Descript ASC, Incident_Time ASC;
END //
DELIMITER ;

# Testing the procedure
CALL getCrimesByDate('2004-07-02');

# Stored Procedure that gets the crimes in a certain district

DROP PROCEDURE IF EXISTS getCrimesbyDistrict;
DELIMITER //
CREATE PROCEDURE getCrimesbyDistrict(IN EnteredDistrict VARCHAR(255))
BEGIN
SELECT * 
FROM Location
WHERE PdDistrict = EnteredDistrict;
END //
DELIMITER ;

# Testing the location
CALL getCrimesByDistrict('Bayview');

# Stored Procedure that gets the crimes by a certain category

DROP PROCEDURE IF EXISTS getCrimesbyCategory;
DELIMITER //
CREATE PROCEDURE getCrimesbyCategory(IN EnteredCategory VARCHAR(255))
BEGIN
SELECT * 
FROM Description
WHERE Category = EnteredCategory;
END //
DELIMITER ;

# Testing the location
CALL getCrimesByCategory('DRUG/NARCOTIC');

# Stored Procedure that gets the crimes based on latitude and longitude
DROP PROCEDURE IF EXISTS getCrimesByXY;
DELIMITER //
CREATE PROCEDURE getCrimesByXY(IN minx FLOAT, IN maxx FLOAT, IN miny FLOAT, IN maxy FLOAT)
BEGIN
SELECT Description.PdId AS PdId, Category, Description.Descript AS Descript, Resolution,
	PdDistrict, Address, X, Y, Incident_Date, Incident_Time
FROM Description
	INNER JOIN Location USING (PdId)
    INNER JOIN Crime_Time USING (PdId)
WHERE X >= minx AND X <= maxx AND Y >= miny AND Y <= maxy
ORDER BY Incident_Date DESC, Incident_Time DESC
LIMIT 50;
END //
DELIMITER ;

# Testing the above procedure
CALL getCrimesByXY(-122.514, -122.499, 37.7079, 37.78);


# Trigger
DROP PROCEDURE IF EXISTS validate_crime;
DELIMITER $$
CREATE PROCEDURE validate_crime (
	IN Crime_Date INT,
	IN Description VARCHAR(128)
)
DETERMINISTIC
NO SQL
BEGIN
	IF Crime_Date > CURDATE() THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Date must be a valid date';
	END IF;
	IF NOT (SELECT Description REGEXP '[A-Za-z0-9]') THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Please enter correct description';
	END IF;
END$$
DELIMITER ;

# Triggers that check for the validity of the data entered
DELIMITER $$
CREATE TRIGGER validate_crime_insert
BEFORE INSERT ON Crime_Time FOR EACH ROW
BEGIN
	CALL validate_crime(NEW.Incident_Date, NEW.Descript);
END$$
DELIMITER ;


# Stored procedure that gets crimes by the district they took place in
DROP PROCEDURE IF EXISTS getCrimesByDistrict;
DELIMITER //
CREATE PROCEDURE getCrimesbyDistrict(IN EnteredDistrict VARCHAR(255))
BEGIN
SELECT * 
FROM Location
WHERE PdDistrict = EnteredDistrict
LIMIT 100;
END //
DELIMITER ;

# Testing the location
CALL getCrimesByDistrict('Bayview');

# Stored Procedure that gets the crimes by a certain category

DROP PROCEDURE IF EXISTS getCrimesByCategory;
DELIMITER //
CREATE PROCEDURE getCrimesbyCategory(IN EnteredCategory VARCHAR(255))
BEGIN
SELECT * 
FROM Description
WHERE Category = EnteredCategory
LIMIT 100;
END //
DELIMITER ;

# Testing the location
CALL getCrimesByCategory('DRUG/NARCOTIC');


DROP PROCEDURE IF EXISTS deleteByPdId;
DELIMITER //
CREATE PROCEDURE deleteByPdId(IN id BIGINT)
BEGIN
	SELECT *
    FROM Description
    WHERE id=PdId;
	DELETE FROM Broken
	WHERE id = PdId;
    DELETE FROM Crime_Time
	WHERE id = PdId;
    DELETE FROM Description
	WHERE id = PdId;
    DELETE FROM Location
	WHERE id = PdId;
    DELETE FROM Other
	WHERE id = PdId;
END //
DELIMITER ;

CALL deleteByPdId(18036083516710);