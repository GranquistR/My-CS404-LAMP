import FetchAPIService from "./FetchAPIService.js";

export default class TableTopService {
  static async GetAllGames() {
    return FetchAPIService.get("../Controllers/GetBoardGames.php").then(
      (data) => {
        return data;
      }
    );
  }
  static async GetGameById(id) {
    return FetchAPIService.get(
      "../Controllers/GetBoardGames.php?ID=" + id
    ).then((data) => {
      return data;
    });
  }
  static async AddGame(game) {
    console.log(game);
    return FetchAPIService.post("../Controllers/AddBoardGame.php", game).then(
      (data) => {
        return data;
      }
    );
  }
}
