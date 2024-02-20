<?php
// Set a json content type
header('Content-Type: application/json');

// Establish the database connection
include "../../../database.php"; 
$db = connectToDatabase("tabletop");
if($db == NULL) {
  http_response_code(500);
  $message = array("error"=>TRUE, "message"=>"Failed to connect to database.");
  die(json_encode($message));
}

//Tries to get the ID from the URL to get a specific board game, otherwise it will get all board games
if (isset($_GET['ID'])) {
  $ID = $_GET['ID'];
  //GET SPECIFIC BOARD GAME
  // Prepares a query for the basic movie information
  $stmt = $db->prepare("SELECT `ID`, `Name`, `Description`, `YearReleased`, `BGG_Rating`, `MinPlayers`, `MaxPlayers`, `MinPlayTime`, `MaxPlayTime`, `MinAge`, `GameWeight`, `Designers`, `Artists`, `Publishers`, `ImageURL` FROM `BoardGames` WHERE ID = ?");
  $stmt->bind_param("i", $ID); // "i" means the parameter is an integer
  if($stmt == NULL) {
    http_response_code(500);
    $message = array("error"=>TRUE, "message"=>"SQL Query Error");
    die(json_encode($message));
  }

  // Execute the query
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result == NULL) {
    http_response_code(500);
    $message = array("error"=>TRUE, "message"=>"Failed to get result set.");
    die(json_encode($message));
  }

  // Get the first row from the result set
  $Data = $result->fetch_assoc();
  if ($Data == NULL) {
    http_response_code(500);
    $message = array("error"=>TRUE, "message"=>"Failed to fetch all rows.");
    die(json_encode($message));
  }

  // Return the data as a JSON object
  echo json_encode($Data);

} else {
  //GET ALL SLIM BOARD GAMES
  // Prepare and execute a query for the basic movie information
  $stmt = simpleQuery($db, "SELECT `ID`, `Name`, `Description`, `YearReleased`, `BGG_Rating`, `ImageURL` FROM `BoardGames` WHERE 1;", FALSE);
  if($stmt == NULL) {
    http_response_code(500);
    $message = array("error"=>TRUE, "message"=>"SQL Query Error");
    die(json_encode($message));
  }

  // Get the result set
  $result_set = $stmt->get_result();
  if ($result_set == NULL) {
    http_response_code(500);
    $message = array("error"=>TRUE, "message"=>"Failed to get result set.");
    die(json_encode($message));
  }

  // Get all the rows from the result set
  $Data = $result_set->fetch_all(MYSQLI_ASSOC);
  if ($Data == NULL) {
    http_response_code(500);
    $message = array("error"=>TRUE, "message"=>"Failed to fetch all rows.");
    die(json_encode($message));
  }

  // Return the data as a JSON object
  echo json_encode($Data);
}

// Close the database connection
$stmt->close();
$db->close();
?>