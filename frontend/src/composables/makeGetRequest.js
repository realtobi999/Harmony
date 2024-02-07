// useGetTrack.js
export function makeGetRequest() {
  const makeGetRequest = async (url, localStorageItemName = null) => {
    try {
      const response = await fetch(
        url,
        {
          method: 'GET',
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('harmony_token'),
          },
        }
      );

      // Handle if the token is expired
      if (response.status === 410) {
        return makeGetRequest(url);
      }

      const data = await response.json();
      if(localStorageItemName){
        localStorage.setItem(localStorageItemName, JSON.stringify(data));
      }
      return data;
    } catch (error) {
      console.error('Error fetching:', error);
      throw error;
    }
  };

  const makePutRequest = async (url, body, localStorageItemName = null) => {
    try {
      const response = await fetch(
        url,
        {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('harmony_token'),
          },
          body: JSON.stringify(body),
        }
      );

      // Handle if the token is expired
      if (response.status === 410) {
        return makePutRequest(url, body, localStorageItemName);
      }

      const data = await response.json();
      if(localStorageItemName){
        localStorage.setItem(localStorageItemName, JSON.stringify(data));
      }
      return data;
    } catch (error) {
      console.error('Error updating:', error);
      throw error;
    }
  };

  const makeDeleteRequest = async (url) => {
    try {
      const response = await fetch(
        url,
        {
          method: 'DELETE',
          headers: {
            Authorization: 'Bearer ' + localStorage.getItem('harmony_token'),
          },
        }
      );

      // Handle if the token is expired
      if (response.status === 410) {
        return makeDeleteRequest(url);
      }

      const data = await response.json();
      return data;
    } catch (error) {
      console.error('Error deleting:', error);
      throw error;
    }
  };

  return { makeGetRequest, makePutRequest, makeDeleteRequest };
}
