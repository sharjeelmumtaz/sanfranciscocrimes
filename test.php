<?php

  ini_set(‘display_errors’, 1);
  ini_set(‘display_startup_errors’, 1);
  error_reporting(E_ALL);

  function init(){
    $werun = "This ran, and so did init().";
    return $werun;
  }

  // Gets the days of the week in the left table, and how many crimes have occurred on each day
  // Refer to conn.php file and open a connection.
  // If the all the variables are set when the Submit button is clicked...
  if (isset($_POST['field_submit'])) {
    // Refer to conn.php file and open a connection.
    require_once("conn.php");
    // get the query
    $query = "SELECT * FROM megatable LIMIT 30";

    try
    {
      // Create a prepared statement. Prepared statements are a way to eliminate SQL INJECTION.
      $prepared_stmt = $dbo->prepare($query);
      $prepared_stmt->execute();
      // Fetch all the values based on query and save that to variable $result
      $result = $prepared_stmt->fetchAll();

      $queryRun = true;
      $success = true;
    }
    catch (PDOException $ex)
    { // Error in database processing.
      echo $sql . "<br>" . $error->getMessage(); // HTTP 500 - Internal Server Error
    }
  }

  if (isset($_POST['button1'])){
    $count2 = $count2 + 1;
  }
  if (isset($_POST['button2'])){
    $count2 = $count2 - 1;
  }


  if (!defined($init_run)){
    $init_run = false;
    $count = 0;
    $count2 = 5;

    $werun = "this ran";
    $werun = init();

    $init_run = true;
    $count = $count + 1;
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
    

    <h1> When are crimes committed? </h1>
    <!-- This is the start of the form. This form has one text field and one button.
      See the project.css file to note how form is stylized.-->
    <?php
      if (isset($_POST['field_submit'])) {
        $gbp = 5;
      }else{
        $gbp = 0;
      }
      if (isset($_POST['button1'])) {
        $b1 = 5;
        $count2 = $count2 + 1;
      }else{
        $b1 = 0;
      }
      if (isset($_POST['button2'])) {
        $b2 = 5;
        $count2 = $count2 - 1;
      }else{
        $b2 = 0;
      }
    ?>

    <h3>Submit pressed: <?php echo $gbp ?> </h3>
    <h3>b1 pressed: <?php echo $b1 ?>  </h3>
    <h3>b2 pressed: <?php echo $b2 ?>  </h3>

    <?php if ($queryRun){
    ?>
       <div class="row">
        <div class="column">
          <list>
            <li>Sunday</li>
            <li>mon</li>
            <li>tuesdee</li>
            <li>Sunday</li>
            <li>Sunday</li>
            <li>Sunday</li>
            <li>Saturday</li>
          </list>
        </div>
        <div class="column">
          <list>
            <li>1</li>
            <li>2</li>
            <li>3</li>
            <li>4</li>
            <li>5</li>
            <li>6</li>
            <li>7</li>
          </list>
        </div>
      </div> 
    <?php } else { ?>
      <h2>Loading...</h2>
      <h2>werun: <?php echo $werun ?></h2>
      <h2>count: <?php echo $count ?></h2>
      <h2>count2: <?php echo $count2 ?></h2>
    <?php } ?>

    <form method="post">
      <!-- The input type is a submit button. Note the name and value. The value attribute decides what will be dispalyed on Button. In this case the button shows Submit.
      The name attribute is referred  on line 3 and line 61. -->
      <input type="submit" name="field_submit" value="Get">
    </form>
    <form method="post">
      <input type="submit" name="button1" value="1">
    </form>
    <form method="post">
      <input type="submit" name="button2" value="2">
    </form>
    
    <?php
      if (isset($_POST['field_submit'])) {
        // If the query executed (result is true) and the row count returned from the query is greater than 0 then...
        if ($result && $prepared_stmt->rowCount() > 0) { ?>
              <!-- first show the header RESULT -->
              <h2>Results</h2>
              <!-- THen create a table like structure. See the project.css how table is stylized. -->
              <table>
                <!-- Create the first row of table as table head (thead). -->
                <thead>
                   <!-- The top row is table head with four columns named -- ID, Title ... -->
                  <tr>
                    <th>Incident Num</th>
                    <th>Incident Code</th>
                    <th>Category</th>
                    <th>Description</th>
                  </tr>
                </thead>
                 <!-- Create rest of the the body of the table -->
                <tbody>
                   <!-- For each row saved in the $result variable ... -->
                  <?php foreach ($result as $row) { ?>
                
                    <tr>
                       <!-- Print (echo) the value of mID in first column of table -->
                      <td><?php echo $row["IncidntNum"]; ?></td>
                      <td><?php echo $row["Incident_Code"]; ?></td>
                      <td><?php echo $row["Category"]; ?></td>
                      <td><?php echo $row["Descript"]; ?></td>
                   </tr>
                  <?php } ?>
                  <!-- End table body -->
                </tbody>
                <!-- End table -->
            </table>
  
        <?php } else { ?>
          <!-- IF query execution resulted in error display the following message-->
          <h3>Sorry, no results found for table. </h3>
        <?php }
    } else {
      ?>

      <h1>We have not clicked anything yet</h1>

    <?php
    } ?>


    
  </body>
</html>







