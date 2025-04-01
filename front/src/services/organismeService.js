const API_URL = import.meta.env.VITE_API_URL

export const organismeService = {
    async getOrganismes() {
        const response = await fetch(`${API_URL}/organismes`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        });
    
        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.error);
        }
        return await response.json();
      },

      async getOrganismeById(id) {
        const response = await fetch(`${API_URL}/organismes/${id}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        });
    
        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.error);
        }
        return await response.json();
      }

}