const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:9000'

const handleRequestError = async (response: Response) => {
  const clonedResponse = response.clone();
  
  if (response.ok) {
    try {
      const data = await clonedResponse.json();
      if (data && data.success === false && data.error) {
        throw new Error(data.error.message);
      }
      return response;
    } catch (error) {
      return response;
    }
  }
  
  try {
    const errorData = await clonedResponse.json();
    if (errorData && errorData.error) {
      throw new Error(errorData.error);
    }
    throw new Error(`Erreur de serveur (${response.status})`);
  } catch (error) {
    if (error instanceof Error) {
      throw error;
    }
    throw new Error(`Erreur de serveur (${response.status})`);
  }
}

export const api = {
  async get<T>(url: string, options: RequestInit = {}): Promise<T> {
    try {
      const token = localStorage.getItem('accessToken')
      const headers = new Headers(options.headers || {})
      headers.set('Content-Type', 'application/json')
      
      if (token) {
        headers.set('Authorization', `Bearer ${token}`)
      }
      
      const response = await fetch(`${API_URL}${url}`, {
        ...options,
        method: 'GET',
        headers
      })
      
      await handleRequestError(response)
      return await response.json()
    } catch (error) {
      console.error('Erreur lors de la requête GET:', error)
      throw error
    }
  },

  async post<T>(url: string, data?: any, options: RequestInit = {}): Promise<T> {
    try {
      const token = localStorage.getItem('accessToken')
      const headers = new Headers(options.headers || {})
      headers.set('Content-Type', 'application/json')
      
      if (token) {
        headers.set('Authorization', `Bearer ${token}`)
      }
      
      const response = await fetch(`${API_URL}${url}`, {
        ...options,
        method: 'POST',
        headers,
        body: data ? JSON.stringify(data) : undefined
      })
      
      await handleRequestError(response)
      return await response.json()
    } catch (error) {
      console.error('Erreur lors de la requête POST:', error)
      throw error
    }
  },

  async put<T>(url: string, data?: any, options: RequestInit = {}): Promise<T> {
    try {
      const token = localStorage.getItem('accessToken')
      const headers = new Headers(options.headers || {})
      headers.set('Content-Type', 'application/json')
      
      if (token) {
        headers.set('Authorization', `Bearer ${token}`)
      }
      
      const response = await fetch(`${API_URL}${url}`, {
        ...options,
        method: 'PUT',
        headers,
        body: data ? JSON.stringify(data) : undefined
      })
      
      await handleRequestError(response)
      return await response.json()
    } catch (error) {
      console.error('Erreur lors de la requête PUT:', error)
      throw error
    }
  },

  async delete<T>(url: string, options: RequestInit = {}): Promise<T> {
    try {
      const token = localStorage.getItem('accessToken')
      const headers = new Headers(options.headers || {})
      headers.set('Content-Type', 'application/json')
      
      if (token) {
        headers.set('Authorization', `Bearer ${token}`)
      }
      
      const response = await fetch(`${API_URL}${url}`, {
        ...options,
        method: 'DELETE',
        headers
      })
      
      await handleRequestError(response)
      return await response.json()
    } catch (error) {
      console.error('Erreur lors de la requête DELETE:', error)
      throw error
    }
  }
}

export default api
