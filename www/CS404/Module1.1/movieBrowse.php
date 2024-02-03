<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Module 1.1 - Movie Browser</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <style>
    .movieSummary {
      border: 1px solid lightgrey;
      border-radius: 10px;
      box-shadow: 3px 3px 6px gray;
      transition: box-shadow 0.3s ease-in-out;
      text-align: center;
      padding: 5px;
      margin-bottom: 15px;
      vertical-align: bottom;
    }
    .summaryTitle { display: block; font-size: medium; height: 4.5em; }
    .summaryInfo { display: block; font-size: medium; height: 5em; }
    </style>
  </head>
  <body>
    <div class='container'>
      <div class='row'>
        <div class="pb-2 mt-4 mb-2 border-bottom" style="width: 100%;">
          <h1>MyFlix Movie Browser</h1>
          Click on a movie below for more information.
        </div>
      </div>
<?php
// Establish the database connection
include "database.php"; // TODO: <-- Don't forget to update this file with your username and password
$db = connectToDatabase("myFlix");
if($db == NULL) {
  die("<p>Failed to connect to database.</p></body></html>\n");
}

// Prepare and execute a query for the basic movie information
$stmt = simpleQuery($db, "SELECT id, title, year, genres, image, imdbrating FROM movies;");
if($stmt == NULL) {
  die("<p>SQL Query Error</p></body></html>\n");
}

// Bind variables to the results (same order as in the query)
$stmt->bind_result($movieID, $movieName, $movieYear, $movieGenre, $movieImage, $movieRating);

//Process the resutls and output in bootstrap grid form
echo "      <div class='row'>\n";

while($stmt->fetch()) {
  // Output a grid cell for the current movie
?>
        <div class='col-sm-6 col-md-4 col-lg-3'>
          <div class='movieSummary' onmouseover='overCard(this)' onmouseout='outCard(this)'>
            <a href='movieDetails.php?movieID=<?=$movieID?>'>
              <span class="summaryTitle"><?=$movieName?></span>
              <img src="../../../thumbs/<?=$movieImage?>" alt="<?=$movieName?>" height="250px">
            </a><br>
            <span class="summaryInfo"><?=$movieGenre?><br><?=$movieYear?>, <?=$movieRating?>/10</span>
            
          </div> <!-- /movieSummary-->
        </div> <!-- /col* -->
<?php
} //end of while loop

echo "      </div> <!-- /row -->\n";

// Close the database connection
$stmt->close();
$db->close();
?>
    </div> <!-- /container -->

    <!-- Custom JS -->
    <script>
      function overCard(card) {
        card.style.boxShadow = "5px 5px 10px gray";
      }

      function outCard(card) {
        card.style.boxShadow = "3px 3px 6px gray";
      }
    </script>

    <!-- Bootstrap JS (with popper integerated) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>