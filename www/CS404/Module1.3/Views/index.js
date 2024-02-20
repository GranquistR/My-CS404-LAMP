import TableTopService from "../Services/TableTopService.js";
// Get all games
TableTopService.GetAllGames().then((games) => {
  console.log(games);
  //order games by rating
  games.sort((a, b) => a.BGG_Rating - b.BGG_Rating);
  games.forEach((game) => {
    const card = document.createElement("div");
    card.classList.add("col-sm-6", "col-md-4", "col-lg-3");
    card.innerHTML = `
                      <div
                          class="Summary"
                          onmouseover="overCard(this)"
                          onmouseout="outCard(this)"
                          onclick="openModal(${game.ID})"
                      >
                          <h3 class="summaryTitle">${game.Name}</h3>
                          <img
                              src="../thumbs/${game.ImageURL}"
                              alt="${game.Name}"
                              style="max-width: 100%;"
                              height="250px"
                          /> 
                          <br />
                          <span class="summaryInfo">${game.Description}<br />${game.YearReleased}, ${game.BGG_Rating}/10</span>
                      </div>
                  `;
    if (cards.firstChild) {
      cards.insertBefore(card, cards.firstChild);
    } else {
      cards.appendChild(card);
    }
  });
});

function showGameInfo(id) {
  TableTopService.GetGameById(id).then((game) => {
    console.log(game);
    const modalTitle = document.querySelector(".modal-title");
    console.log(modalTitle);
    modalTitle.innerHTML = `
    <div>${game[0].Name}</div>
    `;

    const modalBody = document.querySelector(".modal-body");
    modalBody.innerHTML = `
    <div class="card">
    <div class="main">
      <img src="../thumbs/${game[0].ImageURL}" alt="Game" height="200px"/>
        <div class="rating">
          <div class="ratingPart">${game[0].BGG_Rating}</div>
          <div class="ratingPart">out of</div>
          <div class="ratingPart">10</div>
        </div>
        <div class="title">
        <h1>${game[0].Name}</h1>
        <div class="description">
            ${game[0].Description}
        </div>
        <br />
        <div>Release Year:${game[0].YearReleased}</div>
        </div>
        <div>
          <h2 class="credits">Credits</h2>
          <div class="credits">
            Designers: ${game[0].Designers}
          </div>
          <div class="credits">
            Publishers: ${game[0].Publishers}
          </div>
          <div class="credits">
            Artists: ${game[0].Artists}
          </div>
      </div>
    </div>
    <div class="content">
        <div>
        <h2>${game[0].MinPlayers}-${game[0].MaxPlayers} Player</h2>
        <div>Possible Players</div>
        </div>
        <div>
        <h2>${game[0].MinPlayTime}-${game[0].MaxPlayTime} Min</h2>
        <div>Playing Time</div>
        </div>
        <div>
        <h2>${game[0].MinAge} Years</h2>
        <div>Minimum Age</div>
        </div>
        <div>
        <h2>${game[0].GameWeight}/5 Weight</h2>
        <div>'Complexity' rating</div>
        </div>
    </div>

 
    </div>
    `;
  });
}

window.openModal = function (id) {
  $("#gameModal").modal("show");
  showGameInfo(id);
};
