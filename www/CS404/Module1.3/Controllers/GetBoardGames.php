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

//get ID from the url param
if (isset($_GET['ID'])) {
  $ID = $_GET['ID'];
} else {
  $ID = NULL;
}
// Prepare and execute a query for the basic movie information
$stmt = simpleQuery($db, "SELECT `ID`, `Name`, `Description`, `YearReleased`, `BGG_Rating`, `MinPlayers`, `MaxPlayers`, `MinPlayTime`, `MaxPlayTime`, `MinAge`, `GameWeight`, `Designers`, `Artists`, `Publishers`, `ImageURL` FROM `BoardGames` WHERE 1;", FALSE);
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

// If the ID is set, filter out the data to only include the requested ID
if ($ID != NULL) {
  $Data = array_filter($Data, function($row) use ($ID) {
    return $row['ID'] == $ID;
  });
  $Data = array_values($Data);
}

// Return the data as a JSON object
echo json_encode($Data);

// Close the database connection
$stmt->close();
$db->close();
?>