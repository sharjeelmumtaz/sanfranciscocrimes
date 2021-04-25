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

      $prepared_stmt->bindValue(':PdId', $var, PDO::PARAM_STR);

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

    $submitted = false;

    if (isset($_POST['field_submit'])){
        $submitted = true;

        $var_pdid = $_POST["PdId"];

        $result = get_query("CALL deleteByPdId(:PdId)", $var_pdid);
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
    

    <h1> Delete an incident report </h1>

    <h2> Please enter the PdId of the incident you wish to delete: </h2>

    <form method="post">
      <input type="text" name="PdId" id="id_PdId" placeholder="PdId">
      <input type="submit" name="field_submit" value="Delete">
    </form>


    <?php 
      if ($submitted){
        if ($result) { ?>
            <h2>Successfully deleted the following entry from all tables:</h2>
            <table>
                <thead>
                  <tr>
                    <th>PdId</th>
                    <th>Incident Num</th>
                    <th>Incident Code</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Resolution</th>
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
            ?><h2> No entry found for PdId = <?php echo $var_pdid ?></h2><?php
        }
    }?>
    
  </body>
</html>