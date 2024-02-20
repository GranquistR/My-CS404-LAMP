export default class FetchAPIService {
  static async get(url) {
    let response;
    try {
      response = await fetch(url, {
        mode: "cors",
        method: "GET",
      });
      if (!response.ok) {
        throw new Error("Network response was not ok.");
      }
    } catch (error) {
      console.error("Network Error in FetchAPI Service: ", error);
      throw error;
    }

    try {
      if (response) {
        const data = await response.json();
        return data;
      }
    } catch (error) {
      throw new Error("Error parsing JSON");
    }
  }

  static async post(url, data) {
    let response;
    try {
      response = await fetch(url, {
        mode: "cors",
        method: "POST",
        body: JSON.stringify(data),
        headers: { "Content-Type": "application/json" },
      });
      if (!response.ok) {
        throw new Error("Network response was not ok.");
      }
    } catch (error) {
      console.error("Network Error in FetchAPI Service: ", error);
      throw error;
    }

    try {
      if (response) {
        const data = await response.json();
        return data;
      }
    } catch (error) {
      throw new Error("Error parsing JSON");
    }
  }
}
