
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
X INT,
Y INT,
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

LOAD DATA INFILE 'c:\\wamp64\\tmp\\police-department-incidents.csv'
INTO TABLE megatable
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES;



