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

$data = json_decode(file_get_contents('php://input'), true);

//get the data from the request
$Name = isset($data['Name']) ? $data['Name'] : '';
$Description = isset($data['Description']) ? $data['Description'] : '';
$YearReleased = isset($data['YearReleased']) ? $data['YearReleased'] : '';
$BGG_Rating = isset($data['BGG_Rating']) ? $data['BGG_Rating'] : '';
$MinPlayers = isset($data['MinPlayers']) ? $data['MinPlayers'] : '';
$MaxPlayers = isset($data['MaxPlayers']) ? $data['MaxPlayers'] : '';
$MinPlayTime = isset($data['MinPlayTime']) ? $data['MinPlayTime'] : '';
$MaxPlayTime = isset($data['MaxPlayTime']) ? $data['MaxPlayTime'] : '';
$MinAge = isset($data['MinAge']) ? $data['MinAge'] : '';
$GameWeight = isset($data['GameWeight']) ? $data['GameWeight'] : '';
$Designers = isset($data['Designers']) ? $data['Designers'] : '';
$Artists = isset($data['Artists']) ? $data['Artists'] : '';
$Publishers = isset($data['Publishers']) ? $data['Publishers'] : '';
$ImageURL = isset($data['ImageURL']) ? $data['ImageURL'] : '';

$missingFields = [];

if ($Name == NULL) $missingFields[] = 'Name';
if ($Description == NULL) $missingFields[] = 'Description';
if ($YearReleased == NULL) $missingFields[] = 'YearReleased';
if ($BGG_Rating == NULL) $missingFields[] = 'BGG_Rating';
if ($MinPlayers == NULL) $missingFields[] = 'MinPlayers';
if ($MaxPlayers == NULL) $missingFields[] = 'MaxPlayers';
if ($MinPlayTime == NULL) $missingFields[] = 'MinPlayTime';
if ($MaxPlayTime == NULL) $missingFields[] = 'MaxPlayTime';
if ($MinAge == NULL) $missingFields[] = 'MinAge';
if ($GameWeight == NULL) $missingFields[] = 'GameWeight';
if ($Designers == NULL) $missingFields[] = 'Designers';
if ($Artists == NULL) $missingFields[] = 'Artists';
if ($Publishers == NULL) $missingFields[] = 'Publishers';
if ($ImageURL == NULL) $missingFields[] = 'ImageURL';

// validate not null fields
if (!empty($missingFields)) {
  $message = array("status"=>"Failure", "message"=>"Missing required fields: " . implode(', ', $missingFields));
  die(json_encode($message));
}

//More validation
if (!is_numeric($YearReleased)) {
  $message = array("status"=>"Failure", "message"=>"YearReleased must be a number");
  die(json_encode($message));
}

if (!is_numeric($BGG_Rating)) {
  $message = array("status"=>"Failure", "message"=>"BGG_Rating must be a number");
  die(json_encode($message));
}

if (!is_numeric($MinPlayers)) {
  $message = array("status"=>"Failure", "message"=>"MinPlayers must be a number");
  die(json_encode($message));
}

if (!is_numeric($MaxPlayers)) {
  $message = array("status"=>"Failure", "message"=>"MaxPlayers must be a number");
  die(json_encode($message));
}

if (!is_numeric($MinPlayTime)) {
  $message = array("status"=>"Failure", "message"=>"MinPlayTime must be a number");
  die(json_encode($message));
}

if (!is_numeric($MaxPlayTime)) {
  $message = array("status"=>"Failure", "message"=>"MaxPlayTime must be a number");
  die(json_encode($message));
}

if (!is_numeric($MinAge)) {
  $message = array("status"=>"Failure", "message"=>"MinAge must be a number");
  die(json_encode($message));
}

if (!is_numeric($GameWeight)) {
  $message = array("status"=>"Failure", "message"=>"GameWeight must be a number");
  die(json_encode($message));
}

//check if any negative numbers
if ($YearReleased < 0 || $BGG_Rating < 0 || $MinPlayers < 0 || $MaxPlayers < 0 || $MinPlayTime < 0 || $MaxPlayTime < 0 || $MinAge < 0 || $GameWeight < 0) {
  $message = array("status"=>"Failure", "message"=>"positive numbers only!");
  die(json_encode($message));
}

// BGG_Rating and game weight must be between 0 and 10
if ($BGG_Rating < 0 || $BGG_Rating > 10) {
  $message = array("status"=>"Failure", "message"=>"BGG_Rating must be between 0 and 10");
  die(json_encode($message));
}

if ($GameWeight < 0 || $GameWeight > 5) {
  $message = array("status"=>"Failure", "message"=>"GameWeight must be between 0 and 5");
  die(json_encode($message));
}

//min cant be greater than max
if ($MinPlayers > $MaxPlayers) {
  $message = array("status"=>"Failure", "message"=>"MinPlayers must be less than MaxPlayers");
  die(json_encode($message));
}

if ($MinPlayTime > $MaxPlayTime) {
  $message = array("status"=>"Failure", "message"=>"MinPlayTime must be less than MaxPlayTime");
  die(json_encode($message));
}

// Prepare and execute a query for the basic movie information
$sql = "INSERT INTO `BoardGames`(`Name`, `Description`, `YearReleased`, `BGG_Rating`, `MinPlayers`, `MaxPlayers`, `MinPlayTime`, `MaxPlayTime`, `MinAge`, `GameWeight`, `Designers`, `Artists`, `Publishers`, `ImageURL`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($sql);
$stmt->bind_param("ssidiiiiidssss", $Name, $Description, $YearReleased, $BGG_Rating, $MinPlayers, $MaxPlayers, $MinPlayTime, $MaxPlayTime, $MinAge, $GameWeight, $Designers, $Artists, $Publishers, $ImageURL);

if($stmt->execute() === FALSE) {
  http_response_code(500);
  $message = array("error"=>TRUE, "message"=>"SQL Query Error");
  die(json_encode($message));
}

// Return the data as a JSON object
$message = array("status"=>"Success", "message"=>"Successfully added board game.");
die(json_encode($message));

// Close the database connection
$stmt->close();
$db->close();
?>