const API_URL = import.meta.env.VITE_API_URL

export const sectionService = {
  async getSectionsByOrganisme(id) {
    const response = await fetch(`${API_URL}/organismes/${id}/sections`, {
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

  async getSectionById(id) {
    const response = await fetch(`${API_URL}/sections/${id}`, {
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

  async deleteSection(id) {
    const response = await fetch(`${API_URL}/sections/${id}`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error);
    }
    return response.status;
  },

  async updateSection(id, section) {
    const response = await fetch(`${API_URL}/sections/${id}`, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
      body: JSON.stringify({
        nom: section.nom,
        description: section.description,
        categorie: section.categorie,
        capacite: section.capacite,
        tarif: section.tarif,
        organisme_id: section.organisme_id,
      }),
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error);
    }
    return response.status;
  },

  async getSectionsByUser(id) {
    const response = await fetch(`${API_URL}/users/${id}/sections`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error);
    }
    const data = await response.json();

    return data.sections || [];
  },

  async createSection(section) {
    const response = await fetch(`${API_URL}/sections`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
      body: JSON.stringify({
        nom: section.nom,
        description: section.description,
        categorie: section.categorie,
        capacite: section.capacite,
        tarif: section.tarif,
        organisme_id: section.organisme_id,
      }),
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error);
    }
    return response.status;
  }
};
