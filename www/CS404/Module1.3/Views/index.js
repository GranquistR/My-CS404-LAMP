import TableTopService from "../Services/TableTopService.js";

//get and insert all games
TableTopService.GetAllGames().then((games) => {
  //order games by rating
  games.sort((a, b) => a.BGG_Rating - b.BGG_Rating);
  games.forEach((game) => {
    createAndInsertCard(game);
  });
});

//get individual game info and insert into modal
function showGameInfo(id) {
  TableTopService.GetGameById(id).then((game) => {
    const modalTitle = document.querySelector(".modal-title");
    modalTitle.innerHTML = `
    <div>${game.Name}</div>
    `;

    const modalBody = document.querySelector(".modal-body");
    modalBody.innerHTML = `
    <div class="card">
    <div class="main">
      <img src="../thumbs/${game.ImageURL}" alt="Game" height="200px"/>
        <div class="rating">
          <div class="ratingPart">${game.BGG_Rating}</div>
          <div class="ratingPart">out of</div>
          <div class="ratingPart">10</div>
        </div>
        <div class="title">
        <h1>${game.Name}</h1>
        <div class="description">
            ${game.Description}
        </div>
        <br />
        <div>Release Year:${game.YearReleased}</div>
        </div>
        <div>
          <h2 class="credits">Credits</h2>
          <div class="credits">
            Designers: ${game.Designers}
          </div>
          <div class="credits">
            Publishers: ${game.Publishers}
          </div>
          <div class="credits">
            Artists: ${game.Artists}
          </div>
      </div>
    </div>
    <div class="content">
        <div>
        <h2>${game.MinPlayers}-${game.MaxPlayers} Player</h2>
        <div>Possible Players</div>
        </div>
        <div>
        <h2>${game.MinPlayTime}-${game.MaxPlayTime} Min</h2>
        <div>Playing Time</div>
        </div>
        <div>
        <h2>${game.MinAge} Years</h2>
        <div>Minimum Age</div>
        </div>
        <div>
        <h2>${game.GameWeight}/5 Weight</h2>
        <div>'Complexity' rating</div>
        </div>
    </div>

 
    </div>
    `;
  });
}

//open modal handling
window.openModal = function (id) {
  $("#gameModal").modal("show");
  showGameInfo(id);
};

//add and insert functionality
window.SubmitAddForm = function () {
  var Artists = $("#artists").val();
  var BGG_Rating = $("#bgg_rating").val();
  var Description = $("#description").val();
  var Designers = $("#designers").val();
  var GameWeight = $("#game_weight").val();
  var ImageURL = $("#image_url").val();
  var MaxPlayTime = $("#max_play_time").val();
  var MinPlayers = $("#max_players").val();
  var MinAge = $("#min_age").val();
  var MinPlayTime = $("#min_play_time").val();
  var MaxPlayers = $("#min_players").val();
  var Name = $("#name").val();
  var Publishers = $("#publishers").val();
  var YearReleased = $("#year_released").val();
  //insert into json of same format
  var game = {
    Artists: Artists,
    BGG_Rating: BGG_Rating,
    Description: Description,
    Designers: Designers,
    GameWeight: GameWeight,
    ImageURL: ImageURL,
    MaxPlayTime: MaxPlayTime,
    MinPlayers: MinPlayers,
    MinAge: MinAge,
    MinPlayTime: MinPlayTime,
    MaxPlayers: MaxPlayers,
    Name: Name,
    Publishers: Publishers,
    YearReleased: YearReleased,
  };
  TableTopService.AddGame(game).then((data) => {
    if (data.status != "Success") {
      alert(data.message);
    } else {
      //closes clears and creates
      $("#addModal").modal("hide");
      $("input").val("");
      createAndInsertCard(game);
    }
  });
};

//adds new card to top of list
function createAndInsertCard(data) {
  const card = document.createElement("div");
  card.classList.add("col-sm-6", "col-md-4", "col-lg-3");
  card.innerHTML = `
                    <div
                        class="Summary"
                        onmouseover="overCard(this)"
                        onmouseout="outCard(this)"
                        onclick="openModal(${data.ID})"
                    >
                        <h3 class="summaryTitle">${data.Name}</h3>
                        <img
                            src="../thumbs/${data.ImageURL}"
                            alt="${data.Name}"
                            style="max-width: 100%;"
                            height="250px"
                        /> 
                        <br />
                        <span class="summaryInfo">${data.Description}<br />${data.YearReleased}, ${data.BGG_Rating}/10</span>
                    </div>
                `;
  if (cards.firstChild) {
    cards.insertBefore(card, cards.firstChild);
  } else {
    cards.appendChild(card);
  }
}
