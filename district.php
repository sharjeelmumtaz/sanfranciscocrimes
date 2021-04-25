<?php
ini_set('memory_limit', '-1'); #Memory limit has been disabled

  function init(){
    return true;
  }

  function get_query($query, $var_dist){

    require_once("conn.php");

    $result = "unexecuted";

    try
    {
      // Create a prepared statement. Prepared statements are a way to eliminate SQL INJECTION.
      $prepared_stmt = $dbo->prepare($query);

      $prepared_stmt->bindValue(':dist', $var_dist, PDO::PARAM_STR);

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
    $init_run = false;
  if (!defined($init_run)){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    #$init_run = false;
    $init_run = init();

    $distSelected = "none";
    $dd = 0;

    if (isset($_POST['fBayview'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Bayview");
      $distSelected = "Bayview";
    }
    if (isset($_POST['fIngleside'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Ingleside");
      $distSelected = "Ingleside";
    }
    if (isset($_POST['fMission'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Mission");
      $distSelected = "Mission";
    }
    if (isset($_POST['fNorthern'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Northern");
      $distSelected = "Northern";
    }
    if (isset($_POST['fPark'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Park");
      $distSelected = "Park";
    }
    if (isset($_POST['fRichmond'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Richmond");
      $distSelected = "Richmond";
    }
    if (isset($_POST['fTaraval'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Taraval");
      $distSelected = "Taraval";
    }
    if (isset($_POST['fTenderloin'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Tenderloin");
      $distSelected = "Tenderloin";
    }
    if (isset($_POST['fSouthern'])){
      $result = get_query("CALL getCrimesByDistrict(:dist)", "Southern");
      $distSelected = "Southern";
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


    <h1> When districts are crimes committed in? </h1>

    <h2> Select a district: </h2>


    <form method="post">
      <input type="submit" value="Bayview" name="fBayview">
      <input type="submit" value="Ingleside" name="fIngleside">
      <input type="submit" value="Mission" name="fMission">
      <input type="submit" value="Northern" name="fNorthern">
      <input type="submit" value="Park" name="fPark">
      <input type="submit" value="Richmond" name="fRichmond">
      <input type="submit" value="Taraval" name="fTaraval">
      <input type="submit" value="Tenderloin" name="fTenderloin">
      <input type="submit" value="Southern" name="fSouthern">
    </form>

    <?php
      if ($distSelected == "none") {
        ?><h1> No district selected. </h1><?php
      }else{
        ?><h1> Showing results for <?php echo $distSelected; ?>:</h1><?php
        if ($result) { ?>
          <h2>Results</h2>
              <table>
                <thead>
                  <tr>
                    <th>PdId</th>
                    <th>PdDistrict</th>
                    <th>Address</th>
                    <th>X</th>
                    <th>Y</th>
                    <th>location</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($result as $row) { ?>
                    <tr>
                      <td><?php echo $row["PdId"]; ?></td>
                      <td><?php echo $row["PdDistrict"]; ?></td>
                      <td><?php echo $row["Address"]; ?></td>
                      <td><?php echo $row["X"]; ?></td>
                      <td><?php echo $row["Y"]; ?></td>
                      <td><?php echo $row["location"]; ?></td>
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