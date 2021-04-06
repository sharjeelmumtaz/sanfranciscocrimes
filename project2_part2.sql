
DROP DATABASE IF EXISTS SFIncidents;
CREATE DATABASE SFIncidents;
USE SFIncidents;

DROP TABLE IF EXISTS megatable;
CREATE TABLE megatable (
PdId INT,
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
SF_Find_Neighborhoods_2_2 INT,
Current_Police_Districts_2_2 INT,
Current_Supervisor_Districts_2_2 INT,
Analysis_Neighborhoods_2_2 INT,
DELETE_Fire_Prevention_Districts_2_2 INT,
DELETE_Police_Districts_2_2 INT,
DELETE_Supervisor_Districts_2_2 INT,
DELETE_Zip_Codes_2_2 INT,
DELETE_Neighborhoods_2_2 INT,
DELETE_2017_Fix_It_Zones_2_2 INT,
Civic_Center_Harm_Reduction_Project_Boundary_2_2 INT,
Fix_It_Zones_as_of_2017_11_06_2_2 INT,
DELETE_HSOC_Zones_2_2 INT,
Fix_It_Zones_as_of_2018_02_07_2_2 INT,
CBD_BID_and_GBD_Boundaries_as_of_2017_2_2 INT,
Areas_of_Vulnerability_2016_2_2 INT,
Central_Market_Tenderloin_Boundary_2_2 INT,
Central_Market_Tenderloin_Boundary_Polygon_Updated_2_2 INT,
HSOC_Zones_as_of_2018_06_05_2_2 INT,
OWED_Public_Spaces_2_2 INT,
Neighborhoods_2 INT
);



