<?php
// Set a json content type
header('Content-Type: application/json');

// Establish the database connection
include "../../database.php"; 
$db = connectToDatabase("myFlix");
if($db == NULL) {
  http_response_code(500);
  $message = array("error"=>TRUE, "message"=>"Failed to connect to database.");
  die(json_encode($message));
}

// Prepare and execute a query for the basic movie information
$stmt = simpleQuery($db, "SELECT id, title, year, genres, image, rated FROM movies;", FALSE);
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
$movieData = $result_set->fetch_all(MYSQLI_ASSOC);
if ($movieData == NULL) {
  http_response_code(500);
  $message = array("error"=>TRUE, "message"=>"Failed to fetch all rows.");
  die(json_encode($message));
}

// Return the movie data as a JSON object
echo json_encode($movieData);

// Close the database connection
$stmt->close();
$db->close();
?>