const API_URL = import.meta.env.VITE_API_URL

export const authService = {
  async register(userData) {
    const response = await fetch(`${API_URL}/auth/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        nom: userData.nom,
        prenom: userData.prenom,
        email: userData.email,
        password: userData.password
      })
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.error)
    }

    return await response.json()
  },

  async login(userData) {
    const response = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: userData.email,
        password: userData.password
      })
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.error)
    }

    return await response.json()
  }
}
