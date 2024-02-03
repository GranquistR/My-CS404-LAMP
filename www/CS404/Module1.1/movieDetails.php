<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Module 1.1 - Movie Details</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  </head>
  <body>
    <div class='container'>
      <div class='row'>      
        <div class="pb-2 mt-4 mb-2 border-bottom">
          <h1>MyFlix Movie Details</h1>
        </div>
      </div>
<?php
if(isset($_GET['movieID']))
{
  // Get ID of movie from form GET data
  $movieID = $_GET['movieID'];

  // 1. Connect to the database
  include "database.php"; // TODO: <-- Don't forget to update this file with your username and password
  $db = connectToDatabase("** TODO: Fill in your DB name **");
  if ($db->connect_error) {
    die("<p>Failed to connect to database.</p></body></html>\n");
  }

  // 2. Run the Query
  $query = "SELECT title,year,rated,imdbrating,description,image,genres,directors,writers,actors FROM movies WHERE id=?;";
  $stmt = simpleQueryParam($db, $query, "s", $movieID);
  if($stmt == NULL) {
    die("<p>SQL Query Error</p></body></html>\n");
  }

  // 3. Bind and access the result variables
  if(!$stmt->bind_result($movieName, $movieYear, $movieRated, $movieIMDB, $movieDesc, $movieImage,
    $movieGenres, $movieDirectors, $movieWriters, $movieActors)) {
    die("<p>Query Result Binding Failed: " . $stmt->error . "</p></div></body></html>");
  }

  // Fetch and display the results
  if($stmt->fetch()) {
    // TODO: Adjust the following to present all of this information in a more 
    //   organized format. You must use an image tag to display the full resolution
    //   poster, a table to organize all the data (or the Bootstrap column system),
    //   and CSS to keep things looking styled similar to the movieBrowse page.
    //   Feel free to use Bootstrap classes to achieve better styling.
?>
        <ul>
          <li>ID = <?=$movieID?></li>
          <li>Name = <?=$movieName?></li>
          <li>Year = <?=$movieYear?></li>
          <li>Rating = <?=$movieRated?></li>
          <li>IMDB Score = <?=$movieIMDB?></li>
          <li>Desc = <?=$movieDesc?></li>
          <li>Image = <?=$movieImage?></li>
          <li>Genres = <?=$movieGenres?></li>
          <li>Directors = <?=$movieDirectors?></li>
          <li>Writers = <?=$movieWriters?></li>
          <li>Actors = <?=$movieActors?></li>
        </ul>
<?php
  }
} else { // Not isset($_GET['movieID'])
?>
        <p>Error: no movie ID provided.</p>
<?php
}
?>
    </div> <!-- /container -->

    <!-- Bootstrap JS (with popper integerated) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>