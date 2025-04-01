const API_URL = import.meta.env.VITE_API_URL

export const encadrantService = {
  async getEncadrantsByUser(id){
    const response = await fetch(`${API_URL}/users/${id}/encadrants`, {
      method: 'GET',
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      }
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.error)
    }

    const data = await response.json();

    return data.encadrants || [];
  },

  async removeEncadrantFromSection(id, encadrantId){
    const response = await fetch(`${API_URL}/sections/${id}/encadrants/${encadrantId}`, {
      method: 'DELETE',
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      }
    })

    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.error)
    }
  }
}
