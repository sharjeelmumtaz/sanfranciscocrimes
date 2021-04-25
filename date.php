<?php

  function init(){
    return true;
  }

  function get_query($query, $var){

    require_once("conn.php");
    
    $result = "unexecuted";

    try
    {
      // Create a prepared statement. Prepared statements are a way to eliminate SQL INJECTION.
      $prepared_stmt = $dbo->prepare($query);

      $prepared_stmt->bindValue(':date', $var, PDO::PARAM_STR);

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

  if (!$init_run){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $init_run = init();

    $var_day = "";
    $var_month = "";
    $var_year = "";

    $submitted = false;

    if (isset($_POST['field_submit'])){
      $submitted = true;

      $var_day = $_POST["day"];
      $var_month = $_POST["month"];
      $var_year = $_POST["year"];

      $total_date = $var_year . "-" . $var_month . "-" . $var_day;

      $validEntry = false;
      if (strlen($var_day) == 2 && strlen($var_month) == 2 && strlen($var_year) == 4){
        $validEntry = true;
      }
      if ($validEntry){
        $result = get_query("CALL getCrimesByDate(:date)", $total_date);
      }
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
    

    <h1> What crimes were committed on this date? </h1>

    <h2> Enter a date: </h2>

    <div>
      <body> Results range from 01-01-2003 to 15-05-2018 (dd-mm-yyyy) </body>
    </div>
    <br></br>

    <form method="post">
      <input type="text" name="day" id="id_day" placeholder="dd">
      <input type="text" name="month" id="id_month" placeholder="mm">
      <input type="text" name="year" id="id_year" placeholder="yyyy">
      <input type="submit" name="field_submit" value="Search date:">
    </form>


    <?php 
      if ($submitted){
        if ($validEntry){
          if ($result) { ?>
            <h2>Showing crimes for <?php echo $total_date ?>:</h2>
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
            ?> <h2> No crimes found for <?php echo $total_date ?>.</h2> <?php
          }
        }else{
          ?><h3>Please enter your data with the correct format [dd][mm][yyyy]. All numbers.</h3><?php
        }
      }else{
        
      }
    ?>

    
  </body>
</html>







