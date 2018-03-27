<?php
if ( !isset($_GET['name']) || strlen($_GET['name']) < 1) {
  die('Name parameter missing');
}

if ( isset($_POST['logout']) ) {
  header('Location: index.php');
  return;
}

require_once "pdo.php";

$failure = false;
$success = false;

if ( isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
  if ( !is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
    $failure = 'Mileage and year must be numeric';
  }
  else if ( strlen($_POST['make']) < 1) {
    $failure = 'Make is required';
  }
  else {
    $stmt = $pdo->prepare('INSERT INTO Autos
    (make, year, mileage) VALUES ( :mk, :yr, :mi)');
    $stmt->execute(array(
      ':mk' => $_POST['make'],
      ':yr' => $_POST['year'],
      ':mi' => $_POST['mileage'])
    );
    $success = 'Record inserted';
  }
}

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
 ?>

 <!DOCTYPE html>
 <html>
 <head>
   <title>Zuzana autos</title>
 </head>
 <body>
   <h1>Tracking autos for</h1>
   <?php
    if ( isset($_REQUEST['name'])) {
      echo ("<h2>".htmlentities($_REQUEST['name'])."</h2>\n");
    }
    if ($failure !== false) {
      echo ('<p style="color:red;">'.htmlentities($failure)."</p>\n");
    }
    if ($success !== false) {
      echo ('<p style="color:green;">'."Record inserted"."</p>\n");
    }
    ?>
   <form method="POST">
     <label for="make">Make:</label>
     <input type="text" name="make" id="make"><br/>
     <label for="year">Year:</label>
     <input type="text" name="year" id="year"></br>
     <label for="mileage">Mileage:</label>
     <input type="text" name="mileage" id="mileage"></br>
     <input type="submit" value="Add">
     <input type="submit" name="logout" value="Logout">
   </form>
   <h3>Automobiles</h3>
   <table border="1">
     <?php
     foreach( $rows as $row) {
       echo "<tr><td>";
       echo ($row['make']);
       echo ("</td><td>");
       echo ($row['year']);
       echo ("</td><td>");
       echo ($row['mileage']);
       echo ("</td></tr>\n");
     }
      ?>
 </body>
 </html>
