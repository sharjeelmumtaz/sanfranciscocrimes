<?php

  function init(){
    return true;
  }

  function get_query($query, $var_day){

    require_once("conn.php");

    $result = "unexecuted";

    try
    {
      // Create a prepared statement. Prepared statements are a way to eliminate SQL INJECTION.
      $prepared_stmt = $dbo->prepare($query);

      $prepared_stmt->bindValue(':day', $var_day, PDO::PARAM_STR);

      $prepared_stmt->execute();
      // Fetch all the values based on query and save that to variable $result
      $result = $prepared_stmt->fetchAll();
    }
    catch (PDOException $ex)
    { // Error in database processing.
      echo $sql . "<br>" . $error->getMessage(); // HTTP 500 - Internal Server Error
      $result = "failure";
    }

    return $result;
  }


// Runs upon init. Stuff needs to be defined in here to work.

  if (!defined($init_run)){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $init_run = false;
    $init_run = init();

    $daySelected = "none";
    $dd = 0;

    if (isset($_POST['fsunday'])){
      $result = get_query("CALL getCrimesByDay(:day)", "Sunday");
      $daySelected = "Sunday";
    }
    if (isset($_POST['fmonday'])){
      $result = get_query("CALL getCrimesByDay(:day)", "Monday");
      $daySelected = "Monday";
    }
    if (isset($_POST['ftuesday'])){
      $result = get_query("CALL getCrimesByDay(:day)", "Tuesday");
      $daySelected = "Tuesday";
    }
    if (isset($_POST['fwednesday'])){
      $result = get_query("CALL getCrimesByDay(:day)", "Wednesday");
      $daySelected = "Wednesday";
    }
    if (isset($_POST['fthursday'])){
      $result = get_query("CALL getCrimesByDay(:day)", "Thursday");
      $daySelected = "Thursday";
    }
    if (isset($_POST['ffriday'])){
      $result = get_query("CALL getCrimesByDay(:day)", "Friday");
      $daySelected = "Friday";
    }
    if (isset($_POST['fsaturday'])){
      $result = get_query("CALL getCrimesByDay(:day)", "Saturday");
      $daySelected = "Saturday";
    }

  }
?>

<html>
<!-- Any thing inside the HEAD tags are not visible on page.-->
  <head>
		<meta name="google" content="notranslate">
		<!-- THe following is the stylesheet file. The CSS file decides look and feel -->
		<link rel="stylesheet" type="text/css" href="project.css" />
	</head>

	<body>
    <!-- BACK TO LANDING -->
		<div id="navbar">
			<h1><a href="index.html">Home</a></h1>
		</div>
    <?php

    ?>


    <h1> When are crimes committed? </h1>

    <h2> Select a day: </h2>


    <form method="post">
      <input type="submit" value="Sunday" name="fsunday">
      <input type="submit" value="Monday" name="fmonday">
      <input type="submit" value="Tuesday" name="ftuesday">
      <input type="submit" value="Wednesday" name="fwednesday">
      <input type="submit" value="Thursday" name="fthursday">
      <input type="submit" value="Friday" name="ffriday">
      <input type="submit" value="Saturday" name="fsaturday">
    </form>

    <?php
      if ($daySelected == "none") {
        ?><h1> No day selected. </h1><?php
      }else{
        ?><h1> Showing results for <?php echo $daySelected; ?>:</h1><?php
        if ($result) { ?>
          <h2>Results</h2>
              <table>
                <thead>
                  <tr>
                    <th>PdId</th>
                    <th>DayOfWeek</th>
                    <th>Description</th>
                    <th>Incident_Date</th>
                    <th>Incident_Time</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($result as $row) { ?>
                    <tr>
                      <td><?php echo $row["PdId"]; ?></td>
                      <td><?php echo $row["DayOfWeek"]; ?></td>
                      <td><?php echo $row["Descript"]; ?></td>
                      <td><?php echo $row["Incident_Date"]; ?></td>
                      <td><?php echo $row["Incident_Time"]; ?></td>
                   </tr>
                  <?php } ?>
                </tbody>
              </table>
          <?php
        }else{
          ?> <h2> Error retrieving rows. </h2> <?php
        }
      }
    ?>

    <?php
      if (isset($_POST['field_submit'])) {
        // If the query executed (result is true) and the row count returned from the query is greater than 0 then...
        if ($result && $prepared_stmt->rowCount() > 0) { ?>
              <!-- first show the header RESULT -->


        <?php } else { ?>
          <h3>Sorry, no results found for table. </h3>
        <?php }
    } else {

    } ?>



  </body>
</html>
