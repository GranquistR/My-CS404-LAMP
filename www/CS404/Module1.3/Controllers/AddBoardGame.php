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
if ($Name == NULL || $Description == NULL || $YearReleased == NULL || $BGG_Rating == NULL || $MinPlayers == NULL || $MaxPlayers == NULL || $MinPlayTime == NULL || $MaxPlayTime == NULL || $MinAge == NULL || $GameWeight == NULL || $Designers == NULL || $Artists == NULL || $Publishers == NULL || $ImageURL == NULL) {
  http_response_code(400);
  $message = array("error"=>TRUE, "message"=>"Missing required fields: " . implode(', ', $missingFields));
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
echo json_encode("Success");

// Close the database connection
$stmt->close();
$db->close();
?>