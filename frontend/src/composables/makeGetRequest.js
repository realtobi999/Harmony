// useGetTrack.js
export function makeGetRequest() {
  const makeGetRequest = async (url, localStorageItemName = null) => {
    try {
      const harmony_token = getCookie('harmony_token')

      const response = await fetch(url, {
        method: 'GET',
        headers: {
          Authorization: 'Bearer ' + harmony_token
        }
      })

      // Handle if the token is expired
      if (response.status === 410) {
        return makeGetRequest(url)
      }

      const data = await response.json()
      if (localStorageItemName) {
        localStorage.setItem(localStorageItemName, JSON.stringify(data))
      }
      return data
    } catch (error) {
      console.error('Error fetching:', error)
    }
  }

  const makePutRequest = async (url, body, localStorageItemName = null) => {
    try {
      const harmony_token = getCookie('harmony_token')

      const response = await fetch(url, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          Authorization: 'Bearer ' + harmony_token
        },
        body: JSON.stringify(body)
      })

      // Handle if the token is expired
      if (response.status === 410) {
        return makePutRequest(url, body, localStorageItemName)
      }

      const data = await response.json()
      if (localStorageItemName) {
        localStorage.setItem(localStorageItemName, JSON.stringify(data))
      }
      return data
    } catch (error) {
      console.error('Error updating:', error)
    }
  }

  const makeDeleteRequest = async (url) => {
    try {
      const harmony_token = getCookie('harmony_token')

      const response = await fetch(url, {
        method: 'DELETE',
        headers: {
          Authorization: 'Bearer ' + harmony_token
        }
      })

      // Handle if the token is expired
      if (response.status === 410) {
        return makeDeleteRequest(url)
      }

      const data = await response.json()
      return data
    } catch (error) {
      console.error('Error deleting:', error)
    }
  }

  const getCookie = (name) => {
    const cookies = document.cookie.split('; ')
    let value

    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i].split('=')
      if (cookie[0] === name) {
        value = cookie[1]
        break
      }
    }

    return value
  }

  return { makeGetRequest, makePutRequest, makeDeleteRequest }
}
