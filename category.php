<?php
ini_set('memory_limit', '-1'); #Memory limit has been disabled

  function init(){
    return true;
  }

  function get_query($query, $var_cat){

    require_once("conn.php");

    $result = "unexecuted";

    try
    {
      // Create a prepared statement. Prepared statements are a way to eliminate SQL INJECTION.
      $prepared_stmt = $dbo->prepare($query);

      $prepared_stmt->bindValue(':cat', $var_cat, PDO::PARAM_STR);

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

    $catSelected = "none";
    $dd = 0;

    if (isset($_POST['fDRUG/NARCOTIC'])){
      $result = get_query("CALL getCrimesByCategory(:cat)", "DRUG/NARCOTIC");
      $catSelected = "DRUG/NARCOTIC";
    }
    if (isset($_POST['fWARRANTS'])){
      $result = get_query("CALL getCrimesByCategory(:cat)", "WARRANTS");
      $catSelected = "WARRANTS";
    }
    if (isset($_POST['fVANDALISM'])){
      $result = get_query("CALL getCrimesByCategory(:cat)", "VANDALISM");
      $catSelected = "VANDALISM";
    }
    if (isset($_POST['fFORGERY/COUNTERFEITING'])){
      $result = get_query("CALL getCrimesByCategory(:cat)", "FORGERY/COUNTERFEITING");
      $catSelected = "FORGERY/COUNTERFEITING";
    }
    if (isset($_POST['fKIDNAPPING'])){
      $result = get_query("CALL getCrimesByCategory(:cat)", "KIDNAPPING");
      $catSelected = "KIDNAPPING";
    }
    if (isset($_POST['fNON-CRIMINAL'])){
      $result = get_query("CALL getCrimesByCategory(:cat)", "NON-CRIMINAL");
      $catSelected = "NON-CRIMINAL";
    }
    if (isset($_POST['fLARCENY/THEFT'])){
      $result = get_query("CALL getCrimesByCategory(:cat)", "LARCENY/THEFT");
      $catSelected = "LARCENY/THEFT";
    }
    if (isset($_POST['fASSAULT'])){
      $result = get_query("CALL getCrimesByCategory(:cat)", "ASSAULT");
      $catSelected = "ASSAULT";
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


    <h1> When categories are crimes committed in? </h1>

    <h2> Select a category: </h2>


    <form method="post">
      <input type="submit" value="DRUG/NARCOTIC" name="fDRUG/NARCOTIC">
      <input type="submit" value="WARRANTS" name="fWARRANTS">
      <input type="submit" value="VANDALISM" name="fVANDALISM">
      <input type="submit" value="FORGERY/COUNTERFEITING" name="fFORGERY/COUNTERFEITING">
      <input type="submit" value="KIDNAPPING" name="fKIDNAPPING">
      <input type="submit" value="NON-CRIMINAL" name="fNON-CRIMINAL">
      <input type="submit" value="LARCENY/THEFT" name="fLARCENY/THEFT">
      <input type="submit" value="ASSAULT" name="fASSAULT">
    </form>

    <?php
      if ($catSelected == "none") {
        ?><h1> No category selected. </h1><?php
      }else{
        ?><h1> Showing results for <?php echo $catSelected; ?>:</h1><?php
        if ($result) { ?>
          <h2>Results</h2>
              <table>
                <thead>
                  <tr>
                    <th>PdId</th>
                    <th>IncidntNum</th>
                    <th>Incident_Code</th>
                    <th>Category</th>
                    <th>Descript</th>
                    <th>Resolution </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($result as $row) { ?>
                    <tr>
                      <td><?php echo $row["PdId"]; ?></td>
                      <td><?php echo $row["IncidntNum"]; ?></td>
                      <td><?php echo $row["Incident_Code"]; ?></td>
                      <td><?php echo $row["Category"]; ?></td>
                      <td><?php echo $row["Descript"]; ?></td>
                      <td><?php echo $row["Resolution"]; ?></td>
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