<?php

  function init(){
    return true;
  }

  function get_query($query, $minlong, $maxlong, $minlat, $maxlat){

    require_once("conn.php");

    $result = "unexecuted";

    try
    {
      $prepared_stmt = $dbo->prepare($query);

      // INSERT VARIABLE INTO SQL QUERY
      $prepared_stmt->bindValue(':minx', $minlong, PDO::PARAM_STR);
      $prepared_stmt->bindValue(':maxx', $maxlong, PDO::PARAM_STR);
      $prepared_stmt->bindValue(':miny', $minlat, PDO::PARAM_STR);
      $prepared_stmt->bindValue(':maxy', $maxlat, PDO::PARAM_STR);

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


// Runs upon init. Define variables here.

  if (!defined($init_run)){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $init_run = false;
    $init_run = init();

    $daySelected = "none";
    $dd = 0;

    $submitted = false;

    $slider1 = -122514;
    $slider2 = -122365;
    $slider3 = 37707;
    $slider4 = 37821;

    // BUTTONS. isset($_POST['button_name']) is true if the button has been pressed, false otherwise.
    if (isset($_POST['getsliders'])){
      $submitted = true;

      $slider1 = $_POST["minlong"];
      $slider2 = $_POST["maxlong"];
      $slider3 = $_POST["minlat"];
      $slider4 = $_POST["maxlat"];

      $minlong = $slider1 / 1000;
      $maxlong = $slider2 / 1000;
      $minlat = $slider3 / 1000;
      $maxlat = $slider4 / 1000;

      $result = get_query("CALL getCrimesByXY(:minx, :maxx, :miny, :maxy)", $minlong, $maxlong, $minlat, $maxlat);
    }else if (isset($_POST["updatesliders"])){
      $slider1 = $_POST["minlong"];
      $slider2 = $_POST["maxlong"];
      $slider3 = $_POST["minlat"];
      $slider4 = $_POST["maxlat"];
    }else{
      $minlong = -122514 / 1000;
      $maxlong = -122365 / 1000;
      $minlat = 37707 / 1000;
      $maxlat = 37821 / 1000;
    }

    # $slider1 = $_POST["minlong"];
    # $slider2 = $_POST["maxlong"];
    # $slider3 = $_POST["minlat"];
    # $slider4 = $_POST["maxlat"];


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

    <!-- Begin defining the body of the page here: -->
    <h1> Where are crimes committed? </h1>

    <h2> Specify the coordinates using the sliders: </h2>

    
    <div id="map container" width=90%>
      
      <!-- You must divide the results from these sliders by 1000. -->
      <!-- The value of such measurements is a decimal, and we are working with ints in our sliders. -->
      <form method="post">
        <div>
          <input type="range" class="slider" min="-122514" max="-122365" value="<?php echo $slider1; ?>" id = 1 name="minlong" width=40%>
          <body>minimum longitude: <?php echo $slider1 / 1000 ?></body>
          <input type="range" class="slider" min="-122514" max="-122365" value="<?php echo $slider2; ?>" id = 2 name="maxlong" width=40%>
          <body>maximum longitude: <?php echo $slider2 / 1000 ?></body>
          <input type="range" class="slider" min="37707" max="37821" value="<?php echo $slider3; ?>" id = 3 name="minlat" width=40%>
          <body>minimum latitude: <?php echo $slider3 / 1000 ?></body>
          <input type="range" class="slider" min="37707" max="37821" value="<?php echo $slider4; ?>" id = 4 name="maxlat" width=40%>
          <body>maximum latitude: <?php echo $slider4 / 1000 ?></body>
        </div> 
        <div>
          <input type="submit" value="Update Sliders" name="updatesliders">
          <input type="submit" value="Run Query" name="getsliders">
        </div>
      </form>
      
      <img src="region.png" alt="Map" width=100%%>
    <div>

    <?php
      if (isset($result)){
        if ($result == "failure") {
          ?><h1>Unable to retrieve rows.</h1><?php
        }else{
          ?><h1> Showing results:</h1><?php
          if ($result) { ?>
            <h2>Results</h2>
                <table>
                  <thead>
                    <tr>
                      <th>Category</th>
                      <th>Description</th>
                      <th>Resolution</th>
                      <th>District</th>
                      <th>Address</th>
                      <th>X</th>
                      <th>Y</th>
                      <th>Date</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($result as $row) { ?>
                      <tr>
                        <td><?php echo $row["Category"]; ?></td>
                        <td><?php echo $row["Descript"]; ?></td>
                        <td><?php echo $row["Resolution"]; ?></td>
                        <td><?php echo $row["PdDistrict"]; ?></td>
                        <td><?php echo $row["Address"]; ?></td>
                        <td><?php echo $row["X"]; ?></td>
                        <td><?php echo $row["Y"]; ?></td>
                        <td><?php echo $row["Incident_Date"]; ?></td>
                        <td><?php echo $row["Incident_Time"]; ?></td>
                     </tr>
                    <?php } ?>
                  </tbody>
                </table>
            <?php
          }else{
            ?> <h2> Error showing results. </h2> <?php
          }
        }
      }
    ?>

  </body>
</html>

