<?php
header('Content-Type: application/json');

if(isset($_GET['movieID']))
{
  // Get ID of movie from form GET data
  $movieID = $_GET['movieID'];

  // 1. Connect to the database
  include "database.php";
  $db = connectToDatabase("myflix");
  if ($db->connect_error) {
    http_response_code(500);
    $message = array("error"=>TRUE, "message"=>"Failed to connect to database.");
    die(json_encode($message));  
  }

  // 2. Run the Query
  $query = "SELECT title,year,rated,imdbrating,description,image,genres,directors,writers,actors FROM movies WHERE id=?;";
  $stmt = simpleQueryParam($db, $query, "s", $movieID, FALSE);
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

  // Get the one movie from the result set
  $movieData = $result_set->fetch_array(MYSQLI_ASSOC);
  if ($movieData == NULL) {
    http_response_code(404);
    $message = array("error"=>TRUE, "message"=>"Movie not found.");
    die(json_encode($message));
  }

  // Output the movie data as a JSON object
  echo json_encode($movieData);

} else { // Not isset($_GET['movieID'])
  http_response_code(400);
  $message = array("error"=>TRUE, "message"=>"Movie ID required.");
  echo json_encode($message);
}
?>